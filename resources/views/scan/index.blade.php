<!-- scan.blade.php -->
<html>
<head>
    <title>{{env('APP_NAME')}} - scan</title>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jsQR library CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<video id="preview" width="100%"></video>
<button id="toggleCamera">Toggle Camera</button>
<button id="startScan" style="visibility: hidden">Start Scan</button>

<script>
    let qrCodeRead = false;
    let currentCamera = 'environment'; // 'environment' for back camera, 'user' for front camera

    document.getElementById('startScan').addEventListener('click', startScan);
    document.getElementById('toggleCamera').addEventListener('click', toggleCamera);

    function startScan() {
        const videoElement = document.getElementById('preview');
        const canvasElement = document.createElement('canvas');
        const canvas = canvasElement.getContext('2d');

        navigator.mediaDevices.getUserMedia({ video: { facingMode: currentCamera } })
            .then(stream => {
                videoElement.srcObject = stream;
                videoElement.play();
                requestAnimationFrame(tick);
            })
            .catch(err => console.error(err));

        function tick() {
            if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA && !qrCodeRead) {
                canvasElement.height = videoElement.videoHeight;
                canvasElement.width = videoElement.videoWidth;
                canvas.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                if (code) {
                    qrCodeRead = true;
                    $.ajax({
                        url: '/scan',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                        },
                        data: {
                            qr_code_data: code.data,
                            event_id: '{{ $event_id }}'
                        },
                        success: function(response) {

                            Swal.fire({
                                title: response.title,
                                icon: response.status,
                                html: response.message,
                                showCloseButton: false,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                timer: 2000,
                                timerProgressBar: true
                            });

                            setTimeout(() => {
                                qrCodeRead = false;
                            }, 2000);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            }
            requestAnimationFrame(tick);
        }
    }

    function toggleCamera() {
        currentCamera = (currentCamera === 'environment') ? 'user' : 'environment';
        restartScan();
    }

    function restartScan() {
        qrCodeRead = false;
        const videoElement = document.getElementById('preview');
        videoElement.srcObject.getTracks().forEach(track => track.stop());
        startScan();
    }

    // Start scanning on page load
    startScan();
</script>
</body>
</html>
