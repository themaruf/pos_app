<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">{{ $title }}</h2>
    @isset($action)
        {{ $action }}
    @endisset
</div>