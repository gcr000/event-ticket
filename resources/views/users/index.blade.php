@section('title')
    Utenti
@endsection

<x-app-layout>

    <div class="flex flex-row justify-between mb-4">
        <div>
            <h1>Utenti</h1>
        </div>
        <div>
            <a href="{{route('users.create')}}">Crea Utente</a>
        </div>
    </div>


    <table class="table-auto min-w-full divide-y divide-gray-200">
        <thead>
        <tr>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Nome
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Email
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Ruolo
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Numero di telefono
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">

            </th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($users as $user)
            <tr>
                <td class="px-6 py-4 whitespace-no-wrap">
                    {{$user->name}}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap">
                    {{$user->email}}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap">
                    {{$user->role->name}}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap">
                    {{$user->phone_number}}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap text-end">
                    <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="{{route('users.show', $user->id)}}">Dettaglio Utente</a>
                </td>
            </tr>
        @endforeach
        <!-- More rows here -->
        </tbody>
    </table>

</x-app-layout>
