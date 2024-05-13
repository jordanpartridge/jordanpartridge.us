<?php

namespace App\Http\Livewire;

use DebugBar\DataCollector\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Carbon\Carbon;

class DateRangePicker extends Component
{
    public $startDate;
    public $endDate;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->subDays(7)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function render(): View|Factory|Renderable|Application
    {
        return view('livewire.date-range-picker');
    }

    public function updateRange(): void
    {
        $this->emit('dateRangeUpdated', $this->startDate, $this->endDate);
    }

}
