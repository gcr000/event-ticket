@section('title')
    Utenti
@endsection

<x-app-layout>
    <form action="{{route('users.store')}}" method="POST">
        {{csrf_field()}}

        {{--Modal for creating user--}}
        <div id="modelConfirm"
             class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
            <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-3xl">

                <div class="flex justify-end p-2">
                    <button onclick="closeModal('modelConfirm')" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 pt-0 text-center flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5"
                         stroke="currentColor" width="110" height="110">
                        <path strokeLinecap="round" strokeLinejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <h3 class="text-xl font-normal text-gray-500 mt-5 mb-6">Aggiungi un nuovo utente</h3>

                    <div class="flex justify-center">
                        <div class="w-1/3 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="grid-first-name">
                                Nome e Cognome
                            </label>
                            <input name="name_create"
                                   required
                                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-blue-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                   id="grid-first-name" type="text" placeholder="">
                        </div>
                        <div class="w-1/3 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="grid-last-name">
                                Email
                            </label>
                            <input name="email_create"
                                   required
                                   class="appearance-none block border-blue-500 w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                   id="grid-last-name" type="email" placeholder="">
                        </div>
                        <div class="w-1/3 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="grid-phone">
                                Numero di telefono
                            </label>
                            <input name="phone_number_create"
                                      required
                                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-blue-500 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                   id="grid-phone" type="text" placeholder="">
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="grid-state">
                                Ruolo
                            </label>
                            <div class="relative">
                                <select name="role_id_create"
                                        class="block appearance-none w-full bg-gray-200 border border-blue-500 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                        id="grid-state">
                                    <option value="2">Admin</option>
                                    <option value="3">Manager</option>
                                    <option value="4">User</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="grid-password">
                                Password
                            </label>
                            <input name="password_create"
                                      required
                                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-blue-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                   id="grid-password" type="password" placeholder="******************">
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button
                            class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2"
                            type="submit">Salva Utente
                        </button>

                        <a href="#" onclick="closeModal('modelConfirm')"
                           class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center"
                           data-modal-toggle="delete-user-modal">
                            Cancella
                        </a>
                    </div>
                </div>
            </div>
        </div>


        {{--Modal for editing user--}}
        <div id="modelEditing"
             class="fixed hidden z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
            <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-3xl">

                <div class="flex justify-end p-2">
                    <button onclick="closeModal('modelEditing')" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 pt-0 text-center flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5"
                         stroke="currentColor" width="110" height="110">
                        <path strokeLinecap="round" strokeLinejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <h3 class="text-xl font-normal text-gray-500 mt-5 mb-6">Modifica utente</h3>

                    <div class="flex justify-center">
                        <div class="w-1/3 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="name_edit">
                                Nome e Cognome
                            </label>
                            <input name="name"
                                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-blue-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                   id="name_edit" type="text" placeholder="">
                        </div>
                        <div class="w-1/3 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="email_edit">
                                Email
                            </label>
                            <input name="email"
                                   class="appearance-none block border-blue-500 w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                   id="email_edit" type="email" placeholder="">
                        </div>
                        <div class="w-1/3 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="phone_edit">
                                Numero di telefono
                            </label>
                            <input name="phone_number"
                                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-blue-500 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                   id="phone_edit" type="text" placeholder="">
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="role_edit">
                                Ruolo
                            </label>
                            <div class="relative">
                                <select name="role_id"
                                        class="block appearance-none w-full bg-gray-200 border border-blue-500 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                        id="role_edit">
                                    <option value="2">Admin</option>
                                    <option value="3">Manager</option>
                                    <option value="4">User</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="password_edit">
                                Nuova Password
                            </label>
                            <input name="password"
                                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-blue-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                   id="password_edit" type="password" placeholder="******************">
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <input type="hidden" id="user_id_edit">
                        <button
                            class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2"
                            type="button" onclick="updateUser()">Modifica
                        </button>

                        <a href="#" onclick="closeModal('modelEditing')"
                           class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center"
                           data-modal-toggle="delete-user-modal">
                            Cancella
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="flex flex-row justify-between mb-4">

            <div>
                <h1>Utenti</h1>
            </div>

            <span style="cursor: pointer" class="bg-gray-400 rounded-md px-4 py-2 hover:text-black hover:bg-orange-200 transition"
                  onclick="openModal('modelConfirm')">
            crea utente
        </span>

        </div>

        <table class="table-auto min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Nome
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Email
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Ruolo
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Numero di telefono
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">

                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
                <tr id="riga_{{$user->id}}" @if($user->is_disabled) style="background-color: #cecec8" @endif>
                    <td class="px-6 py-4 whitespace-no-wrap" data-name="{{$user->name}}">
                        {{$user->name}}
                        @if(auth()->user()->role_id === 1)
                            <br><small>Tenant: {{$user->tenant->name}}</small>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap" data-email="{{$user->email}}">
                        {{$user->email}}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap" data-role="{{$user->role_id}}">
                        {{$user->role->name}}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap" data-phone="{{$user->phone_number}}">
                        {{$user->phone_number}}
                    </td>
                    @if($user->role_id !== 1)
                    <td class="px-6 py-4 whitespace-no-wrap text-end">
                        @if(!$user->is_disabled)
                            <span style="cursor: pointer; color: orange" onclick="manageUser('archiviare', {{$user->id}})" id="archivia_{{$user->id}}">Modifica</span> <br>
                        @endif
                        <span style="cursor: pointer; color: @if($user->is_disabled) black @else red @endif"
                              onclick="manageUser(@if($user->is_disabled) 'riattivare' @else 'disattivare' @endif, {{$user->id}})"
                              id="elimina_{{$user->id}}"
                        >
                            @if($user->is_disabled) Abilita @else Disabilita @endif
                        </span>
                    </td>
                    @else
                        <td class="px-6 py-4 whitespace-no-wrap text-end">

                        </td>
                    @endif
                </tr>
            @endforeach
            <!-- More rows here -->
            </tbody>
        </table>

    </form>
</x-app-layout>


<script>
    window.openModal = function (modalId) {
        document.getElementById(modalId).style.display = 'block'
        document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
    }

    window.closeModal = function (modalId) {
        document.getElementById(modalId).style.display = 'none'
        document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
    }

    // Close all modals when press ESC
    document.onkeydown = function (event) {
        event = event || window.event;
        if (event.keyCode === 27) {
            document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
            let modals = document.getElementsByClassName('modal');
            Array.prototype.slice.call(modals).forEach(i => {
                i.style.display = 'none'
            })
        }
    };

    function manageUser(type, userId){

        if(type === 'disattivare' || type === 'riattivare'){

            if(!confirm('Sei sicuro di voler ' + type + ' l\'utente? Gli utenti disabilitati non possono accedere al backoffice')) return;
            var url = '{{env('APP_URL')}}/disable_user/' + userId ;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                window.location.reload();
            })



        } else {
            openModal('modelEditing')

            // recupero la riga selezionata
            var row = document.getElementById('riga_' + userId);
            // recupero i dati della riga selezionata
            var name = row.querySelector('td[data-name]').getAttribute('data-name');
            var email = row.querySelector('td[data-email]').getAttribute('data-email');
            var role = row.querySelector('td[data-role]').getAttribute('data-role');
            var phone = row.querySelector('td[data-phone]').getAttribute('data-phone');

            // inserisco i dati nei campi del form
            document.getElementById('name_edit').value = name;
            document.getElementById('email_edit').value = email;
            document.getElementById('role_edit').value = role;
            document.getElementById('phone_edit').value = phone;
            document.getElementById('user_id_edit').value = userId;
        }
    }

    function updateUser(){

        let name = document.getElementById('name_edit').value;
        let email = document.getElementById('email_edit').value;
        let phone = document.getElementById('phone_edit').value;
        let role = document.getElementById('role_edit').value;
        let password = document.getElementById('password_edit').value;
        let userId = document.getElementById('user_id_edit').value;

        if(name === '' || email === '' || phone === ''){
            alert('Tutti i campi sono obbligatori');
            return;
        }

        var url = '{{env('APP_URL')}}/users/' + userId;

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{csrf_token()}}',
            },
            body: JSON.stringify({
                name,
                email,
                phone_number: phone,
                role_id: role,
                password
            })
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
    }
</script>


