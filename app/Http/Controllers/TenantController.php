<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        //
    }

    public function get_tenant_info(Request $request) {

        $tenant = Tenant::find($request->tenant_id);

        if(auth()->user()->role_id != 1){
            if(auth()->user()->tenant_id != $tenant->id){
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Unauthorized'
                ], 401);
            }
        }

        $permissions = Permission::all();
        return view('settings.detail',[
            'tenant' => $tenant,
            'permissions' => $permissions
        ]);
    }

    public function update_tenant_data(Request $request){

        if(!Controller::checkPermission('modifica_impostazioni'))
            return redirect()->back();

        $tenant = Tenant::find($request->tenant_id);

        if(auth()->user()->role_id != 1){
            if(auth()->user()->tenant_id != $tenant->id){
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Unauthorized'
                ], 401);
            }
        }

        $tenant->email = $request->email;
        $tenant->phone = $request->phone;

        if(auth()->user()->role_id == 1)
            $tenant->paypal_client_id = $request->paypal_client_id;

        $tenant->save();

        self::customLog('Dati tenant aggiornati');

        return response()->json([
            'message' => 'Tenant data updated',
            'tenant' => $tenant
        ]);
    }
}
