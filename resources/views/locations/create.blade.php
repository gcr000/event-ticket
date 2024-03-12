@section('title')
    Crea Sede
@endsection

<x-app-layout>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <form action="{{route('locations.store')}}" method="POST">
        @csrf

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <div class="flex flex-row gap-4">
            <div class="card" style="width: 50%">
                <div class="flex flex-1 flex-col justify-between">
                    <div class="mt-2">
                        <x-custom.input required name="name" label="Nome Sede" placeholder="Nome Sede"></x-custom.input>
                    </div>
                    <div class="mt-2">
                        <div class="flex">
                            <div class="col w-2/5">
                                <x-custom.input required name="city" label="Città" placeholder="Città"></x-custom.input>
                            </div>
                            <div class="col w-2/5 ms-4 ">
                                <x-custom.input required name="address" label="Indirizzo" placeholder="Indirizzo"></x-custom.input>
                            </div>
                            <div class="col w-1/5 ms-4">
                                <div class="mt-2" style="margin-top: 32px">
                                    <button type="button" class="border hover:bg-blue-700 hover:text-gray-400 py-2 px-4 rounded" onclick="searchMarker()">Trova su Mappa</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex gap-2">
                            <div class="" style="width: 100%">
                                <x-custom.input required name="phone_number" label="Telefono" placeholder="Telefono"></x-custom.input>
                            </div>
                            <div class="" style="width: 100%">
                                <x-custom.input required type="email" name="email" label="Email" placeholder="Email"></x-custom.input>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <x-custom.select required name="ref_user_id" label="Referente" :data_array="$users"></x-custom.select>
                    </div>
                </div>
            </div>

            <div class="card" style="width: 50%;">
                <div id="map" class="border flex items-center justify-center" style="border-radius: 15px">
                    <img src="{{ asset('map.svg') }}" alt="Mappa" style="max-width: 100%; max-height: 100%;">
                </div>
                <p style="text-align: center; display: none" id="paragh_map">
                    <i>
                        Clicca sulla mappa per inserire o spostare il marker e selezionare la posizione corretta
                    </i>
                </p>
                <div class="mt-2 flex justify-end" style="display: none" id="crea_btn">
                    <label for="" class=""></label>
                    <x-secondary-button type="submit" class="mt-4">Crea</x-secondary-button>
                </div>
            </div>
        </div>
    </form>

    <style>
        #map { height: 380px; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded',function(){
            //
        })

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

                            document.querySelector('#latitude').value = lat;
                            document.querySelector('#longitude').value = lon;

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
                            customAlert('Errore', 'Nessun risultato trovato. Cerca di essere più specifico e dettagliato con l\'indirizzo', 'error')
                        }
                    });
            } else {
                customAlert('Attenzione','Inserisci città e indirizzo', 'info')
            }
        }
    </script>
</x-app-layout>
