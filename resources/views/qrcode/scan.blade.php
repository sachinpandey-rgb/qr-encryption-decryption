<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Products</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow border-0">

                <div class="card-header bg-success text-white text-center py-3">

                    <h3 class="mb-0">
                        ✅ Product Authenticated
                    </h3>

                </div>

                <div class="card-body">
                    <div>
                        <pre>{{ json_encode($payload, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    <div class="alert alert-success">
                        This product has been successfully verified and is genuine.
                    </div>

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

                    </table>

                </div>

                <div class="card-footer text-center bg-light">

                    <small class="text-muted">
                        Verification completed successfully.
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>


</body>
</html>