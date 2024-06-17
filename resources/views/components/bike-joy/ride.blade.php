<?php
use Livewire\Component;

new class () extends Component {
    public $ride;
    public $condense = false;

    public function mount($ride, $condense = false)
    {
        $this->ride = $ride;
        $this->condense = $condense;

    }
};
?>
@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'condense' => false,
    'ride' => null,
])

<div class="{{$condense ? 'm-0 mb-0' : 'm-4 mb-0'}}">
    <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-lg transition-shadow duration-300 flex flex-col {{ $condense ? ' p-0 m-0' : 'lg:flex-row p-8' }}">
        <!-- Map Section -->
        <x-route-map :ride="$ride" :condense="$condense"/>

        <!-- Stats Section -->
        <x-bike-joy.ride-stats :ride="$ride" :condense="$condense"/>
    </div>
</div>
