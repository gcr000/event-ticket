<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Event;
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

        if(!$event)
            return response()->json(['message' => 'Event not found'], 404);

        return view('bookings.index',[
            'event' => $event,
        ]);
    }

    public function pay(Request $request){
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

    public function success($details, $event_id, $email){

        $dati = json_decode(Controller::decryptId($details), true);

        $id_paypal = $dati['id'];
        $payer_name = $dati['payer']['name']['given_name'];
        $payer_surname = $dati['payer']['name']['surname'];
        $payer_email = $dati['payer']['email_address'];
        $payer_id = $dati['payer']['payer_id'];
        $amount = $dati['purchase_units'][0]['amount']['value'];
        $currency = $dati['purchase_units'][0]['amount']['currency_code'];

        $dettagli = Controller::decryptId($details);

        info($email);
        info($event_id);

        $event = Event::find($event_id);
        if(!$event)
            return response()->json(['message' => 'Event not found'], 404);

        $booking = Booking::query()->where('event_id', $event_id)->where('email', $email)->first();

        info($booking);
        if(!$booking)
            return response()->json(['message' => 'Booking not found'], 404);

        $booking->is_confirmed = 1;
        $booking->confirmed_at = now();
        if($booking->save()){
            $booking_details = BookingDetail::query()->where('booking_id', $booking->id)->get();

            foreach ($booking_details as $detail){
                $detail->is_confirmed = 1;
                $detail->save();
            }

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
                'request' => request()->all(),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'msg' => 'Errore conferma prenotazione',
                'request' => request()->all(),
            ]);
        }
    }
}
