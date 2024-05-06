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
<div id="container">
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center justify-content-lg-between">

            <h1 class="logo me-auto me-lg-0">BE</h1>

            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>

                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>

    <section id="hero" class="d-flex align-items-center justify-content-center">
        <div class="container" data-aos="fade-up">

            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
                <div class="col-xl-6 col-lg-8">
                    <h1>{{env('APP_NAME')}}</h1>
                    @if(!$booking->is_confirmed)
                        <h4 style="color: white!important;">
                            Inserisci il codice ricevuto via email per confermare la prenotazione.
                        </h4>
                        <h6 style="color: white!important;">(Ha una validità di 10 minuti)</h6>
                        <br>
                        <input type="text" class="form-control text-center" placeholder="OTP" maxlength="6">
                        <button class="btn btn-primary mt-3" onclick="checkOtp('{{$booking->id}}')">Conferma</button>
                    @else
                        @php($tenant = $booking->tenant)
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('ok.svg') }}" alt="Prenotazione completata con successo" class="img-fluid" style="width: 30%">
                                            <h2 class="card-title">Prenotazione completata con successo!</h2>
                                            <p class="card-text">Ti ringraziamo per la tua prenotazione. <br> I tuoi biglietti verranno inviati via email.</p>
                                            <p class="card-text">Per eventuali domande, contattaci a <a href="mailto:{{$tenant->email}}">{{$tenant->email}}</a>.</p>

                                            <br>
                                            {{$tenant->name}} - {{$tenant->phone}}
                                            @if(!$booking->is_confirmed)
                                                <h6>Prenotazione effettuata il: {{$data_richiesta}}</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Loader div -->
<div id="loader" class="position-absolute top-50 start-50 translate-middle" style="display: none;">
    <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>


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

    let checkOtp = function(booking_id){

        // Show loader and update container opacity
        document.getElementById('container').style.opacity = '0.3';
        document.getElementById('loader').style.display = 'block';

        let otp = document.querySelector('input').value;

        if(otp.length < 6){
            customAlert('Errore', 'Inserisci un codice OTP valido', 'error');
            return;
        }

        let url = '{{env('APP_URL')}}/check-otp';
        let objData = {
            otp,
            booking_id: booking_id
        };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(objData)
        })
            .then(response => response.json())
            .then(data => {
                if(data.status == 'ok'){
                    window.location.reload()
                }else{
                    document.getElementById('container').style.opacity = '1';
                    document.getElementById('loader').style.display = 'none';

                    alert('Codice non valido');
                    let ele = document.querySelectorAll('input');
                    ele.forEach((el)=>{
                        el.value = '';
                    });
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
</script>

<script>

    let totalDataArr = [];

    document.addEventListener('DOMContentLoaded',function(){

    })
</script>
</body>
</html>
