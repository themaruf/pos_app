<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Products</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Product
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="products-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Size</th>
                            <th>Purchase Price</th>
                            <th>Sale Price</th>
                            <th>Alert Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("products.data") }}',
                columns: [
                    {data: 'product_name', name: 'product_name'},
                    {data: 'product_code', name: 'product_code'},
                    {data: 'size', name: 'size'},
                    {data: 'purchase_price', name: 'purchase_price'},
                    {data: 'sale_price', name: 'sale_price'},
                    {data: 'alert_quantity', name: 'alert_quantity'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>