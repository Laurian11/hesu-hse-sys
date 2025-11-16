<?php

namespace App\Http\Controllers;

use App\Models\PpeInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PpeInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ppeItems = PpeInventory::with(['creator', 'company'])
            ->where('company_id', Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ppe-inventory.index', compact('ppeItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ppe-inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:helmet,gloves,boots,vest,goggles,mask,respirator,ear_protection,other',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'quantity_available' => 'required|integer|min:0',
            'quantity_minimum' => 'required|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'last_restocked' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:today',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,discontinued,out_of_stock',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $ppeItem = new PpeInventory($validated);
        $ppeItem->company_id = Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id;
        $ppeItem->created_by = Auth::id();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ppe', 'public');
                $attachments[] = $path;
            }
            $ppeItem->attachments = $attachments;
        }

        $ppeItem->save();

        // Generate item code
        $ppeItem->item_code = $ppeItem->generateItemCode();
        $ppeItem->save();

        return redirect()->route('ppe-inventory.index')->with('success', 'PPE item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PpeInventory $ppeInventory)
    {
        $this->authorize('view', $ppeInventory);
        return view('ppe-inventory.show', compact('ppeInventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PpeInventory $ppeInventory)
    {
        $this->authorize('update', $ppeInventory);
        return view('ppe-inventory.edit', compact('ppeInventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PpeInventory $ppeInventory)
    {
        $this->authorize('update', $ppeInventory);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:helmet,gloves,boots,vest,goggles,mask,respirator,ear_protection,other',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'quantity_available' => 'required|integer|min:0',
            'quantity_minimum' => 'required|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'last_restocked' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,discontinued,out_of_stock',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $ppeInventory->fill($validated);

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $ppeInventory->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ppe', 'public');
                $attachments[] = $path;
            }
            $ppeInventory->attachments = $attachments;
        }

        $ppeInventory->save();

        return redirect()->route('ppe-inventory.show', $ppeInventory)->with('success', 'PPE item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PpeInventory $ppeInventory)
    {
        $this->authorize('delete', $ppeInventory);

        // Delete attachments
        if ($ppeInventory->attachments) {
            foreach ($ppeInventory->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $ppeInventory->delete();

        return redirect()->route('ppe-inventory.index')->with('success', 'PPE item deleted successfully.');
    }
}
