
<form class="contact-form form-area send_query_form" id="contactForm" action="#" name="#" enctype="multipart/form-data">
    @csrf

    {{-- Name --}}
    <div class="form-group">
        <input
            type="text"
            name="name"
            id="contact-name"
            class="form-input @error('name') is-invalid @enderror"
            placeholder="Name"
            value="{{ old('name') }}"
            required
        >
        <span class="error-message" id="name-error">
            @error('name') {{ $message }} @enderror
        </span>
    </div>

    {{-- Email --}}
    <div class="form-group">
        <input
            type="email"
            name="email"
            id="contact-email"
            class="form-input @error('email') is-invalid @enderror"
            placeholder="Email"
            value="{{ old('email') }}"
            required
        >
        <span class="error-message" id="email-error">
            @error('email') {{ $message }} @enderror
        </span>
    </div>
    <input type="hidden" name="query_type" value="1">

    {{-- Mobile --}}
    <div class="form-group">
        <input
            type="tel"
            name="mobile"
            id="contact-mobile"
            class="form-input @error('mobile') is-invalid @enderror"
            placeholder="Mobile"
            value="{{ old('mobile') }}"
            required
        >
        <span class="error-message" id="mobile-error">
            @error('mobile') {{ $message }} @enderror
        </span>
    </div>

    {{-- Submit Button --}}
    <button type="submit" id="contactBtn" class="submit-button submit-btn">Submit</button>
</form>


@push('scripts')
<script>

    (function($){
        "use strict";

        $(document).ready(function() {

            $('#contactForm').on('submit', function(event) {
                event.preventDefault();
                $("#contactBtn").prop('disabled', true);
                $('#contactBtn').text('{{ __('common.submitting') }}');

                var formElement = $(this).serializeArray()
                var formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name, element.value);
                });

                if($('.custom_file').length > 0){
                    let photo = $('.custom_file')[0].files[0];
                    if (photo) {
                        formData.append($('.custom_file').attr('name'), photo)
                    }
                }

                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('contactcustom.store') }}",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {
                        toastr.success("{{__('defaultTheme.message_sent_successfully')}}","{{__('common.success')}}");
                        $("#contactBtn").prop('disabled', false);
                        $('#contactBtn').text("Submit");
                        resetErrorData();

                    },
                    error: function(response) {
                        toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        $("#contactBtn").prop('disabled', false);
                        $('#contactBtn').text("Submit");
                        showErrorData(response.responseJSON.errors)

                    }
                });
            });

            function showErrorData(errors){
                $('#contactForm #error_name').text(errors.name);
                $('#contactForm #error_email').text(errors.email);
                $('#contactForm #error_query_type').text(errors.query_type);
                $('#contactForm #error_message').text(errors.message);
            }

            function resetErrorData(){
                $('#contactForm')[0].reset();
                $('#contactForm #error_name').text('');
                $('#contactForm #error_email').text('');
                $('#contactForm #error_query_type').text('');
                $('#contactForm #error_message').text('');
            }
        });
    })(jQuery);


</script>
@endpush