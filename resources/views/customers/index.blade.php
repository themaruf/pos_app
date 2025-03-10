<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Customers</h1>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Customer
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="customers-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
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
            $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("customers.data") }}',
                columns: [
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>