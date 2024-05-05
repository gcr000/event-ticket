<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Tenant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    static function checkPermission($permission_name) {

        if(auth()->user()->role_id == 1)
            return true;

        $permission = Permission::query()
            ->where('name', $permission_name)
            ->first();

        if(!$permission)
            return false;

        $permission_user = PermissionUser::query()
            ->where('user_id', auth()->user()->id)
            ->where('permission_id', $permission->id)
            ->first();

        if($permission_user)
            return true;

        return false;
    }

    static function checkSameTenant($model) {
        if($model->tenant_id != auth()->user()->tenant_id)
            return false;

        return true;

    }

    // funzione statica per criptare l'id dell'evento
    static function encryptId($id) {
        return Crypt::encryptString($id);
    }

    // funzione statica per decriptare l'id dell'evento
    static function decryptId($id) {
        return Crypt::decryptString($id);
    }

    public function index_settings() {

        if(auth()->user()->role_id != 1 && auth()->user()->role_id != 2)
            return redirect()->route('dashboard');

        if(auth()->user()->role_id != 1)
            $tenances = Tenant::where('id', auth()->user()->tenant_id)->get();
        else
            $tenances = Tenant::all();

        $permissions = Permission::all();

        return view('settings.index',[
            'tenances' => $tenances,
            'permissions' => $permissions
        ]);
    }

    static function customLog($msg = null){

        $controller = str_replace("App\Http\Controllers\\", "", debug_backtrace()[1]['class']);
        $method = debug_backtrace()[1]['function'];
        $requestAll = request()->all();
        $url = request()->url();

        if(!$msg)
            $msg = "** Controller: $controller, ** Method: $method, ** Request: ".json_encode($requestAll).", ** URL: $url **";
        else
            $msg = "** Controller: $controller, ** Method: $method, ** Request: ".json_encode($requestAll).", ** URL: $url, ** Message: $msg, **";

        // se utente loggato, aggiungo anche l'id dell'utente
        if(auth()->user())
            $msg .= " User ID: ".auth()->user()->id . " **";

        Log::channel('custom')->info($msg);
    }
}
