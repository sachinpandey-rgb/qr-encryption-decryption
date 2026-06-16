<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View QR Code</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
        }

        .qr-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .qr-card-header {
            background: #007bff;
            color: #fff;
            padding: 24px;
            text-align: center;
        }

        .qr-display {
            padding: 40px 24px;
            text-align: center;
            background: #fff;
        }

        .qr-display img {
            width: 100%;
            max-width: 360px;
            height: auto;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 16px;
            background: #fff;
        }

        .scan-url-box {
            background: #f8f9fa;
            border: 1px dashed #ced4da;
            border-radius: 8px;
            padding: 12px 16px;
            word-break: break-all;
            font-family: monospace;
            font-size: 13px;
        }

        .hint {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="qr-card">

                <div class="qr-card-header">
                    <h3 class="mb-1">
                        <i class="fas fa-qrcode"></i> Scan QR Code
                    </h3>
                    <small>Use your phone camera to scan and verify this product</small>
                </div>

                <div class="qr-display">
                    <img
                        src="{{ asset('storage/'.$qr->qr_image) }}"
                        alt="QR Code">

                    <p class="hint mt-4 mb-2">
                        Point your phone camera at the QR code above
                    </p>

                    <div class="scan-url-box mb-3" id="scanUrl">
                        {{ $scanUrl }}
                    </div>

                    <div class="d-flex flex-wrap justify-content-center">
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm mr-2 mb-2"
                                onclick="copyScanUrl()">
                            <i class="fas fa-copy"></i> Copy URL
                        </button>

                        @if($qr->activeToken)
                            <a href="/v/{{ $qr->activeToken->token }}"
                               class="btn btn-primary btn-sm mr-2 mb-2"
                               target="_blank">
                                <i class="fas fa-check-circle"></i> Test Verify
                            </a>
                        @else
                            <a href="/scan?str={{ $qr->unique_id }}"
                               class="btn btn-primary btn-sm mr-2 mb-2"
                               target="_blank">
                                <i class="fas fa-check-circle"></i> Test Verify
                            </a>
                        @endif

                        <a href="{{ asset('storage/'.$qr->qr_image) }}"
                           class="btn btn-outline-primary btn-sm mb-2"
                           download="{{ $qr->unique_id }}.svg">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-light text-center py-3">
                    <div class="small text-muted mb-2">
                        Product ID: {{ $qr->unique_id }}
                    </div>
                    <a href="/" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    function copyScanUrl() {
        const text = document.getElementById('scanUrl').innerText.trim();

        navigator.clipboard.writeText(text).then(function () {
            alert('Scan URL copied to clipboard.');
        }).catch(function () {
            prompt('Copy this URL:', text);
        });
    }
</script>

</body>
</html>
