<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{env('APP_NAME')}}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="/new_assets/img/favicon.png" rel="icon">
    <link href="/new_assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="/new_assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="/new_assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/new_assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/new_assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/new_assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/new_assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/new_assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="/new_assets/css/style.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #map { height: 280px; }
        .accordion-item {
            border: none!important;
        }
        .accordion-body {
            padding: 0!important;
        }
    </style>
</head>
<body>

<header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center justify-content-lg-between">

        <h1 class="logo me-auto me-lg-0">BE</h1>

        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>

            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>

        <a href="#about" class="get-started-btn scrollto">Chi siamo</a>
    </div>
</header>

<section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
            <div class="col-xl-6 col-lg-8">
                <h1>{{env('APP_NAME')}}</h1>
                <h6 style="color: white!important;">
                    Esplora una vasta gamma di eventi e spettacoli, dai concerti alle mostre e alle serate in discoteca.
                    Scopri cosa c'è di nuovo nella tua città e dintorni, pianifica le tue serate con facilità e prenota i tuoi biglietti in pochi semplici passaggi.
                </h6>
                <br>
                <a href="#contact" class="get-started-btn scrollto">Prenota il tuo ticket</a>
            </div>
        </div>
    </div>
</section>

<main id="main">
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">

            <div class="row">
                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
                    <img src="/new_assets/img/about.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content" data-aos="fade-right" data-aos-delay="100">
                    <h3>Benvenuti su {{env('APP_NAME')}}</h3>
                    <p class="fst-italic">
                        Se stai cercando un modo semplice e sicuro per partecipare agli eventi più emozionanti e culturalmente ricchi della tua città, sei nel posto giusto. {{env('APP_NAME')}} ti garantisce un'esperienza di prenotazione rapida, conveniente e senza stress.
                    </p>
                    <ul>
                        <li><i class="ri-check-double-line"></i> Accesso istantaneo agli eventi.</li>
                        <li><i class="ri-check-double-line"></i> Prenotare i tuoi biglietti con {{env('APP_NAME')}} è conveniente, sicuro e affidabile.</li>
                        <li><i class="ri-check-double-line"></i> {{env('APP_NAME')}} è il tuo compagno ideale per esplorare la vibrante scena della tua città e vivere esperienze indimenticabili.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Evento</h2>
                    <p>{{strtoupper($event->name)}}</p>
                <h3>{{date('d/m/Y H:i', strtotime($event->datetime_from))}}</h3> <br>
                    <span>{{$event->description}}</span>

            </div>

            <div id="map"></div>

            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <p>
                        <i>
                            Il biglietto elettronico per l'accesso all'evento verrà inviato all'indirizzo email fornito.
                        </i>
                    </p>
                </div>
            </div>

            <div class="row mt-2">

                <div class="col-lg-5">
                    <div class="info">
                        <div class="address">
                            <i class="bi bi-calendar3"></i>
                            <h4>Quando:</h4>
                            <p style="margin-top: -10px">{{date('d/m/Y H:i', strtotime($event->datetime_from))}}</p>
                        </div>
                        <div class="phone">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Dove:</h4>
                            <p style="margin-top: -10px">{{$event->location->address}} {{$event->location->city}}</p>
                        </div>
                        @if($event->show_referente)
                            <div class="email">
                                <i class="bi bi-envelope"></i>
                                <h4>Referente:</h4>
                                <p style="margin-top: -10px">{{$event->ref_user_email}} - {{$event->ref_user_phone_number}}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7 mt-5 mt-lg-0">

                    @if($event->prenotati >= $event->max_prenotabili) {{-- evento completato, nessun'altra prenotazione possibile --}}
                        <div class="row">
                            <div class="col-md-12 form-group text-center">
                                <div class="alert text-center" role="alert">
                                    <img style="width: 30%" src="{{asset('soldout.png')}}" alt="">
                                    <br><strong>Posti esauriti</strong>
                                    <p>Ticket non disponibili per questo evento</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <form id="form" class="php-email-form">
                            {{csrf_field()}}
                            <input type="hidden" name="event_id" value="{{$event->id}}">
                            <div class="form-group mt-3">
                                <input type="text" name="nome" class="form-control" id="name" placeholder="Il tuo nome*" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Numero di telefono*" required>
                                </div>
                                <div class="col-md-6 form-group mt-3 mt-md-0">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="La tua email*" required>
                                </div>
                            </div>
                            @if($event->ticket_for_person > 1)
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button  class="text-black get-started-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                @if($event->ticket_for_person - 1 == 1)
                                                    Aggiungi un'altra persona
                                                @else
                                                    Aggiungi altre {{$event->ticket_for_person - 1}}  persone
                                                @endif
                                                {{--Aggiungi  {{$event->ticket_for_person - 1}} {{$event->ticket_for_person - 1 == 1 ? 'persona' : "persone"}}--}}
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                {{--@for($i = 1; $event->ticket_for_person <= $i; $i++)
                                                    <p>{{$i}}</p>
                                                @endfor--}}
                                                @for($i = 1; $i < $event->ticket_for_person; $i++)
                                                    <div class="row @if($i == 1) mt-4 @endif">
                                                        <div class="col-md-5 form-group">
                                                            <input type="text" class="form-control" name="nome_{{$i}}" id="name_{{$i}}" placeholder="Nome*" required>
                                                        </div>
                                                        <div class="col-md-5 form-group mt-3 mt-md-0">
                                                            <input type="text" class="form-control" name="phone_number_{{$i}}" id="phone_number_{{$i}}" placeholder="Numero di Telefono*" required>
                                                        </div>
                                                        <div class="col-md-2 form-group mt-3 mt-md-0">
                                                            <button class="get-started-btn text-black" type="button" onclick="addPersonObj('name_{{$i}}', 'phone_number_{{$i}}', this)">Aggiungi</button>
                                                        </div>
                                                    </div>

                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                                <div class="my-1">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Prenotazione inviata. Grazie!</div>
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    @if($event->is_payment_required == 'no')
                                        <button id="prenota_button" class="btn btn-lg" style="background-color: #FFC351;" type="button">Prenotati</button>
                                    @endif
                                </div>
                        </form>
                        @if($event->is_payment_required != 'no')
                            <script src="https://www.paypal.com/sdk/js?client-id=Aa8OWqhWwkDo97GxF0BcLqR_iLxLXOPmsObJprF4Ow3LCerjtst4eP1EFG6UG-c5tMeV4ZmTJtx64Dhm"></script>
                            <div id="paypal-button-container"></div>
                            <script>

                                document.addEventListener('DOMContentLoaded', function(){

                                    function emailAlreadyUsed(email) {
                                        return new Promise((resolve, reject) => {
                                            let url = '{{env('APP_URL')}}/check_event_email';
                                            let data = {
                                                email: email,
                                                event_id: document.querySelector('input[name="event_id"]').value
                                            };
                                            fetch(url, {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                },
                                                body: JSON.stringify(data)
                                            })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if(data.status === 'ok') {
                                                        resolve(false);
                                                    } else {
                                                        resolve(true);
                                                    }
                                                })
                                                .catch(error => {
                                                    reject(error);
                                                });
                                        });
                                    }


                                    paypal.Buttons({
                                        createOrder: function(data, actions) {
                                            let name = document.querySelector('input[name="nome"]').value;
                                            let email = document.querySelector('input[name="email"]').value;
                                            let phone_number = document.querySelector('input[name="phone_number"]').value;

                                            // controllo se l'email è già stata utilizzata per prenotare questo evento
                                            return emailAlreadyUsed(email).then((result) => {
                                                if(result) {
                                                    throw new Error('Email già utilizzata per prenotare questo evento');
                                                } else {
                                                    if(name === '' || email === '' || phone_number === ''){
                                                        customAlert('Errore', 'Inserisci il tuo nome, la tua email e il tuo numero di telefono', 'error');
                                                        throw new Error('Dati mancanti');
                                                    } else {
                                                        let objData = {
                                                            nome: name,
                                                            email,
                                                            phone_number,
                                                            first_person: true,
                                                            event_id: document.querySelector('input[name="event_id"]').value
                                                        };

                                                        totalDataArr.push(objData);

                                                        // controllo se ci sono oggetti duplicati, ed eventualmente li rimuovo
                                                        let uniqueDataArr = totalDataArr.filter((v,i,a)=>a.findIndex(t=>(t.nome === v.nome && t.phone_number === v.phone_number))===i);

                                                        let url = '{{env('APP_URL')}}/bookings';

                                                        // scroll to top
                                                        window.scrollTo(0, 0);

                                                        setTimeout(function(){
                                                            saveData(url, uniqueDataArr, true);
                                                        }, 1500);

                                                        let value = '{{$event->price}}';
                                                        let number_ticket = totalDataArr.length;
                                                        let total = value * number_ticket;
                                                        return actions.order.create({
                                                            purchase_units: [{
                                                                amount: {
                                                                    value: total.toFixed(2)
                                                                }
                                                            }]
                                                        });
                                                    }
                                                }
                                            });
                                        },
                                        onApprove: function(data, actions) {
                                            // scroll to top
                                            window.scrollTo(0, 0);

                                            // Show loader and update container opacity
                                            document.getElementById('hero').style.opacity = '0.3';
                                            document.getElementById('loader').style.display = 'block';

                                            return actions.order.capture().then(function(details) {
                                                let dettagli = btoa(JSON.stringify(details));
                                                let event_id = '{{$event->id}}';
                                                savePaypalDetails(event_id, dettagli);
                                                //window.location.href = '{{env('APP_URL')}}/booking/success/'+dettagli /*+ '/' + event_id + '/' + document.querySelector('input[name="email"]').value*/;
                                            });
                                        },
                                        onError: function (err) {
                                            //console.log(err);
                                            customAlert('Errore', err, 'error');
                                            setTimeout(() => window.location.reload(), 3000);
                                        }
                                    }).render('#paypal-button-container');

                                });

                                async function savePaypalDetails(eventId, details) {

                                    let url = '{{env('APP_URL')}}/bookings/save_paypal';
                                    let data = {
                                        event_id: eventId,
                                        details: details,
                                        email: document.querySelector('#email').value
                                    };

                                    let response = await fetch(url, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify(data)
                                    });

                                    let result = await response.json();

                                    if(result.status === 'ok') {
                                        //console.log(result);
                                        window.location.href = '{{env('APP_URL')}}/booking/success/' + result.booking.id;
                                    } else {
                                        document.getElementById('hero').style.opacity = '1';
                                        document.getElementById('loader').style.display = 'none';
                                        customAlert('Errore', result.message, 'error');
                                    }

                                }
                            </script>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

<footer id="footer">
    <div id="loader" class="position-absolute top-50 start-50 translate-middle" style="display: none;">
        <div class="spinner-border" role="status" style="width: 5rem; height: 5rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong><span>BE</span></strong> All Rights Reserved
        </div>
        {{--<div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>--}}
    </div>
</footer><!-- End Footer -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="/new_assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="/new_assets/vendor/aos/aos.js"></script>
<script src="/new_assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/new_assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="/new_assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="/new_assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="/new_assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="/new_assets/js/main.js"></script>

<script>
    function customAlert(title, message, type = 'success') {
        Swal.fire({
            title: title,
            icon: type,
            html: message,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            showConfirmButton: false,
        });
    }
</script>

<script>

    let totalDataArr = [];

    document.addEventListener('DOMContentLoaded',function(){
        let longitude = '{{$event->location->longitude}}';
        let latitude = '{{$event->location->latitude}}';

        var map = L.map('map').setView([latitude, longitude], 16);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([latitude, longitude]).addTo(map);
    })

    function addPersonObj(name, phone, button){

        let nome = document.querySelector('#'+name);
        let phone_number = document.querySelector('#'+phone);

        if(nome.value === '' || phone_number.value === ''){
            customAlert('Errore', 'Inserisci il nome e il numero di telefono', 'error');
            return;
        }

        let objData = {
            nome: nome.value,
            phone_number: phone_number.value,
            first_person: false,
            event_id: document.querySelector('input[name="event_id"]').value
        };

        totalDataArr.push(objData);

        button.setAttribute('disabled', 'disabled');
        button.innerHTML = 'Aggiunto';
        button.style.backgroundColor = '#FFC351';

        nome.setAttribute('disabled', 'disabled');
        nome.style.backgroundColor = 'lightgray';
        phone_number.setAttribute('disabled', 'disabled');
        phone_number.style.backgroundColor = 'lightgray';
    }

    let submitButton = document.querySelector('#prenota_button');
    if(submitButton === null){
        //submitButton = document.querySelector('#prenota_button_paypal');
    } else {
        submitButton.addEventListener('click', function(){

            // Show loader and update container opacity
            document.getElementById('hero').style.opacity = '0.3';
            document.getElementById('loader').style.display = 'block';

            let nome = document.querySelector('input[name="nome"]').value;
            let email = document.querySelector('input[name="email"]').value;
            let phone_number = document.querySelector('input[name="phone_number"]').value;
            let event_id = document.querySelector('input[name="event_id"]').value;

            let html_msg_error = '';

            if(nome === ''){
                html_msg_error += 'Inserisci il tuo nome<br>';
            }
            if(email === ''){
                html_msg_error += 'Inserisci la tua email<br>';
            }
            if(phone_number === ''){
                html_msg_error += 'Inserisci il tuo numero di telefono<br>';
            }

            if(html_msg_error !== ''){
                document.getElementById('hero').style.opacity = '1';
                document.getElementById('loader').style.display = 'none';
                customAlert('Errore', html_msg_error, 'error');
                return;
            }

            let objData = {
                nome,
                email,
                phone_number,
                first_person: true,
                event_id
            };

            totalDataArr.push(objData);

            // controllo se ci sono oggetti duplicati, ed eventualmente li rimuovo
            let uniqueDataArr = totalDataArr.filter((v,i,a)=>a.findIndex(t=>(t.nome === v.nome && t.phone_number === v.phone_number))===i);

            // scroll to top
            window.scrollTo(0, 0);

            let url = '{{env('APP_URL')}}/bookings';

            setTimeout(function(){
                saveData(url, uniqueDataArr);
            }, 1500);
        });
    }


    async function saveData(url, data, from_payment = false){

        let response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        let result = await response.json();

        if(result.status === 'ok') {
            if(from_payment){
                //
            }else{
                window.location.href = '{{env('APP_URL')}}/bookings/confirmation/' + result.booking.id;
            }
        }else if(result.status === 'ko'){
            document.getElementById('hero').style.opacity = '1';
            document.getElementById('loader').style.display = 'none';
            customAlert('Errore',result.message,'error');
        }else{
            document.getElementById('hero').style.opacity = '1';
            document.getElementById('loader').style.display = 'none';
            customAlert('Errore','Errore nella prenotazione','error');
        }
    }
</script>
</body>
</html>
