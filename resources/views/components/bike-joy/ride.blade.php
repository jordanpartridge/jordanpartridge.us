<?php
use Livewire\Component;

new class () extends Component {
    public $ride;
};
?>
<div class="w-full m-4 mb-0">
    <div
        class="bg-gray-50 dark:bg-gray-900 p-8 rounded-2xl shadow-lg transition-shadow duration-300 flex flex-col lg:flex-row">
        <!-- Map Section -->

        <x-route-map :ride="$ride"/>
        <!-- Stats Section -->
        <x-bike-joy.ride-stats :ride="$ride"></x-bike-joy.ride-stats>
    </div>
</div>
