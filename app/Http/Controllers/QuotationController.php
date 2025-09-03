<?php

namespace App\Http\Controllers;

use App\Mail\QuotationReady;
use App\Mail\QuotationStatusChanged;
use App\Models\Quotation;
use App\Models\Prescription;
use App\Models\MedicineQuotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->userRole === "admin") {
            $quotations = Quotation::with(['prescription.user', 'medicineQuotations'])->get();
            return view('quotations.index', compact('quotations'));
        } else {
            $quotations = Quotation::with(['prescription.user', 'medicineQuotations'])
                ->whereHas('prescription', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get();
            return view('quotations.index', compact('quotations'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $selectedPrescriptionId = $request->get('prescription_id');
        $prescription = Prescription::with(['user', 'images'])->find($selectedPrescriptionId);
        Log::info('Creating quotation for prescription ID: ' . $selectedPrescriptionId);

        return view('quotations.create', compact('prescription'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'total_price' => 'required|numeric|min:0',
            'medicines' => 'required|array|min:1',
            'medicines.*.name' => 'required|string|max:255',
            'medicines.*.dosage' => 'required|string|max:255',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
        ], [
            'medicines.required' => 'At least one medicine is required.',
            'medicines.*.name.required' => 'Medicine name is required for all entries.',
            'medicines.*.dosage.required' => 'Dosage is required for all medicine entries.',
            'medicines.*.quantity.required' => 'Quantity is required for all medicine entries.',
            'medicines.*.quantity.min' => 'Quantity must be at least 1.',
            'medicines.*.price.required' => 'Price is required for all medicine entries.',
            'medicines.*.price.min' => 'Price must be 0 or greater.',
        ]);

        // Check if prescription exists and user has permission
        $prescription = Prescription::findOrFail($request->prescription_id);

        // Check authorization - only admin can create quotations
        if (Auth::user()->userRole !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Check if quotation already exists for this prescription
        if ($prescription->quotation) {
            return back()->withErrors(['error' => 'A quotation already exists for this prescription.'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the quotation
            $quotation = Quotation::create([
                'prescription_id' => $request->prescription_id,
                'total_price' => $request->total_price,
                'status' => 'pending',
            ]);

            // Create medicine quotations
            foreach ($request->medicines as $medicine) {
                MedicineQuotation::create([
                    'quotation_id' => $quotation->id,
                    'name' => $medicine['name'],
                    'dosage' => $medicine['dosage'],
                    'quantity' => $medicine['quantity'],
                    'unit_price' => $medicine['price'],
                ]);
            }

            DB::commit();

            $quotation->load(['prescription.user', 'medicineQuotations']);

            try {
                Mail::to($quotation->prescription->user->email)->send(new QuotationReady($quotation));
                Log::info('Quotation email sent successfully to: ' . $quotation->prescription->user->email . ' for quotation ID: ' . $quotation->id);
            } catch (\Exception $emailException) {
                Log::error('Failed to send quotation email: ' . $emailException->getMessage(), [
                    'quotation_id' => $quotation->id,
                    'user_email' => $quotation->prescription->user->email,
                    'error' => $emailException->getMessage()
                ]);
            }

            Log::info('Quotation created successfully for prescription ID: ' . $request->prescription_id . ' by user ID: ' . Auth::id());

            return redirect()->route('quotations')->with('success', 'Quotation created and sent to patient successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create quotation: ' . $e->getMessage(), [
                'prescription_id' => $request->prescription_id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['error' => 'Failed to create quotation. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quotation $quotation)
    {
        // Check authorization
        if (Auth::user()->userRole !== 'admin' && $quotation->prescription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $quotation->load(['prescription.user', 'prescription.images', 'medicineQuotations']);

        return view('quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation)
    {
        // Check authorization
        if (Auth::user()->userRole !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $quotation->load(['prescription.user', 'medicineQuotations']);

        return view('quotations.edit', compact('quotation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
        // Check authorization - only admin can update quotations
        if (Auth::user()->userRole !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $request->validate([
            'total_price' => 'required|numeric|min:0',
            'medicines' => 'required|array|min:1',
            'medicines.*.name' => 'required|string|max:255',
            'medicines.*.dosage' => 'required|string|max:255',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
        ], [
            'medicines.required' => 'At least one medicine is required.',
            'medicines.*.name.required' => 'Medicine name is required for all entries.',
            'medicines.*.dosage.required' => 'Dosage is required for all medicine entries.',
            'medicines.*.quantity.required' => 'Quantity is required for all medicine entries.',
            'medicines.*.quantity.min' => 'Quantity must be at least 1.',
            'medicines.*.price.required' => 'Price is required for all medicine entries.',
            'medicines.*.price.min' => 'Price must be 0 or greater.',
        ]);

        try {
            DB::beginTransaction();

            // Update the quotation
            $quotation->update([
                'total_price' => $request->total_price,
            ]);

            // Delete existing medicine quotations
            $quotation->medicineQuotations()->delete();

            // Create new medicine quotations
            foreach ($request->medicines as $medicine) {
                MedicineQuotation::create([
                    'quotation_id' => $quotation->id,
                    'name' => $medicine['name'],
                    'dosage' => $medicine['dosage'],
                    'quantity' => $medicine['quantity'],
                    'unit_price' => $medicine['price'],
                ]);
            }

            DB::commit();

            Log::info('Quotation updated successfully for quotation ID: ' . $quotation->id . ' by user ID: ' . Auth::id());

            return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update quotation: ' . $e->getMessage(), [
                'quotation_id' => $quotation->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['error' => 'Failed to update quotation. Please try again.'])->withInput();
        }
    }

    /**
     * Update the status of the quotation.
     */
    public function updateStatus(Request $request, Quotation $quotation)
    {
        // Check authorization - only the owner of the prescription can update status
        if ($quotation->prescription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Only allow updating if current status is pending
        if ($quotation->status !== 'pending') {
            return back()->withErrors(['error' => 'Quotation status can only be changed from pending status.']);
        }

        try {
            $quotation->update([
                'status' => $request->status,
            ]);

            // Load relationships for email
            $quotation->load(['prescription.user', 'medicineQuotations']);

            // Send email notification to admin users
            try {
                $adminUsers = User::where('userRole', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    Mail::to($admin->email)->send(new QuotationStatusChanged($quotation));
                }
                Log::info('Status change notification emails sent to admin users for quotation ID: ' . $quotation->id);
            } catch (\Exception $emailException) {
                Log::error('Failed to send status change notification emails: ' . $emailException->getMessage(), [
                    'quotation_id' => $quotation->id,
                    'new_status' => $request->status,
                    'error' => $emailException->getMessage()
                ]);
            }

            Log::info('Quotation status updated to ' . $request->status . ' for quotation ID: ' . $quotation->id . ' by user ID: ' . Auth::id());

            $statusMessage = $request->status === 'approved' ? 'approved' : 'rejected';
            return redirect()->route('quotations.show', $quotation)->with('success', "Quotation {$statusMessage} successfully!");
        } catch (\Exception $e) {
            Log::error('Failed to update quotation status: ' . $e->getMessage(), [
                'quotation_id' => $quotation->id,
                'user_id' => Auth::id(),
                'status' => $request->status,
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['error' => 'Failed to update quotation status. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        // Check authorization
        if (Auth::user()->userRole !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        try {
            $quotation->delete();
            return redirect()->route('quotations')->with('success', 'Quotation deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete quotation. Please try again.']);
        }
    }
}
