<x-filament-panels::page>

<script src="{{ asset('js/dataTables/popper.min.js') }}"></script>
@php

$currentDisplayLang = request('Language') ?? $defaultLanguage ?? 'Eng';

@endphp
 <!-- Breadcrumb System -->
    <nav class="flex items-center justify-between text-sm bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <div class="flex items-center">
            <a href="{{ url('/simple/calender') }}" class="text-blue-500 hover:underline">
                @if($currentDisplayLang === 'Sin')
                    මුල් පිටුව
                @elseif($currentDisplayLang === 'Tam')
                    முகப்பு
                @else
                    Home
                @endif
            </a>
            <span class="mx-2">&gt;</span>
            <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="text-blue-500 hover:underline">{{ $date ?? 
                ($currentDisplayLang === 'Sin' ? 'දිනයක් තෝරා නැත' : 
                ($currentDisplayLang === 'Tam' ? 'தேதி தேர்ந்தெடுக்கப்படவில்லை' : 'No Date Selected')) }}</a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-500 dark:text-gray-400">
                @if($currentDisplayLang === 'Sin')
                    රෝහල් ආහාර පත්‍රිකාව
                @elseif($currentDisplayLang === 'Tam')
                    மருத்துவமனை உணவு தாள்
                @else
                    Hospital Diet Sheet
                @endif
            </span>
        </div>
        <div class="flex gap-2">
            @if($currentDisplayLang !== 'Eng')
                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('Language'), ['Language' => 'Eng'])) }}" class="filament-button filament-button-primary px-3 py-1 rounded-xl" style="background-color: #8D153A; color: #fff; border-color: #8D153A;">English</a>
            @endif
            @if($currentDisplayLang !== 'Sin')
                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('Language'), ['Language' => 'Sin'])) }}" class="filament-button filament-button-primary px-3 py-1 rounded-xl" style="background-color: #FFBE29; color: #000; border-color: #FFBE29;">සිංහල</a>
            @endif
            @if($currentDisplayLang !== 'Tam')
            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('Language'), ['Language' => 'Tam'])) }}" class="filament-button filament-button-primary px-3 py-1 rounded-xl" style="background-color: #00534E; color: #fff; border-color: #00534E;">தமிழ்</a>
            @endif
        </div>

    </nav>

    <!-- Tab Movement Button -->

    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
        <button id="autoPopulateBtn" class="x-4 p-2 text-sm font-medium text-white bg-blue-400 rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2" type="button" style="background-color: #ad1457; color: #ffffff; border-color: #ad1457;">
            @if($currentDisplayLang === 'Sin')
                ස්වයං පුරවන්න 
            @elseif($currentDisplayLang === 'Tam')
                தானாக நிரப்பவும் 
            @else
                Auto Populate
            @endif
        </button>
        </div>

        <!-- Modal -->
                <!-- Modal -->
        <div id="autoPopulateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-xs" style="background-color: #ffffff; color: #000000; border-radius: 0.5rem; box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 20rem;">
            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                @if($currentDisplayLang === 'Sin')
                දිනය තෝරන්න
                @elseif($currentDisplayLang === 'Tam')
                தேதி தேர்ந்தெடுக்கவும்
                @else
                Select Date
                @endif
            </h3>
            <input type="date" id="autoPopulateDate" class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 mb-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.25rem; padding: 0.5rem; margin-bottom: 1rem; background-color: #ffffff; color: #1f2937;">
            <div class="flex justify-end gap-2" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button id="autoPopulateCancel" type="button" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200" style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; background-color: #e5e7eb; color: #1f2937;">
                @if($currentDisplayLang==='Sin')
                    අවලංගු කරන්න
                @elseif($currentDisplayLang==='Tam')
                    ரத்து செய்க
                @else
                    Cancel
                @endif
                </button>
                <button id="autoPopulateConfirm" type="button" class="px-3 py-1 rounded bg-blue-600 text-white" style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; background-color: #2563eb; color: #ffffff;">
                @if($currentDisplayLang==='Sin')
                    තහවුරු කරන්න
                @elseif($currentDisplayLang==='Tam')
                    உறுதிப்படுத்தவும்
                @else
                    Confirm
                @endif
                </button>
            </div>
            </div>
        </div>

        <!-- Clear All Confirmation Modal -->
        <div id="clearAllModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden" >
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md" style="background-color: #ffffff; color: #000000; border-radius: 0.5rem; box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 28rem;">
            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                @if($currentDisplayLang === 'Sin')
                තහවුරු කරන්න
                @elseif($currentDisplayLang === 'Tam')
                உறுதிப்படுத்தவும்
                @else
                Confirm Clear All
                @endif
            </h3>
            <p class="mb-4 text-gray-700 dark:text-gray-300" style="margin-bottom: 1rem; color: #374151;">
                @if($currentDisplayLang === 'Sin')
                {{ $date }} දිනය සඳහා සියලුම දත්ත මකා දැමීමට ඔබට අවශ්‍යද? මෙම ක්‍රියාව අවලංගු කළ නොහැක.
                @elseif($currentDisplayLang === 'Tam')
                {{ $date }} தேதிக்கான அனைத்து தரவையும் நீக்க விரும்புகிறீர்களா? இந்த செயலை மாற்ற முடியாது.
                @else
                Are you sure you want to clear all data for {{ $date }}? This action cannot be undone.
                @endif
            </p>
            <div class="flex justify-end gap-2" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button id="clearAllCancel" type="button" class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200" style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; background-color: #e5e7eb; color: #1f2937;">
                @if($currentDisplayLang==='Sin')
                    අවලංගු කරන්න
                @elseif($currentDisplayLang==='Tam')
                    ரத்து செய்க
                @else
                    Cancel
                @endif
                </button>
                <button id="clearAllConfirm" type="button" class="px-3 py-1 rounded bg-red-600 text-white" style="padding: 0.25rem 0.75rem; border-radius: 0.25rem; background-color: #dc2626; color: #ffffff;">
                @if($currentDisplayLang==='Sin')
                    සියල්ල මකන්න
                @elseif($currentDisplayLang==='Tam')
                    அனைத்தையும் அழிக்கவும்
                @else
                    Clear All
                @endif
                </button>
            </div>
            </div>
        </div>
<div>
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
            @if($currentDisplayLang === 'Sin')
                ටැබ් චලනය:
            @elseif($currentDisplayLang === 'Tam')
                தாவல் இயக்கம்:
            @else
                Tab Movement:
            @endif
        </label>

        <button type="button" id="tabMovementToggle" 
            class="px-4 py-2 text-sm font-medium text-white bg-blue-400 rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2"
            style="background-color: #60A5FA; color: #FFFFFF; border-color: #3B82F6;">
            @if($tabMovement === 'TB')
                @if($currentDisplayLang === 'Sin')
                    තීරු අනුව වෙනස් කරන්න (ඉහළ සිට පහළට)
                @elseif($currentDisplayLang === 'Tam')
                    நெடுவரிசையாக மாற்றவும் (மேலிருந்து கீழாக)
                @else
                    Switch to Column-wise (Top to Bottom)
                @endif
            @else
                @if($currentDisplayLang === 'Sin')
                    පේළි අනුව වෙනස් කරන්න (වමේ සිට දකුණට)
                @elseif($currentDisplayLang === 'Tam')
                    வரிசையாக மாற்றவும் (இடமிருந்து வலமாக)
                @else
                    Switch to Row-wise (Left to Right)
                @endif
            @endif
        </button>
    </div>
    </div>

    <form wire:submit.prevent="save">
        <input type="hidden" wire:model="date" name="date" />
        <div class="overflow-auto relative" id="tableContainer" style="max-height: 70vh;">
            <table class="table-auto border border-gray-300 w-full text-sm">
                <thead>
                    <tr>
                        <th class="border px-2 py-1 bg-gray-100 dark:bg-gray-700 sticky left-0 top-0 z-20" style="min-width: 20ch;">
                            
                            @if($currentDisplayLang === 'Sin')
                                ඒකකය \ ආහාරය
                            @elseif($currentDisplayLang === 'Tam')
                                பிரிவு \ உணவு
                            @else
                                Unit \ Diet
                            @endif
                        </th>
                        @foreach ($diets as $diet)
                            <th class="border px-2 py-1 bg-gray-100 dark:bg-gray-700 sticky top-0 z-10 {{ $loop->index % 2 == 0 ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-green-50 dark:bg-green-900/20' }}">
                                <div>
                                    @if($currentDisplayLang === 'Sin')
                                        {{ $diet->DietName_si }}
                                    @elseif($currentDisplayLang === 'Tam')
                                        {{ $diet->DietName_tm }}
                                    @else
                                        {{ $diet->DietName_en }}
                                    @endif
                                    @if($diet->multiply_values)
                                        <span class="text-red-500" style="color: #ef4444;">*</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">({{ $diet->primary_amount_unit }})</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td class="border px-2 py-1 bg-gray-100 dark:bg-gray-700 font-medium sticky left-0 z-10" style="min-width: 20ch;">{{ $unit->name }}</td>

                            @foreach ($diets as $diet)
                                @php
                                    $unitSteps = [
                                        // Mass units
                                        'mcg' => '0.001', 'mg' => '0.001', 'g' => '0.1', 'kg' => '0.001', 'oz' => '0.01', 'lb' => '0.01', 'st' => '0.01',
                                        // Fluid/volume units
                                        'ml' => '0.1', 'l' => '0.001', 'tsp' => '0.25', 'tbsp' => '0.25', 'fl oz' => '0.01', 'cup' => '0.01', 'pt' => '0.01', 'qt' => '0.01', 'gal' => '0.01',
                                        // Other common units
                                        'piece' => '1', 'serving' => '1', 'slice' => '1', 'portion' => '1', 'drop' => '1', 'pinch' => '1', 'sheet' => '1', 'package' => '1', 'container' => '1', 'can' => '1', 'bottle' => '1', 'jar' => '1', 'bag' => '1', 'box' => '1', 'bar' => '1', 'packet' => '1', 'tube' => '1', 'unit' => '1',
                                    ];
                                    $singularPluralUnits = [
                                        'piece' => 'pieces', 'serving' => 'servings', 'slice' => 'slices', 'portion' => 'portions', 'drop' => 'drops', 'pinch' => 'pinches', 'sheet' => 'sheets', 'package' => 'packages', 'container' => 'containers', 'can' => 'cans', 'bottle' => 'bottles', 'jar' => 'jars', 'bag' => 'bags', 'box' => 'boxes', 'bar' => 'bars', 'packet' => 'packets', 'tube' => 'tubes', 'unit' => 'units',
                                    ];
                                    $stepValue = $unitSteps[$diet->primary_amount_unit] ?? '0.01';
                                    $currentDisplayLang = request('Language') ?? $defaultLanguage ?? 'Eng';
                                @endphp
                                <td class="border px-2 py-1 {{ $loop->index % 2 == 0 ? 'bg-blue-50 dark:bg-blue-900/10' : 'bg-green-50 dark:bg-green-900/10' }} {{ $diet->auto_populate ? 'bg-gray-200 dark:bg-gray-700/60' : '' }}">
                                    <input
                                        type="number"
                                        wire:model.defer="amounts.{{ $unit->id }}.{{ $diet->id }}"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded px-1 py-0.5 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                        step="{{ $stepValue }}"
                                        min="0"
                                        style="min-width: 10ch;"
                                        data-ward="{{ $unit->name }}"
                                        data-diet="@if($currentDisplayLang === 'Sin'){{ $diet->DietName_si }}@elseif($currentDisplayLang === 'Tam'){{ $diet->DietName_tm }}@else{{ $diet->DietName_en }}@endif"
                                    />
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    
                    
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 flex justify-stretch items-center gap-6">
            <x-filament::button class="" type="submit">
                @if($currentDisplayLang === 'Sin')
                    සුරකින්න
                @elseif($currentDisplayLang === 'Tam')
                    சேமிக்கவும்
                @else
                    Save
                @endif
            </x-filament::button>

            <x-filament::button color="danger" id="clearAllBtn" type="button">
                @if($currentDisplayLang === 'Sin')
                    සියල්ල මකන්න
                @elseif($currentDisplayLang === 'Tam')
                    அனைத்தையும் அழிக்கவும்
                @else
                    Clear All
                @endif
            </x-filament::button>


        
    @if(Auth::user() && userHasPermission(Auth::user(), 'view.daily_diet_analysis_calender_simple-panel'))
        <a href="{{ url('/simple/diet-analysis') . '?date=' . urlencode($date) }}" 
        class="p-2 rounded-md border sm:rounded-xl text-white border-pink-800 bg-pink-700 hover:bg-pink-800 hover:border-pink-900"
        style="background-color: #9d174d; border-color: #831843;">
        @if($currentDisplayLang === 'Sin')
            ශ්‍රී ලංකා ජාතික රෝහලේ {{ $date ?? 'දිනයක් තෝරා නැත' }} දිනයේ සම්පූර්ණ ආහාර විශ්ලේෂණයට යන්න
        @elseif($currentDisplayLang === 'Tam')
            {{ $date ?? 'தேதி தேர்ந்தெடுக்கப்படவில்லை' }} அன்று இலங்கை தேசிய மருத்துவமனையின் மொத்த உணவு பகுப்பாய்வுக்கு செல்லவும்
        @else
            Go to Total Diet Analysis of National Hospital of Sri Lanka on {{ $date ?? 'No Date Selected' }}
        @endif
        </a>
    @endif
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto Populate Modal logic
            const autoPopulateBtn = document.getElementById('autoPopulateBtn');
            const autoPopulateModal = document.getElementById('autoPopulateModal');
            const autoPopulateDate = document.getElementById('autoPopulateDate');
            const autoPopulateCancel = document.getElementById('autoPopulateCancel');
            const autoPopulateConfirm = document.getElementById('autoPopulateConfirm');
            if (autoPopulateBtn && autoPopulateModal && autoPopulateDate && autoPopulateCancel && autoPopulateConfirm) {
                autoPopulateBtn.addEventListener('click', function() {
                    // Default to yesterday
                    let today = new Date(@json($date ?? date('Y-m-d')));
                    let yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);
                    autoPopulateDate.value = yesterday.toISOString().slice(0, 10);
                    autoPopulateModal.classList.remove('hidden');
                });
                autoPopulateCancel.addEventListener('click', function() {
                    autoPopulateModal.classList.add('hidden');
                });
                autoPopulateConfirm.addEventListener('click', function() {
                    autoPopulateModal.classList.add('hidden');
                    // Call Livewire with selected date
                    if (window.Livewire) {
                        window.Livewire.find(@json($this->getId())).call('autoPopulateFromYesterday', autoPopulateDate.value);
                    }
                });
            }

            // Clear All Modal logic
            const clearAllBtn = document.getElementById('clearAllBtn');
            const clearAllModal = document.getElementById('clearAllModal');
            const clearAllCancel = document.getElementById('clearAllCancel');
            const clearAllConfirm = document.getElementById('clearAllConfirm');
            if (clearAllBtn && clearAllModal && clearAllCancel && clearAllConfirm) {
                clearAllBtn.addEventListener('click', function() {
                    clearAllModal.classList.remove('hidden');
                });
                clearAllCancel.addEventListener('click', function() {
                    clearAllModal.classList.add('hidden');
                });
                clearAllConfirm.addEventListener('click', function() {
                    clearAllModal.classList.add('hidden');
                    // Call Livewire to clear all data
                    if (window.Livewire) {
                        window.Livewire.find(@json($this->getId())).call('clearAll');
                    }
                });
            }
            let tabMovement = @json($tabMovement === 'LR'); // Convert LR/TB to boolean
            let moveCount = 0;
            let hasUnsavedChanges = false;
            const currentLang = @json($currentDisplayLang);
            const inputs = document.querySelectorAll('input[type="number"]');
            const toggleButton = document.getElementById('tabMovementToggle');
            const toggleSlider = document.getElementById('toggleSlider');
            const toggleLabel = document.getElementById('toggleLabel');
            const tableContainer = document.getElementById('tableContainer');
            
            // Language-specific messages
            const messages = {
                'Sin': {
                    unsavedChanges: 'ඔබට නොසුරකින ලද වෙනස්කම් ඇත. ඔබට පිටවීමට අවශ්‍යද?',
                    ward: 'වාට්ටුව',
                    diet: 'ආහාරය'
                },
                'Tam': {
                    unsavedChanges: 'உங்களிடம் சேமிக்கப்படாத மாற்றங்கள் உள்ளன. நீங்கள் நிச்சயமாக வெளியேற விரும்புகிறீர்களா?',
                    ward: 'பிரிவு',
                    diet: 'உணவு'
                },
                'Eng': {
                    unsavedChanges: 'You have unsaved changes. Are you sure you want to leave?',
                    ward: 'Ward',
                    diet: 'Diet'
                }
            };
            
            const currentMessages = messages[currentLang] || messages['Eng'];
            
            // Initialize frozen table functionality
            function initializeFrozenTable() {
                if (tableContainer) {
                    // Add scroll shadow effects
                    tableContainer.addEventListener('scroll', function() {
                        const scrollLeft = this.scrollLeft;
                        const scrollTop = this.scrollTop;
                        
                        // Add shadow to first column when scrolling horizontally
                        const firstColumnCells = this.querySelectorAll('th:first-child, td:first-child');
                        firstColumnCells.forEach(cell => {
                            if (scrollLeft > 0) {
                                cell.style.boxShadow = '2px 0 5px rgba(0,0,0,0.1)';
                            } else {
                                cell.style.boxShadow = 'none';
                            }
                        });
                        
                        // Add shadow to header row when scrolling vertically
                        const headerCells = this.querySelectorAll('thead th');
                        headerCells.forEach(cell => {
                            if (scrollTop > 0) {
                                cell.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
                            } else {
                                cell.style.boxShadow = 'none';
                            }
                        });
                        
                        // Special handling for top-left cell
                        const topLeftCell = this.querySelector('thead th:first-child');
                        if (topLeftCell) {
                            let shadow = '';
                            if (scrollLeft > 0 && scrollTop > 0) {
                                shadow = '2px 2px 5px rgba(0,0,0,0.15)';
                            } else if (scrollLeft > 0) {
                                shadow = '2px 0 5px rgba(0,0,0,0.1)';
                            } else if (scrollTop > 0) {
                                shadow = '0 2px 5px rgba(0,0,0,0.1)';
                            }
                            topLeftCell.style.boxShadow = shadow;
                        }
                    });
                }
            }
            
            // Initialize frozen table
            initializeFrozenTable();
            
            // Create tooltip element
            const tooltip = document.createElement('div');
            tooltip.className = 'px-2 py-1 text-sm text-white rounded shadow-lg z-50 max-w-xs';
            tooltip.style.background = 'linear-gradient(90deg, #23000aff 0%, #011f1dff 100%)';
            tooltip.style.border = '1px solid #FFBE29';
            tooltip.style.display = 'none';
            tooltip.setAttribute('role', 'tooltip');
            document.body.appendChild(tooltip);
            
            let currentPopperInstance = null;
            
            // Prevent leaving page with unsaved changes
            window.addEventListener('beforeunload', function(e) {
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = currentMessages.unsavedChanges;
                    return currentMessages.unsavedChanges;
                }
            });
            
            // Listen for form submission to reset unsaved changes flag
            document.querySelector('form').addEventListener('submit', function() {
                hasUnsavedChanges = false;
            });
            
            // Listen for Livewire events to reset unsaved changes flag
            window.addEventListener('livewire:load', function () {
                Livewire.on('saved', function () {
                    hasUnsavedChanges = false;
                });
            });
            
            // Toggle button click handler
            toggleButton.addEventListener('click', function() {
                // Get current URL and update tabmove parameter
                const currentUrl = new URL(window.location.href);
                const newTabMove = tabMovement ? 'TB' : 'LR'; // Toggle between TB and LR
                currentUrl.searchParams.set('tabmove', newTabMove);
                
                // Save user preference via Livewire before refreshing
                @this.call('updateUserMovementPreference', newTabMove).then(() => {
                    // Refresh page with new parameter after saving preference
                    window.location.href = currentUrl.toString();
                });
            });
            
            // Function to show tooltip
            function showTooltip(input) {
                const ward = input.dataset.ward;
                const diet = input.dataset.diet;
                
                if (ward && diet) {
                    tooltip.textContent = `${currentMessages.ward}: ${ward} | ${currentMessages.diet}: ${diet}`;
                    tooltip.style.display = 'block';
                    
                    if (currentPopperInstance) {
                        currentPopperInstance.destroy();
                    }
                    
                    currentPopperInstance = Popper.createPopper(input, tooltip, {
                        placement: 'top',
                        modifiers: [
                            {
                                name: 'offset',
                                options: {
                                    offset: [0, 8],
                                },
                            },
                            {
                                name: 'preventOverflow',
                                options: {
                                    boundary: 'viewport',
                                },
                            },
                        ],
                    });
                }
            }
            
            // Function to hide tooltip
            function hideTooltip() {
                tooltip.style.display = 'none';
                if (currentPopperInstance) {
                    currentPopperInstance.destroy();
                    currentPopperInstance = null;
                }
            }
            
            // Function to attach events to an input
            function attachEvents(input) {
                // Track changes to inputs
                input.addEventListener('input', function() {
                    hasUnsavedChanges = true;
                });
                
                // Tooltip events - only show on focus (selected input), not on mouse hover
                input.addEventListener('focus', () => showTooltip(input));
                input.addEventListener('blur', hideTooltip);
                
                // Tab movement and arrow key navigation
                input.addEventListener('keydown', function(e) {
                    const allInputs = document.querySelectorAll('input[type="number"]');
                    const currentIndex = Array.from(allInputs).indexOf(input);
                    const table = input.closest('table');
                    const rows = table.querySelectorAll('tbody tr');
                    const cols = table.querySelectorAll('thead th').length - 1; // Exclude first column
                    
                    const currentRow = Math.floor(currentIndex / cols);
                    const currentCol = currentIndex % cols;
                    
                    let nextIndex = -1;
                    
                    if (e.key === 'Tab' && !e.shiftKey) {
                        e.preventDefault();
                        
                        if (tabMovement) {
                            // Move left to right (row by row)
                            nextIndex = currentIndex + 1;
                        } else {
                            // Move top to bottom in columns
                            if (currentRow < rows.length - 1) {
                                // Move down in same column
                                nextIndex = currentIndex + cols;
                            } else {
                                // Move to first row of next column
                                nextIndex = currentCol + 1;
                                // If we're at the last column, wrap to first column of first row
                                if (nextIndex >= cols) {
                                    nextIndex = 0;
                                }
                            }
                        }
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        // Move to next column in same row
                        if (currentCol < cols - 1) {
                            nextIndex = currentIndex + 1;
                        } else {
                            // Wrap to first column of next row
                            if (currentRow < rows.length - 1) {
                                nextIndex = (currentRow + 1) * cols;
                            }
                        }
                    } else if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        // Move to previous column in same row
                        if (currentCol > 0) {
                            nextIndex = currentIndex - 1;
                        } else {
                            // Wrap to last column of previous row
                            if (currentRow > 0) {
                                nextIndex = (currentRow * cols) - 1;
                            }
                        }
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        // Move down in same column
                        if (currentRow < rows.length - 1) {
                            nextIndex = currentIndex + cols;
                        } else {
                            // Wrap to first row of next column
                            if (currentCol < cols - 1) {
                                nextIndex = currentCol + 1;
                            } else {
                                // If at last column, wrap to first column first row
                                nextIndex = 0;
                            }
                        }
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        // Move up in same column
                        if (currentRow > 0) {
                            nextIndex = currentIndex - cols;
                        } else {
                            // Wrap to last row of previous column
                            if (currentCol > 0) {
                                nextIndex = ((rows.length - 1) * cols) + (currentCol - 1);
                            } else {
                                // If at first column, wrap to last column last row
                                nextIndex = (rows.length * cols) - 1;
                            }
                        }
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        
                        if (tabMovement) {
                            // Move left to right (row by row) - same as Tab
                            nextIndex = currentIndex + 1;
                        } else {
                            // Move top to bottom in columns - same as Tab
                            if (currentRow < rows.length - 1) {
                                // Move down in same column
                                nextIndex = currentIndex + cols;
                            } else {
                                // Move to first row of next column
                                nextIndex = currentCol + 1;
                                // If we're at the last column, wrap to first column of first row
                                if (nextIndex >= cols) {
                                    nextIndex = 0;
                                }
                            }
                        }
                    }
                    
                    if (nextIndex >= 0 && nextIndex < allInputs.length) {
                        allInputs[nextIndex].focus();
                        
                        // Increment move count and auto-save after 5 moves
                        moveCount++;
                        if (moveCount >= 5) {
                            moveCount = 0;
                            hasUnsavedChanges = false; // Reset flag after auto-save
                            // Trigger auto-save via Livewire
                            @this.call('autoSave');
                        }
                    }
                });
            }
            
            // Attach events to all current inputs
            inputs.forEach((input) => {
                attachEvents(input);
            });
            
            // Observer to handle dynamically added inputs
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            const newInputs = node.querySelectorAll('input[type="number"]');
                            newInputs.forEach((input) => {
                                attachEvents(input);
                            });
                        }
                    });
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>

</x-filament-panels::page>
