<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Product</h4>
        </div>

        <div class="card-body">

             <div id="message" class="mt-3"></div>

            <form  method="POST" enctype="multipart/form-data" id="editProductForm"   data-url="{{ route('product.update',$product->id) }}">
                @csrf
                @method('PUT')

                <!-- Post Title -->
                <div class="form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Enter Product Name" value="{{$product->product_name}}"> 
                    <span class="text-danger error-text product_name_error"></span>
                </div>

                <div class="form-group">
                    <label>Product Price <span class="text-danger">*</span></label>
                    <input type="text" id="product_price" name="product_price" class="form-control" placeholder="Enter Product Price" value="{{$product->product_price}}">
                    <span class="text-danger error-text product_price_error"></span>
                </div>

                <!-- Post Body -->
                <div class="form-group">
                    <label>Product Description <span class="text-danger">*</span></label>
                    <textarea id="product_desc" name="product_desc" rows="5" class="form-control" placeholder="Enter Product Description">{{$product->product_desc}}</textarea>
                    <span class="text-danger error-text product_desc_error"></span>
                </div>

                <div class="form-group">
                     <label>Existing Images</label>
    <div id="existingImages">
        @foreach($product->images as $img)
            <div style="display:inline-block; position:relative; margin:5px;">
                <img src="{{ asset('storage/products/' . $img->image_path) }}" width="100" height="100" alt="Product Image">
                
            </div>
        @endforeach
    </div>
                </div>

                <div class="form-group">
                    <label>Product Images <span class="text-danger">*</span></label>
                    <input type="file" id="image_path" name="image_path[]" class="form-control" multiple="">
                    <span class="text-danger error-text image_path_error"></span>
                </div>

                

                <!-- Submit -->
                <button type="submit" class="btn btn-success">
                    Save Product
                </button>

                <a href="{{route('product.index')}}" class="btn btn-secondary">
                    Back
                </a>

            </form>

           
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>


<script src="{{asset('asset/add.js')}}">

</script>


</body>
</html>
