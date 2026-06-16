<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>

<div class="container mt-5">

<div class="card">

<div class="card-header">
Product Information
</div>

<div class="card-body">

<h3>{{ $data['product_name'] }}</h3>

<p>
<b>SKU:</b>
{{ $data['sku'] }}
</p>

<p>
<b>Price:</b>
₹{{ $data['price'] }}
</p>

<p>
<b>Description:</b>
{{ $data['description'] }}
</p>

</div>

</div>

</div>

</body>
</html>