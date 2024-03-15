<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

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



}
