<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<style>
    .bgWhite{
        background:white;
        box-shadow:0px 3px 6px 0px #cacaca;
    }

    .title{
        font-weight:600;
        margin-top:20px;
        font-size:24px
    }

    .customBtn{
        border-radius:0px;
        padding:10px;
    }

    form input{
        display:inline-block;
        width:50px;
        height:50px;
        text-align:center;
    }
</style>
<body>

<div class="container" id="container">
    <div class="row">
        <div class="col-md-12 text-center">
            @if(!$booking->is_confirmed)
                &nbsp;
                <br>
                <br>
                <p>Conferma la tua prenotazione</p>
            @else
                @php($tenant = $booking->tenant)
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 mt-5">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="{{ asset('ok.svg') }}" alt="Prenotazione completata con successo" class="img-fluid" style="width: 60%">
                                    <h2 class="card-title">Prenotazione completata con successo!</h2>
                                    <p class="card-text">Ti ringraziamo per la tua prenotazione. I tuoi biglietti verranno inviati via email.</p>
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



                @if(!$booking->is_confirmed)
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-4 text-center">
                                        <div class="row">
                                            <div class="col-sm-12 mt-5 bgWhite">
                                                <div class="title">
                                                    Inserisci il codice ricevuto via Email
                                                </div>

                                                <form action="" class="mt-5">
                                                    <input autofocus class="otp" type="text" oninput='digitValidate(this)' onkeyup='tabChange(1)' maxlength=1 >
                                                    <input class="otp" type="text" oninput='digitValidate(this)' onkeyup='tabChange(2)' maxlength=1 >
                                                    <input class="otp" type="text" oninput='digitValidate(this)' onkeyup='tabChange(3)' maxlength=1 >
                                                    <input class="otp" type="text" oninput='digitValidate(this)'onkeyup='tabChange(4)' maxlength=1 >
                                                    <input class="otp" type="text" oninput='digitValidate(this)'onkeyup='tabChange(5)' maxlength=1 >
                                                    <input class="otp" type="text" oninput='digitValidate(this)'onkeyup='tabChange(6)' maxlength=1 >
                                                </form>
                                                <hr class="mt-4">
                                                <button class='btn btn-primary btn-block mt-4 mb-4 customBtn btn-lg' style="border-radius: 10px" onclick="confirmCode()">
                                                    &nbsp;&nbsp;Conferma&nbsp;&nbsp;
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
        </div>
    </div>
</div>
<!-- Loader div -->
<div id="loader" class="position-absolute top-50 start-50 translate-middle" style="display: none;">
    <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script>
    let digitValidate = function(ele){
        ele.value = ele.value.replace(/[^0-9]/g,'');
    }

    let tabChange = function(val){
        let ele = document.querySelectorAll('input');
        if(ele[val-1].value != ''){
            if(val < 6)
                ele[val].focus()
        }else if(ele[val-1].value == ''){
            ele[val-2].focus()
        }
    }

    let confirmCode = function(){
        let otp = '';
        let ele = document.querySelectorAll('input');
        ele.forEach((el)=>{
            otp += el.value;
        });

        // Show loader and update container opacity
        document.getElementById('container').style.opacity = '0.3';
        document.getElementById('loader').style.display = 'block';

        checkOtp(otp, '{{$booking->id}}');
    }


    let checkOtp = function(otp, booking_id){
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>
</html>
