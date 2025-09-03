<?php

namespace App\Livewire;

use Livewire\Component;

class DropZone extends Component
{
    public $name = 'file';
    public $maxFiles = 1;
    public $maxSize = 5242880; // 5MB
    public $hint = 'Accepted formats: JPG, PNG. Max :count files.';
    public $icon = 'photo';
    public $accept = 'image/*';
    public $multiple = false;
    public $heightClass = 'h-40';

    public function mount($name = null, $maxFiles = null, $maxSize = null, $hint = null, $icon = null, $accept = null, $multiple = null, $heightClass = null)
    {
        if ($name !== null) $this->name = $name;
        if ($maxFiles !== null) $this->maxFiles = (int) $maxFiles;
        if ($maxSize !== null) $this->maxSize = (int) $maxSize;
        if ($hint !== null) $this->hint = $hint;
        if ($icon !== null) $this->icon = $icon;
        if ($accept !== null) $this->accept = $accept;
        if ($multiple !== null) $this->multiple = filter_var($multiple, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $this->multiple;
        if ($heightClass !== null) $this->heightClass = $heightClass;

        if (is_string($this->name) && str_ends_with($this->name, '[]')) {
            $this->multiple = true;
        }
    }

    public function render()
    {
        return view('livewire.drop-zone');
    }
}
