@section('title')
    Impostazioni
@endsection

<x-app-layout>

    <div class="flex flex-row justify-between mb-4">
        <div>
            <h1>Permessi</h1>
        </div>
    </div>

    @foreach($tenances as $tenant)
        <div class="card border mb-3 p-2">
            <div class="card-header mb-5">
                <h3>Tenant: <b>{{$tenant->name}}</b></h3>
            </div>
            <div class="card-body">
                <div class="w-full overflow-x-auto">
                    <div class="flex flex-row mb1">
                        <div style="width: 300px">&nbsp;</div>
                        @foreach($tenant->users as $user)
                            <div class="me-4" style="width: 150px; text-align: center">
                                {{$user->name}}
                            </div>
                        @endforeach
                    </div>
                    @foreach($permissions as $permission)
                        <div class="flex flex-row mb1">
                            <div style="width: 300px">
                                {{$permission->name}}
                            </div>
                            @foreach($tenant->users as $user)
                                <div class="me-4" style="width: 150px; text-align: center">
                                    <input
                                        type="checkbox"
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
    @endforeach

    <script>
        function updatePermission(user_id, permission_id) {
            let url = '{{env('APP_URL')}}/settings/update_permission';

            let data = {
                user_id: user_id,
                permission_id: permission_id
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    </script>


</x-app-layout>
