<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sales History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="sales-table">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Details Modal -->
    <div class="modal fade" id="saleDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sale Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#sales-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("sales.index") }}',
                columns: [
                    { data: 'invoice_number', name: 'invoice_number' },
                    { 
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD MMM, YYYY hh:mm A');
                        }
                    },
                    { 
                        data: 'customer.customer_name',
                        name: 'customer.customer_name'
                    },
                    { data: 'items_count', name: 'items_count' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>