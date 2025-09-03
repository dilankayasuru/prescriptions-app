<?php

namespace App\Livewire;

use App\Services\NvidiaNIMService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OCRPrescriptons extends Component
{
    public $images = [];
    public $extractedMedicines = [];

    public $isLoading = false;

    public $errorMessage = '';

    public function extractMedicine()
    {
        // Reset error message
        $this->errorMessage = '';

        // Validate that images exist
        if (count($this->images) === 0) {
            $this->errorMessage = 'Please upload images before extracting medicines.';
            return;
        }

        try {
            $nvidiaService = new NvidiaNIMService();

            foreach ($this->images as $image) {
                try {
                    $extractedData = $nvidiaService->extractFromImage($image->file_path);
                    if (is_array($extractedData)) {
                        $this->extractedMedicines = array_merge($this->extractedMedicines, $extractedData);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to extract data from image: ' . $e->getMessage());
                    $this->errorMessage = 'Error extracting data from image: ' . $e->getMessage();
                    break; // Stop processing if an error occurs
                }
            }
        } catch (\Exception $e) {
            Log::error('General error in extractMedicine: ' . $e->getMessage());
            $this->errorMessage = 'An unexpected error occurred. Please try again.';
        }
    }

    public function clearError()
    {
        $this->errorMessage = '';
    }

    public function resetExtraction()
    {
        $this->extractedMedicines = [];
        $this->errorMessage = '';
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.o-c-r-prescriptons');
    }
}
