<?php

namespace App\Http\Controllers;

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
        $users = User::query();

        if(auth()->user()->role_id != 1)
            $users = $users->where('tenant_id', auth()->user()->tenant_id);


        return view('users.index', [
            'users' => $users->get()
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
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function disable_user(string $id)
    {
        $user = User::find($id);
        if($user->role_id != 1)
        $user->is_disabled = !$user->is_disabled;
        if($user->role_id !== 1) $user->save();
        return response()->json($user);
    }
}
