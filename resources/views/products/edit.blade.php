<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('products.update', $product->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                       id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="product_code" class="form-label">Product Code</label>
                                <input type="text" class="form-control @error('product_code') is-invalid @enderror" 
                                       id="product_code" name="product_code" value="{{ old('product_code', $product->product_code) }}" required>
                                @error('product_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="size" class="form-label">Size</label>
                                <select class="form-select @error('size') is-invalid @enderror" id="size" name="size" required>
                                    <option value="">Select Size</option>
                                    @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                                        <option value="{{ $size }}" {{ old('size', $product->size) == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="purchase_price" class="form-label">Purchase Price</label>
                                <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" 
                                       id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                       id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" required>
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="alert_quantity" class="form-label">Alert Quantity</label>
                                <input type="number" class="form-control @error('alert_quantity') is-invalid @enderror" 
                                       id="alert_quantity" name="alert_quantity" value="{{ old('alert_quantity', $product->alert_quantity) }}" required>
                                @error('alert_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    $(document).ready(function() {
        $("#productForm").validate({
            rules: {
                product_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                product_code: {
                    required: true,
                    remote: {
                        url: "{{ route('products.check-code') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            code: function() {
                                return $("#product_code").val();
                            },
                            id: {{ $product->id }}
                        }
                    }
                },
                size: {
                    required: true
                },
                purchase_price: {
                    required: true,
                    number: true,
                    min: 0
                },
                sale_price: {
                    required: true,
                    number: true,
                    min: 0
                },
                alert_quantity: {
                    required: true,
                    digits: true,
                    min: 0
                }
            },
            messages: {
                product_name: {
                    required: "Please enter product name",
                    minlength: "Name must be at least 3 characters long",
                    maxlength: "Name cannot be longer than 255 characters"
                },
                product_code: {
                    required: "Please enter product code",
                    remote: "This product code is already taken"
                },
                size: {
                    required: "Please select a size"
                },
                purchase_price: {
                    required: "Please enter purchase price",
                    number: "Please enter a valid number",
                    min: "Price cannot be negative"
                },
                sale_price: {
                    required: "Please enter sale price",
                    number: "Please enter a valid number",
                    min: "Price cannot be negative"
                },
                alert_quantity: {
                    required: "Please enter alert quantity",
                    digits: "Please enter a whole number",
                    min: "Quantity cannot be negative"
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
    });
</script>
@endpush