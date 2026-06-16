<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open {{ $appName }}</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background: linear-gradient(160deg, #0d6efd 0%, #004085 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .redirect-card {
            background: #fff;
            color: #212529;
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
            max-width: 420px;
            width: 100%;
            overflow: hidden;
        }

        .redirect-header {
            background: #f8f9fa;
            padding: 28px 24px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        .app-icon {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: #0d6efd;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 12px;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .redirect-body {
            padding: 24px;
        }

        .hint {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container px-3">
    <div class="redirect-card mx-auto">

        <div class="redirect-header">
            <div class="app-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h4 class="mb-1">Opening {{ $appName }}</h4>
            <p class="hint mb-0">
                Redirecting you to the app to verify this product…
            </p>
        </div>

        <div class="redirect-body text-center">

            <div class="mb-4" id="loadingState">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="hint mt-3 mb-0">Please wait…</p>
            </div>

            <div id="actionButtons" style="display: none;">
                <a href="{{ $primaryOpenUrl }}"
                   id="openAppBtn"
                   class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-mobile-alt"></i> Open in {{ $appName }}
                </a>

                <a href="{{ $playStoreUrl }}"
                   class="btn btn-outline-success btn-block mb-2"
                   target="_blank"
                   rel="noopener">
                    <i class="fab fa-google-play"></i> Install from Play Store
                </a>

                <a href="{{ $webVerifyUrl }}"
                   class="btn btn-link btn-block">
                    Continue in browser instead
                </a>
            </div>

        </div>

    </div>
</div>

<script>
    (function () {
        var deepLink = @json($deepLink);
        var androidIntent = @json($androidIntentUrl);
        var playStoreUrl = @json($playStoreUrl);
        var isAndroid = @json($isAndroid);

        function showActions() {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('actionButtons').style.display = 'block';
        }

        function tryOpenApp() {
            if (isAndroid) {
                window.location.href = androidIntent;
            } else {
                window.location.href = deepLink;
            }

            setTimeout(showActions, 2500);
            setTimeout(function () {
                if (document.hidden) {
                    return;
                }
            }, 3000);
        }

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                document.getElementById('loadingState').style.display = 'none';
            }
        });

        tryOpenApp();
    })();
</script>

</body>
</html>
