<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Customer</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customers.update', $customer->id) }}" id="customerForm">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" 
                                       value="{{ old('customer_name', $customer->customer_name) }}" 
                                       required maxlength="255">
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" 
                                       maxlength="11">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('customers.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Customer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $.validator.addMethod("bangladeshPhone", function(value, element) {
                return this.optional(element) || /^01[0-9]{9}$/.test(value);
            }, "Please enter a valid phone number starting with 01");

            $("#customerForm").validate({
                rules: {
                    customer_name: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    phone: {
                        required: true,
                        bangladeshPhone: true
                    }
                },
                messages: {
                    customer_name: {
                        required: "Please enter customer name",
                        minlength: "Name must be at least 3 characters long",
                        maxlength: "Name cannot be longer than 255 characters"
                    },
                    phone: {
                        required: "Please enter phone number"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.mb-3').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            // Clean phone input
            $("#phone").on('input', function() {
                $(this).val($(this).val().replace(/[^0-9]/g, '').substring(0, 11));
            });
        });
    </script>
    @endpush
</x-app-layout>