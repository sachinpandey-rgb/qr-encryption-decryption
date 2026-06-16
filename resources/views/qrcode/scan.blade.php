<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Verification</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            @php
                $headers = [
                    'verified' => ['class' => 'bg-success', 'title' => 'Product Authenticated'],
                    'already_scanned' => ['class' => 'bg-warning text-dark', 'title' => 'Already Verified'],
                    'expired' => ['class' => 'bg-secondary', 'title' => 'QR Code Expired'],
                    'invalid' => ['class' => 'bg-danger', 'title' => 'Verification Failed'],
                ];

                $header = $headers[$status] ?? $headers['invalid'];
            @endphp

            <div class="card shadow border-0">

                <div class="card-header {{ $header['class'] }} text-white text-center py-3">

                    <h3 class="mb-0">
                        {{ $header['title'] }}
                    </h3>

                </div>

                <div class="card-body">

                    <div class="alert alert-{{ $status === 'verified' ? 'success' : ($status === 'already_scanned' ? 'warning' : 'danger') }}">
                        {{ $message }}
                    </div>

                    @if($status === 'verified' && ! empty($payload))

                        <table class="table table-bordered">

                            <tr>
                                <th width="30%">QR ID</th>
                                <td>{{ $payload['qr_uid'] ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Product Name</th>
                                <td>{{ $payload['product']['name'] ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Company Form</th>
                                <td>{{ $payload['product']['company_form'] ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Company Name</th>
                                <td>{{ $payload['product']['company_name'] ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Price</th>
                                <td>
                                    ₹ {{ number_format($payload['product']['price'] ?? 0) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Reward Points</th>
                                <td>
                                    {{ $payload['product']['reward_points'] ?? 0 }}
                                </td>
                            </tr>

                            <tr>
                                <th>Issued At</th>
                                <td>{{ $payload['issued_at'] ?? '-' }}</td>
                            </tr>

                            @if($record?->expires_at)
                                <tr>
                                    <th>Valid Until</th>
                                    <td>{{ $record->expires_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endif

                        </table>

                    @elseif($status === 'already_scanned' && $record)

                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">First Verified</th>
                                <td>{{ $record->first_scanned_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Last Verified</th>
                                <td>{{ $record->last_scanned_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Scans</th>
                                <td>{{ $record->scan_count }}</td>
                            </tr>
                        </table>

                    @elseif($status === 'expired' && $record)

                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Expired On</th>
                                <td>{{ $record->expires_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                            </tr>
                        </table>

                    @endif

                </div>

                <div class="card-footer text-center bg-light">

                    <a href="/" class="btn btn-outline-primary btn-sm">
                        Back to QR List
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


</body>
</html>
