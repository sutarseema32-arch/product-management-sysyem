$(document).ready(function () {



    $("form").on("submit", function(e) {
        e.preventDefault(); // stop default submit

        // Check if form is valid
 
            // Collect form data (including file)
            let form = $(this);
            let  formData = new FormData(this);
            let url = $(this).data('url');
            let isEdit = form.attr('id') === 'editProductForm';

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                
                success: function(res) {
                $("#message").html(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${res.message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                `);
                    //alert(res.message);
                    $('.error-text').text('');
                    

                    if(isEdit){
                    // Update fields dynamically for edit
                    $('input[name="product_name"]').val(res.product.name);
                    $('input[name="product_price"]').val(res.product.price);
                    $('textarea[name="product_desc"]').val(res.product.desc);


                        let imagesHtml = '';
                    res.images.forEach(function(img){
                        imagesHtml += `<div class="image-item" data-id="${img.id}" style="display:inline-block; position:relative; margin:5px;">
                                        <img src="${img.url}" width="100" height="100" alt="Product Image">
                                        
                                       </div>`;
                    });
                   // alert(imagesHtml);
                    $('#existingImages').html(imagesHtml);

                    }
                    else
                    {
                        form[0].reset();
                    }

                       // Update images
                
                },
                error: function(xhr, status, error) {
                
                let errors = xhr.responseJSON.errors;
                console.log(errors);
                     $('.error-text').text(''); 
                    if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                           if (key.startsWith('image_path')) {
                                $('.image_path_error').text(value[0]);
                            } else {
                                $('.' + key + '_error').text(value[0]);
                            }
                    });
                    }
                }
            });

        
    });


});