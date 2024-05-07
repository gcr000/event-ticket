@section('title')
    Home
@endsection

<x-app-layout>

    @php($tenant = \App\Models\Tenant::query()->where('id', auth()->user()->tenant_id)->first())

    <div>
        <div class="px-4 sm:px-0">
            <h3 class="text-base font-semibold leading-7 text-gray-900">Informazioni Cliente</h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Pannello di amministrazione </p>
        </div>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900 sm:w-1/4">Nome</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:w-3/4 sm:mt-0">{{$tenant->name}}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Dati azienda</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{$tenant->email ?? '-'}} | {{$tenant->phone ?? '-'}}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Utenti</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @foreach($tenant->users as $user)
                            <b>{{$user->name}}</b> <small>({{$user->role->name}})</small> @if(!$loop->last), @endif
                        @endforeach
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Sedi</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if($tenant->locations->count() == 0)
                            Nessuna sede presente
                        @else
                            @foreach($tenant->locations as $location)
                                <b>{{$location->name}}</b> <small>({{$location->address}})</small> @if(!$loop->last), @endif
                            @endforeach
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Eventi</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if($tenant->events->count() == 0)
                            Nessun evento presente
                        @else
                            @foreach($tenant->events as $event)
                                <i>{{$event->name}}</i> @if(!$loop->last), @endif
                            @endforeach
                        @endif
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Ultime prenotazioni</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @if($tenant->last_bookings->count() == 0)
                            Nessuna prenotazione effettuata
                        @else
                            <div class="grid grid-cols-3 gap-4">
                                <div class="font-semibold">Email</div>
                                <div class="font-semibold">Evento | Data</div>
                                <div class="font-semibold">Prenotazione</div>
                            </div>
                            @foreach($tenant->last_bookings as $booking)
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="">{{$booking->email}}</div>
                                    <div class="">{{$booking->event->name}} | <small>{{date('d/m/Y H:i',strtotime($booking->event->datetime_from))}}</small></div>
                                    <div class="">{{date('d/m/Y H:i', strtotime($booking->created_at))}}</div>
                                </div>
                            @endforeach
                        @endif
                    </dd>
                </div>

            </dl>
        </div>
    </div>
</x-app-layout>
