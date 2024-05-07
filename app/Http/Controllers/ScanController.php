<?php

namespace App\Http\Controllers;

use App\Models\BookingDetail;
use App\Models\Event;
use App\Models\Scan;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($event_id)
    {

        if(!Controller::checkPermission('scan_eventi'))
            return redirect()->route('dashboard');

        $evento = Event::find($event_id);

        $tenant = Tenant::find($evento->tenant_id);

        if(auth()->user()->role_id != 1){
            if(auth()->user()->tenant_id != $tenant->id){
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Unauthorized'
                ], 401);
            }
        }

        return view('scan.index', [
            'event_id' => $event_id
        ]);
    }

    public function scan(Request $request){
        $codice_prenotazione = $request->get('qr_code_data');
        $evento = Event::find($request->get('event_id'));

        $booking_detail = BookingDetail::where('booking_code', $codice_prenotazione)->first();

        if(!$booking_detail){
            return response()->json([
                'status' => 'error',
                'message' => 'Codice non valido: prenotazione non trovata',
                'title' => 'Attenzione'
            ]);
        }

        if(!$evento){
            return response()->json([
                'status' => 'error',
                'message' => 'Codice non valido: evento non trovato',
                'title' => 'Attenzione'
            ]);
        }

        if($booking_detail->event_id != $evento->id){
            return response()->json([
                'status' => 'error',
                'message' => 'Codice non valido: prenotazione non valida per questo l\'evento',
                'title' => 'Attenzione'
            ]);
        }

        if(!$booking_detail->is_confirmed){
            return response()->json([
                'status' => 'error',
                'message' => 'Codice non valido: prenotazione non confermata',
                'title' => 'Attenzione'
            ]);
        }

        if($booking_detail->tenant_id != $evento->tenant_id){
            return response()->json([
                'status' => 'error',
                'message' => 'Codice non valido: prenotazione non valida per questo evento',
                'title' => 'Attenzione'
            ]);
        }

        if($booking_detail->tenant_id != auth()->user()->tenant_id){
            return response()->json([
                'status' => 'error',
                'message' => 'Codice non valido: prenotazione non valida per questo cliente',
                'title' => 'Attenzione'
            ]);
        }

        if($booking_detail->arrived_at){
            return response()->json([
                'status' => 'error',
                'message' => 'Codice non valido: prenotazione già utilizzata',
                'title' => 'Attenzione'
            ]);
        }

        $booking_detail->arrived_at = now();
        $booking_detail->user_puncher_id = auth()->user()->id;
        $booking_detail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Codice valido: prenotazione confermata',
            'event_name' => $evento->name,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Scan $scan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scan $scan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scan $scan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scan $scan)
    {
        //
    }
}
