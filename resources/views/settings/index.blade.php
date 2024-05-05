@section('title')
    Impostazioni
@endsection

<x-app-layout>

    <div class="flex flex-row justify-between mb-4">
        <div class="basis-3/4" >
            <h1>Impostazioni: <span id="tenant_customer">Loading...</span>
            </h1>
        </div>
        <div class="basis-1/4 text-end">
            <select name="" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" onchange="getTentantInfo()" id="select_tenant" style="width: 80%!important;">
                @foreach($tenances as $tenant)
                    <option value="{{$tenant->id}}">{{$tenant->name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="permissions_container"></div>

    <script>
        let obj = {};

        document.addEventListener('DOMContentLoaded', function(){
            getTentantInfo(document.querySelector('#select_tenant').value);
            obj.tenant_selected = document.querySelector('#select_tenant').value;
        });

        function getTentantInfo(){
            $('#permissions_container').html('<div class="text-center">waiting...</div>');
            let select = document.querySelector('#select_tenant');

            obj.tenant_selected = document.querySelector('#select_tenant').value

            let url = '{{env('APP_URL')}}/settings/get_tenant_info';

            let data = {
                tenant_id: select.value ? select.value : obj.tenant_selected
            };

            // prendo il text della option selezionata
            let tenant_customer = select.options[select.selectedIndex].text;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                }
            });
            jQuery.ajax({
                url: url,
                method: 'POST',
                datatype: 'json',
                data: data,
                success: function(response){
                    $('#tenant_customer').html('<b>'+tenant_customer.toUpperCase()+'</b>');
                    $('#permissions_container').html(response);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>

    {{--@foreach($tenances as $tenant)
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
    @endforeach--}}

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

                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    </script>


</x-app-layout>
