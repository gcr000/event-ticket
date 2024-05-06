<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione completata con successo</title>
    <!-- Collegamento a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset('ok.svg') }}" alt="Prenotazione completata con successo" class="img-fluid" style="width: 30%">
                    <h2 class="card-title">Prenotazione completata con successo!</h2>
                    <p class="card-text">Ti ringraziamo per la tua prenotazione. <br> I tuoi bigliettii verranno inviati via email.</p>
                    <p class="card-text">Per eventuali domande, contattaci a <a href="mailto:{{$tenant->email}}">{{$tenant->email}}</a>.</p>

                    <br>
                    {{$tenant->name}} - {{$tenant->phone}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script di collegamento a Bootstrap JS (opzionale) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
