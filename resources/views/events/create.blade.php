@section('title')
    Crea Evento
@endsection

<x-app-layout>

    <div class="flex">
        <div class="card" style="width: 65%" id="first_card">
            <form action="{{route('events.store')}}" method="POST">
                @csrf
                <div class="flex flex-col justify-between border p-4 rounded-lg">
                    <div class="mt-4">
                        <x-custom.input required name="name" label="Nome Evento" placeholder="Nome Evento"></x-custom.input>
                    </div>
                    <div class="mt-4">
                        <x-custom.input required name="description" label="Descrizione Evento" placeholder="Descrizione Evento"></x-custom.input>
                    </div>
                    <div class="mt-4">
                        <div class="flex">
                            <div class="col w-1/3">
                                <label for="" class="block text-sm font-medium leading-6 text-gray-900">Numero di invitati prenotabili</label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <select onchange="partecipantType(this.value)" name="partecipation" class = "bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0">Aperto, senza limitazioni</option>
                                        <option value="1">Limitato, a numero chiuso</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col w-1/3 ms-4 ">
                                <label for="" class="block text-sm font-medium leading-6 text-gray-900">N. totale prenotabili</label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <input id="max_partecipanti" name="max_prenotabili" readonly value="" type="number" style="height: 46px" class="bg-gray-300 block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <div class="col w-1/3 ms-4 ">
                                <label for="" class="block text-sm font-medium leading-6 text-gray-900">N. biglietti prenotabili per persona</label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <input id="ticket_for_person" name="ticket_for_person" value="1" min="1" type="number" style="height: 46px" class="block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex">
                            <div class="col w-1/3">
                                <label for="" class="block text-sm font-medium leading-6 text-gray-900">Prenotazione a pagamento</label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <select onchange="setPagamentoPrice(this.value)" name="payment_request" class ="bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0" selected>No</option>
                                        <option value="1">Si, pagamento parziale</option>
                                        <option value="2">Si, pagamento completo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col w-1/3 ms-4 ">
                                <label for="" class="block text-sm font-medium leading-6 text-gray-900">Costo prenotazione</label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <input disabled id="payment_request_input" name="payment_request_input" value="" min="" step="0.01" type="number" style="height: 46px" class="bg-gray-300 block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <div class="col w-1/3 ms-4 ">
                                <label for="" class="block text-sm font-medium leading-6 text-gray-900" style="white-space: nowrap">Mostra referente in pagina prenotazione/email</label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <select name="show_referente" class ="bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="" class="block text-sm font-medium leading-6 text-gray-900">Scegli il referente per l'evento</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <select required name="referente_id" class="bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Scegli il referente</option>
                                @foreach(\App\Models\User::query()->where('tenant_id', \Illuminate\Support\Facades\Auth::user()->tenant_id)->get() as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    {{-- EVENTO SCELTA SEDE --}}
                    <div class="mt-4">
                        <label for="" class="block text-sm font-medium leading-6 text-gray-900">Sede</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <select required id="location_id" onchange="getSedeInfoLastEvents(this.value)" name="location_id" class = "bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Scegli la sede</option>
                                @foreach($locations as $location)
                                    <option value="{{$location->id}}">{{$location->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<x-custom.select id="location_select" :data_array="$locations" required name="location_id" label="Scegli la sede"></x-custom.select>--}}
                    </div>



                    {{-- EVENTO PERIODO DATA  --}}
                    <div class="mt-6 space-y-6" id="radio_buttons" style="display: none">
                        <div class="flex items-center gap-x-3">
                            <input required value="1" id="check_singolo" onclick="toggleShow('check_singolo')" name="singolo_giorno" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="check_singolo" class="block text-sm font-medium leading-6 text-gray-900">Singolo Giorno</label>
                        </div>
                        <div class="flex items-center gap-x-3">
                            <input required value="0" id="check_periodo" onclick="toggleShow('check_periodo')" name="singolo_giorno" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="check_periodo" class="block text-sm font-medium leading-6 text-gray-900">Periodo</label>
                        </div>
                    </div>

                    {{-- EVENTO PERIODO DATA  --}}
                    <div id="periods"></div>

                    <div class="mt-2 flex justify-end">
                        <label for="" class=""></label>
                        <x-secondary-button type="submit" class="mt-4 block ">Crea</x-secondary-button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card ms-3 border p-4 rounded-lg" style="width: 35%" id="second_card">

                <div class="mt-4">
                    <p class="text-center text-lg">Ultimi eventi per la sede selezionata</p>
                    <div id="last_events" class="mt-4">
                        <p class="text-center">
                            <i>
                                Nessuna sede selezionata
                            </i>
                        </p>
                    </div>
                </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

        });

        function setPagamentoPrice(value) {

            let payment_request_input = document.getElementById('payment_request_input');
            payment_request_input.value = '';
            if (value === '1' || value === '2') {
                payment_request_input.disabled = false;
                payment_request_input.classList.remove('bg-gray-300');
                payment_request_input.setAttribute('required', true);
            } else {
                payment_request_input.disabled = true;
                payment_request_input.classList.add('bg-gray-300');
                payment_request_input.removeAttribute('required');
            }
        }

        let html_singolo = `
            <div class="mt-4">
                <div class="flex flex-row gap-4">
                    <div class="" style="width: 70%">
                        <div>
                            <label for="" class="block text-sm font-medium leading-6 text-gray-900">Data</label>
                            <div class="relative mt-2 rounded-md shadow-sm">
                                <input onchange="checkData(this.value, document.getElementById('location_id').value)" type="date" name="datetime_from" value="" class = "block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>
                    <div class="" style="width: 30%">
                        <x-custom.input required name="time_from" type="time" class="mx-auto w-full" label="Ora" placeholder=""></x-custom.input>
                    </div>
                </div>
            </div>
        `;

        let html_periodo = `
            <div class="mt-4">
                <div class="flex flex-row gap-4">
                    <div class="" style="width: 70%">
                        <div>
                            <label for="" class="block text-sm font-medium leading-6 text-gray-900">Data Inizio</label>
                            <div class="relative mt-2 rounded-md shadow-sm">
                                <input onchange="checkData(this.value, document.getElementById('location_id').value)" type="date" name="datetime_from" value="" class = "block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder=""
                                >
                            </div>
                        </div>
                    </div>
                    <div class="" style="width: 30%">
                        <x-custom.input required name="time_from" type="time" class="mx-auto w-full" label="Ora Inizio" placeholder=""></x-custom.input>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="flex flex-row gap-4">
                    <div class="" style="width: 70%">
                        <div>
                            <label for="" class="block text-sm font-medium leading-6 text-gray-900">Data Fine</label>
                            <div class="relative mt-2 rounded-md shadow-sm">
                                <input onchange="checkData(this.value, document.getElementById('location_id').value)" type="date" name="datetime_to" value="" class = "block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder=""
                                >
                            </div>
                        </div>
                    </div>
                    <div class="" style="width: 30%">
                        <x-custom.input required name="time_to" type="time" class="mx-auto w-full" label="Ora Fine" placeholder=""></x-custom.input>
                    </div>
                </div>
            </div>
        `;

        function getToken() {
            let metas = document.getElementsByTagName("meta");
            for (let i = 0; i < metas.length; i++) {
                let meta = metas[i];
                if (meta.name === "csrf-token") {
                    return meta.content;
                }
            }
        }

        async function checkData(data, location_id){

            let url = "{{env('APP_URL')}}/check_event_data/";
            let token = getToken();

            let check = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    data: data,
                    location_id: location_id
                })
            })

            let response = await check.json();
            let event_id = response.event_id;

            if(event_id != null){
                customAlert('Data già occupata', 'Fai attenzione: il '+moment(data).format('DD/MM/YYYY')+' la location selezionata è già occupata da un altro evento. Potrai comunque creare l\'evento', 'error');
            }
        }

        function toggleShow(id) {
            let periods = document.getElementById('periods');
            periods.innerHTML = '';
            if (id === 'check_singolo') periods.innerHTML = html_singolo;
            else if (id === 'check_periodo') periods.innerHTML = html_periodo;
        }

        function partecipantType(value) {
            let max_partecipanti = document.getElementById('max_partecipanti');
            max_partecipanti.value = '';
            if (value === '1') {
                max_partecipanti.classList.remove('bg-gray-300');
                max_partecipanti.readOnly = false;
            } else {
                max_partecipanti.classList.add('bg-gray-300');
                max_partecipanti.readOnly = true;
            }
        }

        function getSedeInfoLastEvents(location_id){

            let last_events = document.getElementById('last_events');

            // add spinner
            last_events.innerHTML = '';
            let spinner = document.createElement('div');
            spinner.classList.add('flex', 'justify-center', 'mt-4');
            spinner.innerHTML = `
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status">
                    <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                </div>
            `
            last_events.appendChild(spinner);


            let radio_buttons = document.getElementById('radio_buttons');
            if(location_id === ''){
                last_events.innerHTML = '';
                let p = document.createElement('p');
                p.classList.add('text-center');
                p.innerHTML = '<i>Nessuna sede selezionata</i>';
                last_events.appendChild(p);
                radio_buttons.style.display = 'none';
                return;
            } else {
                radio_buttons.style.display = '';
            }

            let url = "{{env('APP_URL')}}/last_location_events/" + location_id;
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    last_events.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {

                            let div = document.createElement('div');
                            div.classList.add('flex', 'justify-between');

                            let p1 = document.createElement('p');
                            p1.classList.add('text-sm');
                            p1.innerHTML = item.name;

                            let p2 = document.createElement('p');
                            p2.classList.add('text-sm');
                            if(item.is_single_day === 1)
                                p2.innerHTML = moment(item.datetime_from).format('DD/MM/YYYY HH:mm');
                            else
                                p2.innerHTML = moment(item.datetime_from).format('DD/MM/YYYY HH:mm') + ' - ' + moment(item.datetime_to).format('DD/MM/YYYY HH:mm');

                            div.appendChild(p1);
                            div.appendChild(p2);

                            last_events.appendChild(div);

                            let hr = document.createElement('hr');
                            hr.classList.add('my-2');
                            last_events.appendChild(hr);
                        });
                    } else {
                        let p = document.createElement('p');
                        p.classList.add('text-center');
                        p.innerHTML = '<i>Nessun evento in questa sede</i>';
                        last_events.appendChild(p);
                    }
                });
        }

    </script>



    {{--<div class="flex flex-row justify-start border g-2">
        <x-custom.input label="Nome Evento" placeholder="Nome Evento"></x-custom.input>
        <x-custom.input label="Descrizione Evento" placeholder="Descrizione Evento"></x-custom.input>
            --}}{{--<x-custom.daterange label="Periodo" startDate="{{date('Y-m-d')}}" endDate="{{date('Y-m-d')}}"></x-custom.daterange>
            <x-custom.input label="Nome Evento" placeholder="Nome Evento"></x-custom.input>
            <x-custom.input label="Descrizione Evento" placeholder="Descrizione Evento"></x-custom.input>
            <a href="{{route('events.index')}}">Torna alla lista</a>--}}{{--
    </div>--}}


</x-app-layout>
