<div
    x-data="{
        activeTab: 'bike',
        tabs: {
            bike: {
                name: 'BASE VEHICLE',
                items: [
                    { id: 1, name: 'FRAME', description: 'Carbon fiber fat bike frame with military-grade durability', image: '/img/bike-frame-placeholder.jpg' },
                    { id: 2, name: 'TIRES', description: '4.8" winter-ready studded fat tires for maximum traction', image: '/img/bike-tire-placeholder.jpg' },
                    { id: 3, name: 'DRIVETRAIN', description: 'SRAM X01 Eagle 12-speed drivetrain with extended range', image: '/img/bike-drivetrain-placeholder.jpg' },
                    { id: 4, name: 'SUSPENSION', description: 'RockShox Bluto RL fat bike-specific front suspension', image: '/img/bike-suspension-placeholder.jpg' }
                ]
            },
            gear: {
                name: 'COMBAT GEAR',
                items: [
                    { id: 5, name: 'HELMET', description: 'Tactical all-mountain helmet with integrated camera mount', image: '/img/bike-helmet-placeholder.jpg' },
                    { id: 6, name: 'LIGHTS', description: 'Military-spec 2500 lumen front light system', image: '/img/bike-light-placeholder.jpg' },
                    { id: 7, name: 'CARGO', description: 'Molle-compatible frame bags for extended missions', image: '/img/bike-bag-placeholder.jpg' },
                    { id: 8, name: 'BIOMETRICS', description: 'Garmin bike computer with heart rate and navigation', image: '/img/bike-computer-placeholder.jpg' }
                ]
            },
            maintenance: {
                name: 'FIELD REPAIR',
                items: [
                    { id: 9, name: 'TOOLS', description: 'Compact multi-tool with chain breaker and tire levers', image: '/img/bike-tool-placeholder.jpg' },
                    { id: 10, name: 'SPARE PARTS', description: 'Essential replacement parts for field repairs', image: '/img/bike-parts-placeholder.jpg' },
                    { id: 11, name: 'PUMP', description: 'High-volume fat bike pump with pressure gauge', image: '/img/bike-pump-placeholder.jpg' },
                    { id: 12, name: 'MEDICAL', description: 'First aid kit for rider and bike emergencies', image: '/img/bike-medical-placeholder.jpg' }
                ]
            }
        }
    }"
    class="bg-gray-900 rounded-xl shadow-lg overflow-hidden camo-border"
>
    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-700">
        <template x-for="(tab, key) in tabs" :key="key">
            <button
                @click="activeTab = key"
                class="px-4 py-3 font-bold military-font text-sm transition-colors duration-300"
                :class="activeTab === key ? 'text-white bg-gray-800 border-t-2 border-green-500' : 'text-gray-400 hover:text-white'"
                x-text="tab.name"
            ></button>
        </template>
    </div>

    <!-- Equipment Content -->
    <div class="bg-gray-800 p-4">
        <template x-for="(tab, key) in tabs" :key="key">
            <div x-show="activeTab === key" class="space-y-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <h3 class="text-xl text-center text-white military-font mb-4" x-text="`${tab.name} LOADOUT`"></h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="item in tab.items" :key="item.id">
                        <div class="bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-600 hover:border-green-600">
                            <div class="h-48 bg-gray-600 flex items-center justify-center">
                                <!-- Placeholder for equipment image -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>

                            <div class="p-4">
                                <h4 class="text-white font-bold military-font" x-text="item.name"></h4>
                                <p class="mt-2 text-gray-300 text-sm" x-text="item.description"></p>

                                <!-- Spec indicators -->
                                <div class="mt-3 flex space-x-2">
                                    <span class="inline-block bg-gray-800 px-2 py-1 rounded-full text-xs text-green-400 font-semibold">DEPLOYED</span>
                                    <span class="inline-block bg-gray-800 px-2 py-1 rounded-full text-xs text-yellow-400 font-semibold">FIELD-TESTED</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>