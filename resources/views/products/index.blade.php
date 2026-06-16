<!DOCTYPE html>
<html>
    <head>

    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    </head>
    <body>
        <div class="m-4">
            <div class="card p-1">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Qr List</h4>
                    <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary">Add</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>QR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>    <img src="{{ asset('storage/'.$product->qr_image) }}" width="100"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>