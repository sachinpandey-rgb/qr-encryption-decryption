<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="m-4">
            <div class="card p-1">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Qr List</h4>
                    <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary">Add</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Product Name</label>
                            <input
                                type="text"
                                name="product_name"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label>SKU</label>
                            <input
                                type="text"
                                name="sku"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input
                                type="number"
                                name="price"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea
                                name="description"
                                class="form-control">
                            </textarea>
                        </div>

                        <button class="btn btn-primary">
                            Save Product
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
