
    <script>
        $(document).ready(function(){

            $(function () {
            var today = new Date();

        // Format the date as dd-mm-yyyy
         var formattedDate = ("0" + today.getDate()).slice(-2) + "-" + ("0" + (today.getMonth() + 1)).slice(-2) + "-" + today.getFullYear();

            $('#job_start_date').datepicker({
            format: 'dd-mm-yyyy',
            startDate: formattedDate

        });
        });



        $("#license_requirement").on('change', function(){
            var value = $(this).val();
            if(value =='Other'){
                $(".other_license_requirement").show();
            }else{
                $(".other_license_requirement").hide();
            }
        });

        $("#any_other_langauge").on('change', function(){
            var value = $(this).val();
            if(value =='Other'){
                $(".other_any_other_langauge").show();
            }else{
                $(".other_any_other_langauge").hide();
            }
        });

        $("#experience_sf").on('change', function(){
            var value = $(this).val();
            if(value =='Yes'){
                $(".license_requirement").show();
                $(".how_many_years_of_experience").show();
            }else{
                $('#last_month_year_in_sf').hide();
                $(".license_requirement").hide();
                $(".other_license_requirement").hide();
                $(".how_many_years_of_experience").hide();


            }
        });






        });
//       $('#job_start_date').datetimepicker({
//     format: 'DD-MM-YYYY',
//     locale: 'en'

//   });
    $(document).ready(function(){
        $.validator.addMethod("lessThan", function (value, element, param) {
                return parseInt(value) < parseInt($(param).val());
            }, "Minimum value must be less than maximum value");
        $('#employerJobForm').validate({
            rules: {
                job_title : {
                    required: true,
                    maxlength :50,
                },
                job_description : {
                    required: true,

                },
                location : {
                    required: true,

                },
                experience_sf : {
                    required: true,

                },
                experience_without_sf : {
                    required: true,

                },
                license_candidate_no_experience : {
                    required: true,

                },
                job_role : {
                    required: true,

                },
                total_number_of_working_days : {
                    required: true,

                },
                job_shift : {
                    required: true,

                },
                job_type : {
                    required: true,

                },
                minimum_pay_per_hour : {
                    required: true,


                },
                maximum_pay_per_hour : {
                    required: true,
                    greaterThan: "#minimum_pay_per_hour"

                },
                bonus_commission : {
                    required: true,

                },
                any_other_langauge : {
                    required: true,

                },
                other_any_other_langauge:{
                    required: function(element){
                            return $("#any_other_langauge").val()=="Other";
                        }
                },
                license_requirement: {
                    required: function(element){
                            return $("#experience_sf").val()=="Yes";
                        }
                },
                other_license_requirement: {
                    required: function(element){
                            return $("#license_requirement").val()=="Other";
                        }
                },
                job_start_date : {
                    required: true,
                    // dateFormat: dateITA

                },
                number_of_vacancies : {
                    required: true,

                },
                job_recruiment_duration : {
                    required: true,
                    min: 1,
                    digits : true,

                }

            },

            messages: {
                job_title : {
                    required : "Please enter job title.",
                    maxlength : "Please enter no more than 80 characters."
                },
                job_description : {
                    required : "Please enter job description.",
                },
                location : {
                    required : "Please enter job location.",
                },
                experience_sf : {
                    required : "Please select insurance experience with State Farm.",
                },
                experience_sf : {
                    required : "Please select insurance experience without State Farm.",
                },
                license_candidate_no_experience : {
                    required : "Please select License candidate without insurance Experience.",
                },
                job_role : {
                    required : "Please select job role.",
                },
                total_number_of_working_days : {
                    required : "Please select job role.",
                },
                job_shift: {
                    required : "Please select job Work type.",
                },
                job_type : {
                    required : "Please select job type.",
                },
                minimum_pay_per_hour : {
                    required : "Please enter minimum pay per hour.",
                },
                maximum_pay_per_hour : {
                    required : "Please enter minimum pay per hour.",
                    greaterThan: "Please enter greater minimum pay per hour value.",
                },
                bonus_commission : {
                    required : "Please enter bonus commission.",
                },
                any_other_langauge : {
                    required : "Please select language.",
                },
                license_requirement : {
                    required : "Please select license requirement.",
                },
                other_license_requirement : {
                    required : "Please select other license requirementt.",
                },
                job_start_date : {
                    required : "Please enter job launch date.",
                },
                number_of_vacancies : {
                    required : "Please enter number of vacancy.",
                },
                job_recruiment_duration : {
                    required: "Please enter Job recruiment duration.",
                    min: "Please enter a value greater than or equal to 1",

                },

            },
            errorPlacement: function ( error, element ) {

                if(element.parent().hasClass('input-group')){
                error.insertAfter( element.parent() );
                }else{
                    error.insertAfter( element );
                }

            },
            submitHandler: function(form) {

                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();

                if(latitude == '' && longitude == ''){
                    $('#location').val('');
                    $('.location-error').css("display", "block");
                    $('.location-error').html("Please select location.");

                    return false;
                }
                $(".data-loader").addClass('loading');

                var submissionPromise = form.submit();

                submissionPromise.done(function () {
                    $(".data-loader").removeClass('loading');
                });

                submissionPromise.fail(function () {
                    $(".data-loader").removeClass('loading');
                });
            },

        });

    });

        $('input').on('change', function() {
            // Your code to handle the 'change' event here
            var id= $(this).attr('id');
            $('#'+id+'-error').css('display','none');
            $('#'+id).removeClass('error');
        });



    </script>

<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/bod/resources/views/admin/customJs/employerJobs/create.blade.php ENDPATH**/ ?>