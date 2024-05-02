<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Omnipay\Omnipay;

class BookingController extends Controller
{

    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_SECRET'));
        $this->gateway->setTestMode(env('PAYPAL_SANDBOX'));
    }

    public function booking($base64EventiId)
    {
        $eventId = Controller::decryptId($base64EventiId);
        $event = Event::find($eventId);

        if (!$event)
            return response()->json(['message' => 'Event not found'], 404);

        return view('bookings.index', [
            'event' => $event,
        ]);
    }

    public function pay(Request $request)
    {
        try {
            $response = $this->gateway->purchase([
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'description' => 'Booking for event',
                'returnUrl' => url('/booking/success'),
                'cancelUrl' => url('/booking/error'),
            ])->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return response()->json(['message' => $response->getMessage()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);

        }
    }

    public function save_paypal(Request $request)
    {

        $details = $request->details;
        $event_id = $request->event_id;
        $dati = base64_decode($details);
        $dati = json_decode($dati, true);

        $id_paypal = $dati['id'];
        $payer_name = $dati['payer']['name']['given_name'];
        $payer_surname = $dati['payer']['name']['surname'];
        $payer_email = $dati['payer']['email_address'];
        $payer_id = $dati['payer']['payer_id'];
        $amount = $dati['purchase_units'][0]['amount']['value'];
        $currency = $dati['purchase_units'][0]['amount']['currency_code'];
        $payment_status = $dati['purchase_units'][0]['payments']['captures'][0]['status'];


        $event = Event::find($event_id);
        if (!$event)
            return response()->json(['message' => 'Event not found'], 404);

        $booking = Booking::query()->where('event_id', $event_id)->where('email', $request->email)->first();

        if (!$booking)
            return response()->json(['message' => 'Booking not found'], 404);

        $booking->is_confirmed = 1;
        $booking->confirmed_at = now();
        if ($booking->save()) {
            $booking_details = BookingDetail::query()->where('booking_id', $booking->id)->get();

            foreach ($booking_details as $detail) {
                $detail->is_confirmed = 1;
                $detail->save();
            }

            // save Payment data
            $newPayment = new Payment();
            $newPayment->payment_id = $id_paypal;
            $newPayment->payer_id = $payer_id;
            $newPayment->payer_name = $payer_name;
            $newPayment->payer_surname = $payer_surname;
            $newPayment->payer_email = $payer_email;
            $newPayment->amount = $amount;
            $newPayment->currency = $currency;
            $newPayment->booking_id = $booking->id;
            $newPayment->event_id = $event_id;
            $newPayment->payment_status = $payment_status;
            $newPayment->save();


            // send BookingEmail
            $content = [
                'subject' => 'Conferma prenotazione evento',
                'urlToEvent' => env('APP_URL') . '/bookings/' . base64_encode($event->id),
                'title' => strtoupper($event->name),
                'day' => date('d/m/Y', strtotime($event->datetime_from)),
                'time' => date('H:i', strtotime($event->datetime_from)),
                'where' => $event->location->name . ' - ' . $event->location->address,
                'location_number' => $event->location->phone_number,
                'location_email' => $event->location->email,
                'referente' => $event->location->ref_user_name . ' ' . $event->location->ref_user_email . ' | ' . $event->location->ref_user_phone_number,
                'codes' => $booking->details,
                'show_referente' => $event->show_referente
            ];

            Mail::to($booking->email)->send(new BookingConfirmation($content));

            return response()->json([
                'status' => 'ok',
                'msg' => 'Prenotazione confermata',
                'tenant' => $event->tenant,
                'booking' => $booking,
            ]);

        }

    }

    public function success($booking_id)
    {

        $booking = Booking::find($booking_id);
        $event = $booking->event;

        return view('bookings.success', [
            'event' => $event,
            'booking' => $booking,
            'tenant' => $event->tenant,
        ]);
    }
}
