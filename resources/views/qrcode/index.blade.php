<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Products</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .page-header {
            background: #fff;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .table-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .table thead {
            background: #007bff;
            color: #fff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .qr-image {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 5px;
            background: #fff;
        }

        .badge-id {
            font-size: 14px;
            padding: 8px 12px;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .title {
            font-weight: 700;
            margin-bottom: 0;
        }

        .subtitle {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center">

        <div>
            <h3 class="title">
                <i class="fas fa-qrcode text-primary"></i>
                QR Product Management
            </h3>

            <div class="subtitle">
                Manage and view generated QR codes
            </div>
        </div>

        <a href="/create" class="btn btn-success">
            <i class="fas fa-plus-circle"></i>
            Create Product
        </a>

    </div>

    <!-- Table Card -->
    <div class="table-card">

        <div class="mb-3">
            <h5>
                <i class="fas fa-list"></i>
                Generated QR Codes
            </h5>
        </div>

        <div class="table-responsive">

            <table class="table table-hover table-bordered">

                <thead>
                    <tr>
                        <th width="80">#</th>
                        <th>Unique ID</th>
                        <th width="150">QR Preview</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($qrs as $index => $qr)

                    <tr>
                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            <span class="badge badge-primary badge-id">
                                {{ $qr->unique_id }}
                            </span>
                        </td>

                        <td class="text-center">
                            <img
                                src="{{ asset('storage/'.$qr->qr_image) }}"
                                alt="QR Code"
                                class="qr-image">
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3">

                            <div class="empty-state">
                                <i class="fas fa-qrcode fa-3x mb-3"></i>

                                <h5>No QR Codes Found</h5>

                                <p>
                                    Click "Create Product" to generate your first QR code.
                                </p>
                            </div>

                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>