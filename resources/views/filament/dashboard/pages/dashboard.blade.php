<x-filament::page>
<style>

    a.disabled {
  pointer-events: none;
  cursor: default;
}
</style>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ url('/simple') }}" class="block">
            <x-filament::card class="hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <x-fluentui-food-grains-20 class="h-10" />
                    <h2 class="text-xl font-bold">Simple Diet Management</h2>
                </div>
                <p class="py-4 text-sm text-gray-600">Manage simple diet plans and nutrition details.</p>
            </x-filament::card>
        </a>

        <a href="{{ url('advance-orders') }}" class="block disabled">
            <x-filament::card class="hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <x-heroicon-o-document-text class="h-10 text-gray-500" />
                    <h2 class="text-xl font-bold">Advanced Orders</h2>
                </div>
                <p class="py-4 text-sm text-gray-600">Handle complex nutrition orders and schedules.</p>
            </x-filament::card>
        </a>

        <a href="{{ url('reports') }}" class="block disabled">
            <x-filament::card class="hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <x-heroicon-o-chart-bar class="h-10 text-gray-500" />
                    <h2 class="text-xl font-bold">Reports</h2>
                </div>
                <p class="py-4 text-sm text-gray-600">Generate and view detailed nutrition reports.</p>
            </x-filament::card>
        </a>

        <a href="{{ url('/admin') }}" class="block disabled">
            <x-filament::card class="hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <x-heroicon-o-cog class="h-10 text-gray-500" />
                    <h2 class="text-xl font-bold">Admin Settings</h2>
                </div>
                <p class="py-4 text-sm text-gray-600">Customize application settings and preferences.</p>
            </x-filament::card>
        </a>

       
    </div>
</x-filament::page>
