<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['user', 'quotation']);

        // Filter by status (based on whether quotation exists)
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->doesntHave('quotation');
            } elseif ($request->status === 'quotation_sent') {
                $query->has('quotation');
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort by date
        if ($request->filled('sort')) {
            if ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $prescriptions = $query->get();
        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('prescriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation with better rules
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'delivery_address' => 'required|string|max:500',
            'delivery_time' => 'required|string|in:09:00-11:00,11:00-13:00,13:00-15:00,15:00-17:00',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'images.required' => 'Please upload at least one prescription image.',
            'images.min' => 'Please upload at least one prescription image.',
            'delivery_address.required' => 'Delivery address is required.',
            'delivery_time.required' => 'Please select a delivery time slot.',
            'delivery_time.in' => 'Please select a valid delivery time slot.',
        ]);

        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please login to submit a prescription.');
        }

        $prescription = Prescription::create([
            'user_id' => $request->user()->id,
            'note' => $validated['notes'] ?? null,
            'delivery_address' => $validated['delivery_address'],
            'delivery_time' => $validated['delivery_time'],
        ]);

        $uploadedImages = 0;
        foreach ($request->file('images') as $file) {
            if (!$file->isValid()) {
                continue;
            }

            try {
                $fileName = time() . '_' . $uploadedImages . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('prescriptions/' . $prescription->id, $fileName, 'public');

                Image::create([
                    'prescription_id' => $prescription->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);

                $uploadedImages++;
            } catch (\Exception $e) {
                Log::error('Failed to upload prescription image: ' . $e->getMessage());
            }
        }

        if ($uploadedImages === 0) {
            $prescription->delete();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to upload images. Please try again.');
        }

        return redirect()->route('prescriptions')
            ->with('success', "Prescription submitted successfully with {$uploadedImages} image(s).");
    }

    /**
     * Display the specified resource.
     */
    public function show(Prescription $prescription)
    {
        $prescription->load('images');
        return view('prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prescription $prescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prescription $prescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescription $prescription)
    {
        foreach ($prescription->images as $image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }

        $prescription->delete();

        return redirect()->route('prescriptions')
            ->with('success', 'Prescription deleted successfully.');
    }
}
