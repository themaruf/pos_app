<div class="bg-white border-end" style="width: 280px;">
    <div class="p-4">
        <div class="list-group list-group-flush">
            <a href="{{ route('customers.index') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Customers
            </a>
            <a href="{{ route('products.index') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="fas fa-box me-2"></i> Products
            </a>
            <a href="{{ route('pos.index') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                <i class="fas fa-cash-register me-2"></i> POS
            </a>
            <a href="{{ route('sales.index') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart me-2"></i> Sales History
            </a>
        </div>
    </div>
</div>