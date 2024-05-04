@section('title')
    Sedi
@endsection

<x-app-layout>

    <div class="flex flex-row justify-between mb-4">
        <div>
            <h1>Sedi</h1>
        </div>
        <div>
            <a href="{{route('locations.create')}}">Crea Sede</a>
        </div>
    </div>


    <table class="table table-auto min-w-full divide-y divide-gray-200">
        <thead>
        <tr>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Nome
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Indirizzo
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Num Telefono
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Email Sede
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                &nbsp;
            </th>

        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($locations as $location)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{$location->name}}
                        @if(auth()->user()->role_id === 1)
                            <br><small>Tenant: {{$location->tenant->name}}</small>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{$location->address}}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{$location->phone_number}}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{$location->email}}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="{{env('APP_URL')}}/locations/{{$location->id}}/edit">Dettaglio Sede</a>
                    </td>
                </tr>
            @endforeach

        <!-- More rows here -->
        </tbody>
    </table>
</x-app-layout>
