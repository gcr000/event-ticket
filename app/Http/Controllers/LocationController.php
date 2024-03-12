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
        return view('locations.index', [
            'locations' => Location::query()->where('tenant_id', auth()->user()->tenant_id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locations.create', [
            'users' => User::query()->where('tenant_id', auth()->user()->tenant_id)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $loc = Location::create($request->all());

        $ref = User::find($request->ref_user_id);
        $loc->ref_user_name = $ref->name;
        $loc->ref_user_email = $ref->email;
        $loc->ref_user_phone_number = $ref->phone_number;
        $loc->tenant_id = auth()->user()->tenant_id;
        $loc->save();

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
        if(!self::checkSameTenant($location))
            return redirect()->route('locations.index')->with('error', 'Accesso non autorizzato!');;

        $location->update($request->all());

        $ref = User::find($request->ref_user_id);
        $location->ref_user_name = $ref->name;
        $location->ref_user_email = $ref->email;
        $location->ref_user_phone_number = $ref->phone_number;
        $location->save();

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
