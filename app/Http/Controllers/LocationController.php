<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Controller::checkPermission('lista_sedi'))
            return redirect()->route('dashboard');

        $locations = Location::query();

        if(auth()->user()->role_id != 1)
            $locations = $locations->where('tenant_id', auth()->user()->tenant_id);


        return view('locations.index', [
            'locations' => $locations->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Controller::checkPermission('crea_sedi'))
            return redirect()->route('dashboard');

        return view('locations.create', [
            'users' => User::query()->where('tenant_id', auth()->user()->tenant_id)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Controller::checkPermission('crea_sedi'))
            return redirect()->route('dashboard');

        $loc = Location::create($request->all());
        $loc->tenant_id = auth()->user()->tenant_id;
        $loc->save();

        self::customLog('Sede creata');
        return redirect()->route('locations.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        if(!Controller::checkPermission('dettaglio_sedi'))
            return redirect()->route('dashboard');

        if(!self::checkSameTenant($location))
            return redirect()->route('locations.index')->with('error', 'Accesso non autorizzato!');

        return view('locations.show', [
            'location' => $location,
            'users' => User::query()->where('tenant_id', auth()->user()->tenant_id)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        if(!Controller::checkPermission('modifica_sedi'))
            return redirect()->route('dashboard');

        if(!self::checkSameTenant($location))
            return redirect()->route('locations.index')->with('error', 'Accesso non autorizzato!');;

        $location->update($request->all());
        self::customLog('Sede modificata');
        return redirect()->route('locations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
    }
}
