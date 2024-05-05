<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Controller::checkPermission('lista_utenti'))
            return redirect()->route('dashboard');

        $users = User::query();

        if(auth()->user()->role_id != 1)
            $users = $users->where('tenant_id', auth()->user()->tenant_id);


        return view('users.index', [
            'users' => $users->orderBy('tenant_id')->orderBy('name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if(!Controller::checkPermission('crea_utenti'))
            return redirect()->route('dashboard');

        if($request->role_id != 1) {

            $user = new User();
            $user->name = $request->name_create;
            $user->email = $request->email_create;
            $user->phone_number = $request->phone_number_create;
            $user->role_id = $request->role_id_create;
            $user->password = Hash::make($request->password_create);
            $user->tenant_id = auth()->user()->tenant_id;
            $user->save();
        }

        self::customLog('Nuovo utente creato');
        return redirect()->route('users.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!Controller::checkPermission('modifica_utenti'))
            return redirect()->route('dashboard');

        $user = User::find($id);

        if($user->email !== $request->email && User::query()->where('email', $request->email)->exists())
            return response()->json(['message' => 'Email già in uso'], 400);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->role_id = $user->role_id === 1 ? 1 : $request->role_id;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;

        // modifico tutti i dati dell'utente negli eventi
        if($user->role_id !== 1) $user->events()->update([
            'ref_user_name' => $user->name,
            'ref_user_email' => $user->email,
            'ref_user_phone_number' => $user->phone_number
        ]);

        if($user->role_id !== 1) $user->save();

        self::customLog('Utente modificato');
        return response()->json($user);
    }


    public function disable_user(string $id)
    {
        if(!Controller::checkPermission('gestisci_utenti'))
            return redirect()->route('dashboard');

        $user = User::find($id);

        if($user->role_id != 1)
            $user->is_disabled = !$user->is_disabled;

        if($user->role_id !== 1)
            $user->save();

        self::customLog('Utente disabilitato');
        return response()->json($user);
    }

    public function update_permission(Request $request){

        /*if(!Controller::checkPermission('modifica_permessi'))
            return redirect()->route('dashboard');*/

        $user = User::find($request->user_id);
        $permission = Permission::find($request->permission_id);

        $permission_user = PermissionUser::query()
            ->where('user_id', $user->id)
            ->where('permission_id', $permission->id)
            ->first();


        if($permission_user){
            $permission_user->delete();
            $msg = 'Permesso rimosso';
        }
        else {
            $permission_user = new PermissionUser();
            $permission_user->user_id = $user->id;
            $permission_user->permission_id = $permission->id;
            $permission_user->tenant_id = $user->tenant_id;
            $permission_user->save();
            $msg = 'Permesso aggiunto';
        }

        self::customLog($msg);
        return response()->json($permission_user);
    }
}
