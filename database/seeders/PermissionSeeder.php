<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions_array = [
            'lista_utenti' =>'utente che può vedere la lista utenti',
            'crea_utenti' =>'utente che può creare un nuovo utente',
            'modifica_utenti' =>'utente che può modifica un utente',
            'gestisci_utenti' =>'utente che può abilitare/disabilitare un utente',

            'lista_sedi' =>'utente che può vedere la lista delle sedi',
            'crea_sedi' =>'utente che può creare una sede',
            'dettaglio_sedi' =>'utente che può vedere il dettaglio di una sede',
            'modifica_sedi' =>'utente che può modificare una sede',

            'lista_eventi' =>'utente che può vedere la lista degli eventi',
            'crea_eventi' =>'utente che può creare un nuovo evento',
            'dettaglio_eventi' =>'utente che può vedere il dettaglio di un evento',
            'archivia_eventi' =>'utente che può archiviare gli eventi'
        ];


        foreach ($permissions_array as $name => $description) {
            DB::table('permissions')->insert([
                'name' => $name,
                'description' => $description,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
