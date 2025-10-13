<x-filament-panels::page>
    <form wire:submit="save">
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Application Settings</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Configure your application settings and preferences.</p>
                </div>
                
                <div class="space-y-6">
                    {{ $this->form }}
                </div>
                
                <div class="mt-6 flex justify-end">
                    <x-filament::button 
                        type="submit" 
                        icon="heroicon-m-check"
                        class="bg-primary-600 hover:bg-primary-700 text-white"
                    >
                        Save Settings
                    </x-filament::button>
                </div>
            </div>
        </div>
    </form>
</x-filament-panels::page>
