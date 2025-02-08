<script>
    // jQuery.validator.addMethod('ckrequired', function (value, element, params) {
    //     var idname = jQuery(element).attr('id');
    //     var messageLength =  jQuery.trim ( ClassicEditor.instances[idname].getData() );
    //     return !params  || messageLength.length !== 0;
    // }, "Image field is required");

    function createSlug(title) {
        var slug = title.toLowerCase();
        slug = slug.replace(/[^a-z0-9\s-]/g, '');
        slug = slug.replace(/\s+/g, '-');
        slug = slug.replace(/-+/g, '-');
        slug = slug.replace(/^-+|-+$/g, '');
        return slug;
    }

    $('#title').change(function() {

        var title = $('#title').val();
        var slug = createSlug(title);

        $('#slug').val(slug);
        $('#hiddenSlugId').val(slug);

    });

    $(document).ready(function() {
        CKEDITOR.replace('content');


        $('#add-cms-form').validate({
            ignore: [],
            rules: {

                title: {
                    required: true,

                },
                content: {
                    required: function() {
                        CKEDITOR.instances.content.updateElement();
                    },

                    minlength: 10
                },
                slug: {
                    required: true,
                }
            },
            messages: {
                title: {
                    required: "Please enter title",

                },
                content: {
                    required: "Please enter content",

                },
                slug: {
                    required: "Please enter slug",
                }
            },
            errorPlacement: function(error, element) {

                if (element.parent().hasClass('input-item')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }

            },
            submitHandler: function(form) {


                $(".data-loader").addClass('loading');

                var submissionPromise = form.submit();

                submissionPromise.done(function() {
                    $(".data-loader").removeClass('loading');
                });

                submissionPromise.fail(function() {
                    $(".data-loader").removeClass('loading');
                });

            }
        });


        $('input').on('change', function() {
            // Your code to handle the 'change' event here
            var id = $(this).attr('id');
            $('#' + id + '-error').css('display', 'none');
            $('#' + id).removeClass('error');
        });


    });
</script>
