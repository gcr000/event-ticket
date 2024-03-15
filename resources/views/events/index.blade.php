@section('title')
    Eventi
@endsection

<x-app-layout>

    <div class="flex flex-row justify-between mb-4">
        <div>
            <h1>Eventi</h1>
        </div>
        <div>
            <a href="{{route('events.create')}}">Crea Evento</a>
        </div>
    </div>

    <div class="flex flex-row justify-end mb-4">
        <div>
            <input style="width: 300px" class="block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" placeholder="Cerca...">
        </div>
        <div class="ms-3">
            <select style="height: 40px; width: 300px" name="" id="" class = "bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Tutti</option>
                @foreach(\App\Models\Location::query()->where('tenant_id', auth()->user()->tenant_id)->get() as $location)
                    <option value="">{{$location->name}}</option>
                @endforeach
            </select>
        </div>
    </div>


    <table class="table-auto min-w-full divide-y divide-gray-200">
        <thead>
        <tr>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Nome Evento
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Descrizione
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Data e Sede
            </th>
            {{--<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                Tipologia Presenza
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">
                Prenotati/Partecipanti
            </th>--}}
            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">

            </th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($events as $event)
                <tr id="event_{{$event->id}}">
                    <td class="px-6 py-4 whitespace-no-wrap">
                        <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="{{route('events.show', $event->id)}}">
                            {{$event->name}}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{$event->description}}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{date('d/m/Y H:i', strtotime($event->datetime_from))}} @if($event->datetime_to != $event->datetime_from) - {{date('d/m/Y H:i', strtotime($event->datetime_to))}} @endif
                        <br>
                        {{$event->location->name}}
                        @if($event->is_payment_required != 'no')
                            <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                €
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-center">
                        <div>
                          <span class="group relative">
                            <div class="absolute bottom-[calc(100%+0.5rem)] left-[50%] -translate-x-[50%] hidden group-hover:block w-auto">
                              <div class="bottom-full right-0 rounded bg-white border px-4 py-1 whitespace-nowrap">
                                  <div class="row">
                                      <div class="col-12">
                                          @if($event->max_prenotabili == '10000000')
                                              Prenotazione aperta, senza limiti di partecipanti
                                          @else
                                              Limitato a {{$event->max_prenotabili}} partecipanti
                                          @endif
                                      </div>
                                      <div class="col-12">
                                            {{$event->prenotati}} prenotati / {{$event->partecipanti}} partecipanti
                                      </div>
                                      <div class="col-12">
                                          Massimo
                                          @if($event->ticket_for_person == 1)
                                              un biglietto prenotabile
                                          @else
                                              {{$event->ticket_for_person}} biglietti prenotabili
                                          @endif
                                           per persona
                                      </div>
                                      <div class="col-12">
                                          @if($event->show_referente)
                                                Referente: {{$event->ref_user_name}}
                                            @else
                                                Referente: non visibile ai partecipanti
                                            @endif
                                      </div>
                                      <div class="col-12">
                                          @if($event->is_payment_required == 'no')
                                              Prenotazione gratuita
                                            @elseif($event->is_payment_required == 'si, pagamento parziale')
                                                Prenotazione con pagamento parziale <b>({{$event->price}}€)</b>
                                            @else
                                                Prenotazione a pagamento <b>({{$event->price}}€)</b>
                                            @endif
                                      </div>
                                  </div>
                                <svg class="absolute left-0 top-full h-2 w-full text-black" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                              </div>
                            </div>
                            <span onmouseover="colorRow('event_'+{{$event->id}})" style="cursor: pointer"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                            </span>
                          </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @php($base64Id = \App\Http\Controllers\Controller::encryptId($event->id))
                        <a href="{{env('APP_URL') . '/bookings/' . $base64Id}}">Link prenotazione</a> <br>
                        <span style="cursor: pointer; color: orange" onclick="manageEvent('archiviare', {{$event->id}})" id="archivia_{{$event->id}}">Archivia</span> <br>
                        <span style="cursor: pointer; color: red" onclick="manageEvent('eliminare', {{$event->id}})" id="elimina_{{$event->id}}">Elimina</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function colorRow(rowId){
            // prendo tutte le righe della tabella e cambio il colore di sfondo
            var rows = document.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                rows[i].style.backgroundColor = 'white';
            }
            // cambio il colore della riga selezionata
            document.getElementById(rowId).style.backgroundColor = '#f3f4f6';
        }

        function manageEvent(type, eventId){
            if(!confirm('Sei sicuro di voler ' + type + ' l\'evento?')) return;
            var url = '{{env('APP_URL')}}/events/' + eventId + '/' + type;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
        }
    </script>

</x-app-layout>
