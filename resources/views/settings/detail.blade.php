
<h3 style="font-weight: 900;">Elenco Permessi</h3>
<p class="mt-1 text-sm leading-6 text-gray-600" style="margin-bottom: 20px">Lista dei permessi relativi ai singoli utenti</p>

<div class="card border mb-3 p-2" style="border-radius: 5px">
    {{--<div class="card-header mb-5">
        --}}{{--<h3>Tenant: <b>{{$tenant->name}}</b></h3>--}}{{--
    </div>--}}
    <div class="card-body">
        <div class="w-full overflow-x-auto">
            <div class="flex flex-row mb1">
                <div style="width: 300px">&nbsp;</div>
                @foreach($tenant->users as $user)
                    <div class="me-4" style="width: 150px; text-align: center">
                        <p style="margin-bottom: -35px">{{$user->name}}</p> <br> <span><small>({{$user->role->name}})</small></span>
                    </div>
                @endforeach
            </div>
            @foreach($permissions as $permission)
                <div class="flex flex-row mb-1 mt-1">
                    <div style="width: 300px">
                        <p style="margin-bottom: -10px">{{$permission->name}}</p>
                        <small><i>{{$permission->description}}</i></small>
                    </div>
                    @foreach($tenant->users as $user)
                        <div class="me-4" style="width: 150px; text-align: center">
                            <input
                                type="checkbox"
                                style="margin-top: 10px"
                                id="id_{{$user->id}}_{{$permission->id}}"
                                value="{{$permission->id}}"
                                onchange="updatePermission({{$user->id}}, {{$permission->id}})"
                                @if(\App\Models\PermissionUser::where('user_id', $user->id)->where('permission_id', $permission->id)->exists())
                                    checked
                                @endif
                            >
                        </div>
                    @endforeach
                </div>
                @if(!$loop->last)
                    <hr>
                @endif
            @endforeach
        </div>
    </div>
</div>
<br>
<h3 style="font-weight: 900">Dati generali</h3>
<div class="card">
    <div class="card-body">
        <div class="space-y-5">
            <div class="border-b border-gray-900/10 pb-12">
                <p class="mt-1 text-sm leading-6 text-gray-600">Informazioni relative all'istanza del cliente</p>

                <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-9">

                    <div class="sm:col-span-4">
                        <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                        <div class="mt-2">
                            <input type="text" value="{{$tenant->email}}" name="first-name" id="email" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Numero di telefono</label>
                        <div class="mt-2">
                            <input type="text" value="{{$tenant->phone}}" name="last-name" id="phone" autocomplete="family-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">&nbsp;</label>
                        <div class="mt-2">
                            <button onclick="updateData()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                Aggiorna
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateData(){
        let url = '{{env('APP_URL')}}/settings/update_data';
        let data = {
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            tenant_id: '{{$tenant->id}}'
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                'Content-Type': 'application/json'
            }
        });

        jQuery.ajax({
            url: url,
            method: 'POST',
            datatype: 'json',
            data: JSON.stringify(data),
            success: function(response){
                window.location.reload()
            },
            error: function(error){
                console.log(error);
            }
        });

    }
</script>

