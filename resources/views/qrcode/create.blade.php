<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background: #f4f7fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            background: #007bff;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            padding: 20px;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
        }

        .btn-generate {
            height: 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .input-group-text {
            width: 45px;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-1">
                            <i class="fas fa-qrcode"></i> QR Code Generator
                        </h3>
                        <small>Create Product QR Codes</small>
                    </div>

                    <div class="card-body p-4">

                        <form action="/store" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Product Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-box"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                        class="form-control"
                                        name="name"
                                        placeholder="Enter Product Name"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Company Form</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-building"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                        class="form-control"
                                        name="company_form"
                                        placeholder="e.g. Pvt Ltd"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Company Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-industry"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                        class="form-control"
                                        name="company_name"
                                        placeholder="Enter Company Name"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Price (₹)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            ₹
                                        </span>
                                    </div>
                                    <input type="number"
                                        class="form-control"
                                        name="price"
                                        placeholder="Enter Product Price"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Reward Points</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-gift"></i>
                                        </span>
                                    </div>
                                    <input type="number"
                                        class="form-control"
                                        name="reward_points"
                                        placeholder="Enter Reward Points"
                                        required>
                                </div>
                            </div>

                            <button type="submit"
                                    class="btn btn-primary btn-block btn-generate">
                                <i class="fas fa-qrcode"></i>
                                Generate QR Code
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>