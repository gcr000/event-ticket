<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\BookingSendCodeMail;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::query()->where('tenant_id', auth()->user()->tenant_id);

        if(request()->has('type'))
            if(request('type') == 'archiviati')
                $events->where('is_archiviato', true);
            else
                $events->where('is_archiviato', false);

        return view('events.index', [
            'events' => $events->get(),
        ]);
    }

    public function create()
    {
        return view('events.create',[
            'locations' => Location::query()->where('tenant_id', auth()->user()->tenant_id)->get(),
        ]);
    }

    public function store(Request $request)
    {
        if($request->singolo_giorno) {
            $datetime_from = $request->datetime_from . ' ' . $request->time_from;
            $datetime_to = $request->datetime_from . ' ' . $request->time_from;
            $is_single_day = true;
        } else {
            $datetime_from = $request->datetime_from . ' ' . $request->time_from;
            $datetime_to = $request->datetime_to . ' ' . $request->time_to;
            $is_single_day = false;
        }

        if(!$request->partecipation)
            $max_prenotabili = 10000000;
        else
            $max_prenotabili = $request->max_prenotabili;

        if($request->payment_request == 0)
            $payment_request = 'no';
        elseif($request->payment_request == 1)
            $payment_request = 'si, pagamento parziale';
        elseif ($request->payment_request == 2)
            $payment_request = 'si, pagamento completo';
        else
            $payment_request = null;


        $ref = User::find($request->referente_id);

        Event::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $request->name,
            'description' => $request->description,
            'datetime_from' => $datetime_from,
            'datetime_to' => $datetime_to,
            'max_prenotabili' => $max_prenotabili,
            'ticket_for_person' => $request->ticket_for_person,
            'show_referente' => $request->show_referente,
            'is_single_day' => $is_single_day,
            'user_id' => auth()->user()->id,
            'location_id' => $request->location_id,
            'is_payment_required' => $payment_request,
            'price' => $request->payment_request_input ?? null,
            //'referente' => $ref->name . ' ' . $ref->email . ' | ' . $ref->phone_number,
            'ref_user_name' => $ref->name,
            'ref_user_email' => $ref->email,
            'ref_user_phone_number' => $ref->phone_number,
            'ref_user_id' => $ref->id,
        ]);

        return redirect()->route('events.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if(!self::checkSameTenant($event))
            return redirect()->route('events.index')->with('error', 'Accesso non autorizzato!');

        $base64Id = Controller::encryptId($event->id);
        $urlToEvent = env('APP_URL') . '/bookings/' . $base64Id;

        return view('events.show', [
            'event' => $event,
            'urlToEvent' => $urlToEvent,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }

    public function booking($base64EventiId)
    {
        /*$eventId = base64_decode($base64EventiId);
        $event = Event::find($eventId);

        if(!$event)
            return response()->json(['message' => 'Event not found'], 404);

        return view('bookings.index',[
            'event' => $event,
        ]);*/
    }

    public function store_booking(Request $request)
    {
        $arr_utenti = $request->all();
        $first_person = new \stdClass();
        $other_persons = [];

        $count_utenti = count($arr_utenti);

        foreach ($arr_utenti as $item){
            if($item['first_person'] == 'true'){
                $first_person = $item;
            } else {
                $other_persons[] = $item;
            }
        }

        $event_id = $first_person['event_id'];
        $event = Event::find($event_id);

        if(!$event)
            return response()->json(['message' => 'Event not found', 'status' => 'ko'], 404);

        if($event->prenotati >= $event->max_prenotabili)
            return response()->json(['code' => 1,'message' => 'Posti esauriti', 'status' => 'ko'], 400);

        if($count_utenti > $event->max_prenotabili)
            return response()->json(['code' => 2,'message' => 'Non ci sono abbastanza posti', 'status' => 'ko'], 400);

        if($count_utenti > $event->max_prenotabili - $event->prenotati)
            return response()->json(['code' => 3,'message' => 'Non ci sono abbastanza posti', 'status' => 'ko'], 400);

        /*if($event->prenotati >= $event->max_prenotabili)
            return response()->json(['message' => 'Posti esauriti', 'status' => 'ko'], 400);*/

        // se la mail è già stata usata per una prenotazione per lo stesso evento
        if(Booking::where('email', $first_person['email'])->where('event_id', $event->id)->first())
            return response()->json(['message' => 'Email già utilizzata per questo evento', 'status' => 'ko'], 400);

        // se il phone_number è già stato usato per una prenotazione per lo stesso evento
        if(Booking::where('phone_number', $first_person['phone_number'])->where('event_id', $event->id)->first())
            return response()->json(['message' => 'Numero di telefono già utilizzato per questo evento', 'status' => 'ko'], 400);

        $customer_email = $first_person['email'];
        $customer_name = $first_person['nome'];
        $customer_phone = $first_person['phone_number'];

        if(!$customer_email || !$customer_name || !$customer_phone)
            return response()->json(['message' => 'All fields are required', 'status' => 'ko'], 400);

        if(!filter_var($customer_email, FILTER_VALIDATE_EMAIL))
            return response()->json(['message' => 'Invalid email', 'status' => 'ko'], 400);


        // salvo il booking
        $booking = new Booking();
        $booking->tenant_id = $event->tenant_id;
        $booking->event_id = $event->id;
        $booking->otp = rand(100000,999999);
        $booking->phone_number = $customer_phone;
        $booking->email = $customer_email;
        $booking->name = $customer_name;
        $booking->quantity = $count_utenti;
        $booking->ip_address = $request->ip();

        if($booking->save()){
            // salvo la prenotazione del primo partecipante, quello che ha effettuato la prenotazione
            $bookingDetail = new \App\Models\BookingDetail();
            $bookingDetail->tenant_id = $event->tenant_id;
            $bookingDetail->event_id = $event->id;
            $bookingDetail->booking_id = $booking->id;
            $bookingDetail->email = $customer_email;
            $bookingDetail->name = $customer_name;
            $bookingDetail->phone_number = $customer_phone;
            $bookingDetail->save();

            $bookingDetail->booking_code = time() . strtoupper(Str::random(1)) . $booking->id . strtoupper(Str::random(1)) .$bookingDetail->id;
            $bookingDetail->save();
            // salvo i partecipanti aggiuntivi
            foreach ($other_persons as $item){
                $bookingDetail = new \App\Models\BookingDetail();
                $bookingDetail->tenant_id = $event->tenant_id;
                $bookingDetail->event_id = $event->id;
                $bookingDetail->booking_id = $booking->id;
                $bookingDetail->email = '-';
                $bookingDetail->name = $item['nome'];
                $bookingDetail->phone_number = $item['phone_number'];
                $bookingDetail->save();
                $bookingDetail->booking_code = time() . strtoupper(Str::random(1)) . $booking->id . strtoupper(Str::random(1)) .$bookingDetail->id;
                $bookingDetail->save();
            }


            $urlToEvent = env('APP_URL') . '/bookings/' . Controller::encryptId($event->id);

            Mail::to($booking->email)->send(new BookingSendCodeMail($booking->otp, $event->name, $customer_name, $urlToEvent));

            return response()->json([
                'phone_code' => $booking->otp,
                'msg' => '',
                'data_richiesta' => date('d/m/Y H:i'),
                'status' => 'ok',
                'booking' => $booking
            ]);
        }





        // se esiste già una prenotazione con la stessa email e lo stesso evento
        //$booking = Booking::where('email', $customer_email)->where('event_id', $event->id)->first();

        /*if(!$booking){
            $booking = new Booking();
            $phone_code = rand(100000,999999);
            $msg = '';
            $data_richiesta = date('d/m/Y H:i');
        } else {
            $msg = 'You have already booked for this event';
            $phone_code = $booking->otp;
            $data_richiesta = date('d/m/Y H:i', strtotime($booking->created_at));
        }

        $booking->event_id = $event->id;
        $booking->email = $customer_email;
        $booking->name = $customer_name;
        $booking->otp = $phone_code;
        $booking->phone_number = $customer_phone;
        $booking->tenant_id = $event->tenant_id;
        $booking->save();
        $status = 'ok';

        return response()->json([
            'phone_code' => $phone_code,
            'msg' => $msg,
            'data_richiesta' => $data_richiesta,
            'status' => $status,
            'booking' => $booking
        ]);*/
    }

    public function confirmation()
    {
        $booking_id = request()->route('booking_id');
        $booking = Booking::find($booking_id);

        return view('bookings.confirmation',[
            'phone_code' => $booking->otp,
            'booking' => $booking,
            'msg' => '',
            'status' => $booking->is_confirmed ? 'Prenotazione confermata' : 'In attesa di conferma',
            'data_richiesta' => date('d/m/Y H:i', strtotime($booking->created_at)),
        ]);
    }

    public function check_otp(Request $request)
    {
        $booking = Booking::find($request->booking_id);

        if(!$booking)
            return response()->json(['message' => 'Booking not found', 'status' => 'ko'], 404);

        if($booking->otp != $request->otp)
            return response()->json(['message' => 'Invalid OTP', 'status' => 'ko'], 400);

        $booking->is_confirmed = true;
        $booking->confirmed_at = date('Y-m-d H:i:s');
        $booking->save();

        $event = Event::find($booking->event_id);
        $event->prenotati = $event->prenotati + $booking->quantity;
        $booking->save();
        $event->save();

        foreach ($booking->details as $detail){
            $detail->is_confirmed = true;
            $detail->save();
        }

        // send BookingEmail
        $content = [
            'subject' => 'Conferma prenotazione evento',
            'urlToEvent' => env('APP_URL') . '/bookings/' . Controller::encryptId($event->id),
            'title' => strtoupper($event->name),
            'day' => date('d/m/Y', strtotime($event->datetime_from)),
            'time' => date('H:i', strtotime($event->datetime_from)),
            'where' => $event->location->name . ' - ' . $event->location->address,
            'location_number' => $event->location->phone_number,
            'location_email' => $event->location->email,
            'referente' => $event->ref_user_name . ' ' . $event->ref_user_email . ' | ' . $event->ref_user_phone_number,
            'codes' => $booking->details,
            'show_referente' => $event->show_referente
        ];

        Mail::to($booking->email)->send(new BookingConfirmation($content));

        return response()->json([
            'status' => 'ok',
            'msg' => 'Prenotazione confermata',
            'request' => $request->all(),
        ]);
    }

    public function last_location_events($location_id)
    {
        $events = Event::where('location_id', $location_id)->where('datetime_from', '>', now())->orderBy('datetime_from', 'asc')->get();
        return response()->json($events);
    }

    public function check_event_data(Request $request){
        $location = Location::find($request->location_id)->first();
        $data = $request->data;

        // check in the database if the event already exists in the same location and in the same date
        $eventInDate = Event::query()

            ->where('location_id', $request->location_id)
            ->whereDate('datetime_from', '>=', $data)
            ->whereDate('datetime_to', '<=', $data)

            ->orWhereDate('datetime_from', '<=', $data)
            ->whereDate('datetime_to', '>=', $data)
            ->where('location_id', $request->location_id)

            ->orWhereDate('datetime_from', '<=', $data)
            ->whereDate('datetime_to', '>=', $data)
            ->where('location_id', $request->location_id)

            ->orWhereDate('datetime_from', '>=', $data)
            ->whereDate('datetime_to', '<=', $data)
            ->where('location_id', $request->location_id)

            ->first();


        if($eventInDate)
            return response()->json([
                'message' => 'Evento già presente in questa data',
                'event_id' => $eventInDate->id
            ]);
        else
            return response()->json([
                'message' => 'Evento non presente in questa data',
                'event_id' => null
            ]);
    }

    public function check_event_email(Request $request){
        $event = Event::find($request->event_id);
        $email = $request->email;

        // check in the database if the email is already used for the same event
        $booking = Booking::query()
            ->where('event_id', $event->id)
            ->where('email', $email)
            ->first();

        if($booking)
            return response()->json([
                'message' => 'Email già utilizzata per questo evento',
                'status' => 'ko'
            ]);
        else
            return response()->json([
                'message' => 'Email non utilizzata per questo evento',
                'status' => 'ok'
            ]);
    }

    public function archivia_evento(Event $event)
    {
        $event->is_archiviato = true;
        $event->save();
        return response()->json(['message' => 'Evento archiviato', 'status' => 'ok']);
    }
}
