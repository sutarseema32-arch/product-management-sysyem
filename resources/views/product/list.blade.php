<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" 
          href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.6.1/css/responsive.bootstrap4.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">



</head>

<body>
<div class="container mt-5">
    <a href="{{route('product.create')}}" class="btn btn-primary" style="float: right;">Add Product</a>
    
    <h2 class="mb-4">Product Details</h2>

     <div id="message" class=""></div>

     <div class="table-responsive">
    <table id="product_tbl" class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Product Description</th>
                <th>Product Images</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        
        </tbody>
    </table>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

<script>
var table = null;
$(document).ready(function() {
    table = $('#product_tbl').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('product.fetch_product') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'S.No' }, // serial number
            { data: 'product_name', name: 'product_name' },
            { data: 'product_price', name: 'product_price' },
            { data: 'product_desc', name: 'product_desc' },
            { data: 'image_column', name: 'image_column' },
            { data: 'action', name: 'action' },
        ]
    });

   $(document).on('click', '.delete-product', function(){
    if(!confirm('Are you sure you want to delete this product?')) return;

    let id = $(this).data('id');

    $.ajax({
        url: '/product/' + id,
        type: 'DELETE',
        data: {_token: $('meta[name="csrf-token"]').attr('content')},
        success: function(res){
            if(res.success){
                 $("#message").html(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${res.message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                `);
                table.ajax.reload(); // Reload table data
            }
        },
        error: function(xhr){
            alert('Something went wrong!');
        }
    });
});


});
</script>

</body>
</html>
