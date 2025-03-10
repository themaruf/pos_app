<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <!-- Left Side - Cart -->
            <div class="col-lg-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer" class="form-label">Select Customer</label>
                            <select class="form-select" id="customer" name="customer">
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }} - {{ $customer->phone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm" id="cart-table">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Cart items will be dynamically added here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total Amount:</h5>
                                <h5 id="total">0.00</h5>
                            </div>

                            <button class="btn btn-primary w-100" id="checkout-btn">
                                Proceed to Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Products -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="product-search" placeholder="Search products...">
                        </div>
                        <div class="row g-3" id="products-grid">
                            @foreach($products as $product)
                            <div class="col-md-4 col-lg-3">
                                <div class="card product-card h-100">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="card-title mb-1">{{ $product->product_name }}</h6>
                                        <p class="card-text small mb-1">{{ $product->product_code }}</p>
                                        <p class="card-text mb-2">Size: {{ $product->size }}</p>
                                        <p class="card-text fw-bold">৳{{ number_format($product->sale_price, 2) }}</p>
                                        <button class="btn btn-sm btn-outline-primary add-to-cart w-100" 
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->product_name }}"
                                                data-price="{{ $product->sale_price }}">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Total Amount</label>
                        <input type="text" class="form-control" id="payment-total" readonly>
                    </div>
                    
                    <div id="payment-methods">
                        <div class="payment-method-row mb-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <select class="form-select payment-type">
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" class="form-control payment-amount" placeholder="Amount">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-danger remove-payment" type="button">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-secondary mb-3" id="add-payment-method">
                        Add Payment Method
                    </button>

                    <div class="alert alert-info" id="remaining-amount">
                        Remaining: ৳0.00
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="complete-sale">Complete Sale</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .product-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        #cart-table thead {
            background-color: white;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .modal-content, .modal-content * {
                visibility: visible;
            }
            .modal-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 15px;
            }
            .modal-footer, .btn-close {
                display: none !important;
            }
            .modal {
                position: absolute !important;
                left: 0;
                top: 0;
                margin: 0;
                padding: 0;
                min-height: 100%;
                background: #fff;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        $(document).ready(function() {
            let cart = [];

            $('.add-to-cart').click(function() {
                const product = {
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    price: $(this).data('price'),
                    quantity: 1
                };

                const existingItem = cart.find(item => item.id === product.id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push(product);
                }

                updateCart();
            });

            function updateCart() {
                const tbody = $('#cart-table tbody');
                tbody.empty();

                let total = 0;

                cart.forEach((item, index) => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;

                    tbody.append(`
                        <tr>
                            <td>${item.name}</td>
                            <td>৳${item.price.toFixed(2)}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm quantity-input" 
                                       value="${item.quantity}" min="1" style="width: 60px"
                                       data-index="${index}">
                            </td>
                            <td>৳${itemTotal.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });

                $('#total').text('৳' + total.toFixed(2));
                $('#payment-total').val('৳' + total.toFixed(2));
                updateRemainingAmount();
            }

            $('#add-payment-method').click(function() {
                const newRow = $('.payment-method-row:first').clone();
                newRow.find('input').val('');
                $('#payment-methods').append(newRow);
                updateRemainingAmount();
            });

            $(document).on('click', '.remove-payment', function() {
                if ($('.payment-method-row').length > 1) {
                    $(this).closest('.payment-method-row').remove();
                }
                updateRemainingAmount();
            });

            // Update remaining amount
            function updateRemainingAmount() {
                const total = parseFloat($('#total').text().replace('৳', ''));
                let paidAmount = 0;

                $('.payment-amount').each(function() {
                    paidAmount += parseFloat($(this).val()) || 0;
                });

                const remaining = total - paidAmount;
                const label = remaining < 0 ? 'Change Amount' : 'Remaining';
                $('#remaining-amount').html(`${label}: ৳${Math.abs(remaining).toFixed(2)}`);
            }

            // Monitor payment amount changes
            $(document).on('input', '.payment-amount', updateRemainingAmount);

            // Update quantity
            $(document).on('change', '.quantity-input', function() {
                const index = $(this).data('index');
                cart[index].quantity = parseInt($(this).val());
                updateCart();
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                const index = $(this).data('index');
                cart.splice(index, 1);
                updateCart();
            });

            // Product search
            $('#product-search').on('input', function() {
                const search = $(this).val().toLowerCase();
                $('.product-card').each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).closest('.col-md-4').toggle(text.includes(search));
                });
            });

            // Payment method change
            $('#payment-method').change(function() {
                if ($(this).val() === 'cash') {
                    $('#cash-payment-fields').show();
                    $('#other-payment-fields').hide();
                } else {
                    $('#cash-payment-fields').hide();
                    $('#other-payment-fields').show();
                }
            });

            // Calculate change
            $('#received-amount').on('input', function() {
                const received = parseFloat($(this).val()) || 0;
                const total = parseFloat($('#total').text().replace('৳', ''));
                const change = received - total;
                $('#change-amount').val(change >= 0 ? '৳' + change.toFixed(2) : '');
            });

            // Complete Sale
            $('#complete-sale').click(function() {
                if (!$('#customer').val()) {
                    alert('Please select a customer');
                    return;
                }

                const total = parseFloat($('#total').text().replace('৳', ''));
                let paidAmount = 0;
                const payments = [];

                $('.payment-method-row').each(function() {
                    const amount = parseFloat($(this).find('.payment-amount').val()) || 0;
                    if (amount > 0) {
                        payments.push({
                            type: $(this).find('.payment-type').val(),
                            amount: amount
                        });
                        paidAmount += amount;
                    }
                });

                const remaining = total - paidAmount;
                if (remaining <= 0 && cart.length > 0) {
                    const saleData = {
                        customer_id: $('#customer').val(),
                        items: cart,
                        payments: payments,
                        total_amount: total,
                        paid_amount: paidAmount,
                        change_amount: Math.abs(remaining)
                    };

                    // Save sale
                    $.ajax({
                        url: '{{ route("pos.store") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            sale: saleData
                        },
                        success: function(response) {
                            if (response.success) {
                                const paymentModal = document.getElementById('paymentModal');
                                const bsModal = window.bootstrap.Modal.getInstance(paymentModal);
                                if (bsModal) {
                                    bsModal.hide();
                                }
                                
                                // Open receipt in new window and print
                                const printWindow = window.open(
                                    `/pos/${response.sale_id}/receipt`,
                                    'ReceiptWindow',
                                    'width=800,height=600,menubar=no,toolbar=no,location=no,status=no'
                                );
                                
                                if (printWindow) {
                                    printWindow.onload = function() {
                                        printWindow.print();
                                    };
                                }
                                
                                // Reset cart and form
                                cart = [];
                                updateCart();
                                $('#customer').val('').trigger('change');
                            }
                        },
                        error: function(xhr) {
                            alert('Error saving sale. Please try again.');
                        }
                    });
                }
            });
            
            $('#checkout-btn').click(function(e) {
                const total = parseFloat($('#total').text().replace('৳', '')) || 0;
                const customerId = $('#customer').val();

                if (total === 0) {
                    alert('Please add items to cart');
                    return;
                }

                if (!customerId) {
                    alert('Please select a customer');
                    return;
                }

                const paymentModal = document.getElementById('paymentModal');
                const modal = new window.bootstrap.Modal(paymentModal);
                modal.show();
            });
        });
    </script>
    @endpush
</x-app-layout>