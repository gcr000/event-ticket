@section('title')
    Eventi
@endsection


<x-app-layout>

    <style>
        .first_div {
            width: 100%;
            border: 1px solid lightgray;
            border-radius: 20px;
            margin: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="flex justify-between mb-4">
        <div class="ms-2">
            <h1>Dettaglio Evento</h1>
        </div>
        <div>
            <a href="{{route('events.index')}}">Lista Eventi</a>
        </div>
    </div>

    <div class="flex justify-around mb-4">

        <div class="first_div">
            <h1 class="text-4xl font-black tracking-widest">{{$event->name}}</h1> <br>
            <p class="text-2xl p-4 text-center" style="margin-top: -30px">{{$event->description}}</p>

            <br>

            <div style="text-align: center">
                <h4 class="italic">
                    @if($event->datetime_to != $event->datetime_from)
                        Dalle {{date('H:i', strtotime($event->datetime_from))}} del {{date('d/m/Y', strtotime($event->datetime_from))}}  fino alle {{date('H:i', strtotime($event->datetime_to))}} del <b style="font-size: 1.3em">{{date('d/m/Y', strtotime($event->datetime_to))}}</b>
                    @else
                        Alle {{date('H:i', strtotime($event->datetime_to))}} del <b style="font-size: 1.3em">{{date('d/m/Y', strtotime($event->datetime_to))}}</b>
                    @endif
                </h4>
                <h4 class="italic">{{$event->location->name}} - {{$event->location->address}}</h4> <br> <br>
                <h4 class="italic">
                    @if($event->max_prenotabili == '10000000')
                        Prenotazione aperta, senza limiti di partecipanti
                    @else
                        Limitato a {{$event->max_prenotabili}} partecipanti
                    @endif
                </h4>
                <h4 class="italic">
                    Massimo
                        @if($event->ticket_for_person == 1)
                            un biglietto prenotabile
                        @else
                            {{$event->ticket_for_person}} biglietti prenotabili
                        @endif
                    per persona
                </h4>
                <h4 class="italic">
                    @if($event->show_referente)
                        Referente: <b>{{$event->ref_user_name}}</b>
                    @else
                        Referente: non visibile ai partecipanti
                    @endif
                </h4>
                <h4 class="italic">
                    @if($event->is_payment_required == 'no')
                        Prenotazione gratuita
                    @elseif($event->is_payment_required == 'si, pagamento parziale')
                        Prenotazione con pagamento parziale <b>({{$event->price}}€)</b>
                    @else
                        Prenotazione a pagamento <b>({{$event->price}}€)</b>
                    @endif
                </h4>

            </div>

        </div>
        <div class="first_div">
            <h1></h1> <br>
            <a href="{{$urlToEvent}}" target="_blank">
                {!! QrCode::style('round')->size(250)->generate($urlToEvent); !!}
            </a>

            <div class="text-center mt-4">
                <br>
                <a style="color: #0d6efd" href="{{$urlToEvent}}" target="_blank">Pagina Prenotazione Clienti</a>
                <br>
                <br>
                <div class="flex flex-row justify-center gap-4">
                    <div>
                        <button id="copyButton" style="background-color: #0d6efd; color: white; padding: 10px; border-radius: 10px">Copia link</button>
                    </div>
                    <div>
                        <button id="downloadSvgButton" style="background-color: #0d6efd; color: white; padding: 10px; border-radius: 10px">Scarica QrCode</button>
                    </div>
                </div>

                <br>
                <span class="hidden" id="testoDaCopiare">{{$urlToEvent}}</span>
            </div>
        </div>
    </div>

    <div class="flex justify-between mb-4">
        <div class="ms-2">
            <h1>Partecipanti</h1>
        </div>
    </div>

    <div class="flex justify-around mb-4">

        <div class="first_div">
            <table class="items-center bg-transparent w-full border-collapse" style="width: 100%!important;"
                   width="100">
                <thead>
                <tr>
                    <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle  border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                        Nome
                    </th>
                    <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle  border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                        Email
                    </th>
                    <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle  border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                        Phone Number
                    </th>
                    <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle  border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                        Data Prenotazione
                    </th>
                    <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle  border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                        Ingresso
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\BookingDetail::query()->where('event_id', $event->id)->orderBy('created_at')->get() as $detail)
                    @if($detail->booking->is_confirmed)
                        <tr class="border-t">
                            <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 text-left text-blueGray-700">
                                #{{$detail->booking_id}} {{$detail->name}}
                            </th>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4"> {{$detail->email}} </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4"> {{$detail->phone_number}} </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4"> {{date('d/m/Y H:i', strtotime($detail->booking->confirmed_at))}} </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 text-center"> {{$detail->booking->arrived_at ? date('d/m/Y H:i', strtotime($detail->booking->arrived_at)) : ' - '}} </td>
                        </tr>
                    @endif
                @endforeach
                @if(count(\App\Models\Booking::query()->where('is_confirmed', 1)->where('event_id', $event->id)->orderBy('created_at')->get()) == 0)
                    <tr class="border-t">
                        <td class="border-t-0 text-center px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 italic text-blueGray-700" colspan="5"> Nessun partecipante </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

    </div>


    <script>
        document.getElementById("copyButton").addEventListener("click", function () {
            // Selezione del testo da copiare
            var testoDaCopiare = document.getElementById("testoDaCopiare");
            var testo = testoDaCopiare.textContent;

            // Creazione di un elemento textarea temporaneo per copiare il testo
            var textareaTemp = document.createElement("textarea");
            textareaTemp.value = testo;
            document.body.appendChild(textareaTemp);

            // Selezione e copia del testo negli appunti
            textareaTemp.select();
            document.execCommand("copy");

            // Rimozione dell'elemento textarea temporaneo
            document.body.removeChild(textareaTemp);

            // Feedback per l'utente
            this.innerText = "Copiato!";
        });

        document.getElementById("downloadSvgButton").addEventListener("click", function () {
            // Seleziona l'elemento SVG
            // di tutti gli svg, seleziona l'ultimo

            var svgElement = document.querySelectorAll('svg')[document.querySelectorAll('svg').length - 1];

            // Converti l'elemento SVG in XML
            var svgXml = new XMLSerializer().serializeToString(svgElement);

            // Crea un URL dati (data URL) dall'XML SVG
            var svgBlob = new Blob([svgXml], {type: "image/svg+xml"});
            var svgUrl = URL.createObjectURL(svgBlob);

            // Crea un link di download
            var downloadLink = document.createElement("a");
            downloadLink.href = svgUrl;
            // nome del file da scaricare preceduto da timestamp

            downloadLink.download = "qrcode_" + "{{str_replace(' ', '_', $event->name)}}" + ".svg";

            // Aggiungi il link al documento e avvia il download
            document.body.appendChild(downloadLink);
            downloadLink.click();

            // Rimuovi il link dal documento
            document.body.removeChild(downloadLink);
        });

    </script>

</x-app-layout>
