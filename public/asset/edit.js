$(document).ready(function () {



    $("#productForm").on("submit", function(e) {
        e.preventDefault(); // stop default submit

        // Check if form is valid
        if ($("#productForm").valid()) {

            // Collect form data (including file)
            let  formData = new FormData(this);
            let url = $(this).data('url');

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
                    $('#productForm')[0].reset();// remove error highlight
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

        } else {
            // Form is invalid, validation messages will show automatically
            console.log("Form invalid");
        }
    });


});