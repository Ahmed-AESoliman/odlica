<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DualRangeSlider extends Component
{
    public $min;
    public $max;
    public $minValue;
    public $maxValue;

    public function __construct($min = 0, $max = 100, $minValue = null, $maxValue = null)
    {
        $this->min = $min;
        $this->max = $max;
        $this->minValue = $minValue ?? $min;
        $this->maxValue = $maxValue ?? $max;
    }

    public function render()
    {
        return view('components.dual-range-slider');
    }
}
