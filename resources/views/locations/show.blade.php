@section('title')
    Dettaglio Sede
@endsection

<x-app-layout>

    <div class="flex flex-row justify-between mb-4">
        <div>
            <h1 class="text-xl">Dati</h1>
        </div>
        <div>
            <a href="{{route('locations.index')}}">Elenco Sedi</a>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <form action="{{route('locations.update', $location->id)}}" method="POST">
        @csrf

        <input type="hidden" name="latitude" value="{{$location->latitude}}">
        <input type="hidden" name="longitude" value="{{$location->longitude}}">
        <div class="flex flex-row gap-4">
            <div class="card" style="width: 50%">
                <div class="flex flex-1 flex-col justify-between">
                    <div class="mt-2">
                        <x-custom.input required name="name" label="Nome Sede" placeholder="Nome Sede" value="{{$location->name}}"></x-custom.input>
                    </div>
                    <div class="mt-2">
                        <div class="flex">
                            <div class="col w-2/5">
                                <x-custom.input required name="city" label="Città" placeholder="Città" value="{{$location->city}}"></x-custom.input>
                            </div>
                            <div class="col w-2/5 ms-4 ">
                                <x-custom.input required name="address" label="Indirizzo" placeholder="Indirizzo" value="{{$location->address}}"></x-custom.input>
                            </div>
                            <div class="col w-1/5 ms-4">
                                <div class="mt-2" style="margin-top: 32px">
                                    <button type="button" class="border hover:bg-blue-700 hover:text-gray-400 py-2 px-4 rounded" onclick="searchMarker()">Trova su Mappa</button>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <div style="width: 80%">
                                <div class="">

                                </div>
                                <div class="">

                                </div>
                            </div>
                            <div style="width: 20%">

                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex gap-2">
                            <div class="" style="width: 100%">
                                <x-custom.input required name="phone_number" label="Telefono" placeholder="Telefono" value="{{$location->phone_number}}"></x-custom.input>
                            </div>
                            <div class="" style="width: 100%">
                                <x-custom.input required type="email" name="email" label="Email" placeholder="Email" value="{{$location->email}}"></x-custom.input>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="" class="block text-sm font-medium leading-6 text-gray-900">Referente</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <select name="ref_user_id" class="bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}"
                                        @if($user->id == $location->ref_user_id)
                                            selected
                                        @endif
                                    >{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="width: 50%;">
                <div id="map" class="border flex items-center justify-center" style="border-radius: 15px">
                    {{-- spinner --}}
                    <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status">
                        <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                    </div>
                </div>
                <p style="text-align: center; display: none" id="paragh_map">
                    <i>
                        Clicca sulla mappa per inserire o spostare il marker e selezionare la posizione corretta
                    </i>
                </p>

                <div class="mt-2 flex justify-end" id="crea_btn">
                    <label for="" class=""></label>
                    <x-secondary-button type="submit" class="mt-4">Aggiorna</x-secondary-button>
                </div>
            </div>
        </div>

    </form>

    <div class="mb-4">
        <h1 class="text-xl mb-3">Eventi</h1>
        @foreach($location->events as $event)

            <div class="flex flex-row border-b-2">
                <div class="basis-1/6">{{date('d/m/Y', strtotime($event->datetime_from))}} {{date('H:i', strtotime($event->datetime_from))}}</div>
                <div class="basis-1/6">{{$event->name}}</div>
                <div class="basis-2/6">{{$event->description}}</div>
                <div class="basis-1/6 text-center">{{$event->user->name}}</div>
                <div class="basis-1/6 text-end">
                    <a class="hover:text-blue-800" href="{{route('events.show', $event->id)}}">Dettaglio Evento</a>
                </div>
            </div>
        @endforeach

        @if(count($location->events) == 0)
            <i>Non ci sono eventi associati a questa sede</i>
        @endif
        {{--<div class="gap-4">
            @foreach($location->events as $event)
                <div class="row">
                    <div class="col-4">
                        {{$event->name}}
                    </div>
                    <div class="col-4">
                        {{$event->datetime_from}}
                    </div>
                </div>
            @endforeach
        </div>--}}

    </div>

    <style>
        #map { height: 380px; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded',function(){
            setTimeout(() => {
                setExistingMarker()
            }, 500);
        })

        function setExistingMarker(){
            let latitudine = document.querySelector('input[name="latitude"]').value
            let longitudine = document.querySelector('input[name="longitude"]').value

            if(latitudine && longitudine){
                let paragh_map = document.getElementById('paragh_map');
                paragh_map.style.display = 'block';
                let create_btn = document.getElementById('crea_btn');
                create_btn.style.display = '';
                map = L.map('map').setView([latitudine, longitudine], 17);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                marker = L.marker([latitudine, longitudine]).addTo(map);
                marker.dragging.enable();
                marker.on('dragend', function (e) {
                    let latlng = marker.getLatLng();
                    document.querySelector('input[name="latitude"]').value = latlng.lat;
                    document.querySelector('input[name="longitude"]').value = latlng.lng;
                });
                map.on('click', function (e) {
                    if (marker) {
                        map.removeLayer(marker); // Rimuovi il marker precedente
                    }
                    marker = L.marker(e.latlng).addTo(map); // Aggiungi il nuovo marker
                    marker.dragging.enable();
                    marker.on('dragend', function (e) {
                        let latlng = marker.getLatLng();
                        document.querySelector('input[name="latitude"]').value = latlng.lat;
                        document.querySelector('input[name="longitude"]').value = latlng.lng;
                    });
                    document.querySelector('input[name="latitude"]').value = e.latlng.lat;
                    document.querySelector('input[name="longitude"]').value = e.latlng.lng;
                });
            }
        }


        let map, marker;

        function searchMarker(){

            let city = document.querySelector('input[name="city"]').value
            let address = document.querySelector('input[name="address"]').value

            if(city && address){
                fetch('https://nominatim.openstreetmap.org/search?format=json&city='+city+'&street='+address)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let lat = data[0].lat;
                            let lon = data[0].lon;
                            let paragh_map = document.getElementById('paragh_map');
                            paragh_map.style.display = 'block';
                            let create_btn = document.getElementById('crea_btn');
                            create_btn.style.display = '';
                            if (!map) {
                                map = L.map('map').setView([lat, lon], 17);
                                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                }).addTo(map);
                            } else {
                                map.setView([lat, lon], 15);
                            }

                            if (marker) {
                                map.removeLayer(marker); // Rimuovi il marker precedente
                            } else {
                                marker = L.marker([lat, lon]).addTo(map); // Aggiungi il nuovo marker
                                marker.dragging.enable();
                            }

                            marker.on('dragend', function (e) {
                                let latlng = marker.getLatLng();
                                document.querySelector('input[name="latitude"]').value = latlng.lat;
                                document.querySelector('input[name="longitude"]').value = latlng.lng;
                            });

                            map.on('click', function (e) {
                                if (marker) {
                                    map.removeLayer(marker); // Rimuovi il marker precedente
                                }
                                marker = L.marker(e.latlng).addTo(map); // Aggiungi il nuovo marker
                                marker.dragging.enable();
                                marker.on('dragend', function (e) {
                                    let latlng = marker.getLatLng();
                                    document.querySelector('input[name="latitude"]').value = latlng.lat;
                                    document.querySelector('input[name="longitude"]').value = latlng.lng;
                                });
                                document.querySelector('input[name="latitude"]').value = e.latlng.lat;
                                document.querySelector('input[name="longitude"]').value = e.latlng.lng;
                            });
                        } else {
                            customAlert('Errore','Nessun risultato trovato. Cerca di essere più specifico e dettagliato con l\'indirizzo', 'error')
                        }
                    });
            } else {
                customAlert('Attenzione','Inserisci città e indirizzo', 'info')
            }
        }
    </script>
</x-app-layout>
