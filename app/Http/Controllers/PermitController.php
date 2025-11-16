<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermitController extends Controller
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
        $permits = Permit::with(['requester', 'approver', 'issuer', 'closer', 'company'])
            ->where('company_id', Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('permits.index', compact('permits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:work,hot_work,confined_space,electrical,height_work,excavation,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'work_description' => 'required|string',
            'location' => 'required|string|max:255',
            'planned_start' => 'required|date',
            'planned_end' => 'required|date|after:planned_start',
            'hazards_identified' => 'nullable|string',
            'control_measures' => 'nullable|string',
            'ppe_required' => 'nullable|string',
            'emergency_procedures' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $permit = new Permit($validated);
        $permit->company_id = Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id;
        $permit->requested_by = Auth::id();
        $permit->status = 'draft';

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('permits', 'public');
                $attachments[] = $path;
            }
            $permit->attachments = $attachments;
        }

        $permit->save();

        // Generate permit number
        $permit->permit_number = $permit->generatePermitNumber();
        $permit->save();

        return redirect()->route('permits.index')->with('success', 'Permit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permit $permit)
    {
        $this->authorize('view', $permit);
        return view('permits.show', compact('permit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permit $permit)
    {
        $this->authorize('update', $permit);
        return view('permits.edit', compact('permit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permit $permit)
    {
        $this->authorize('update', $permit);

        $validated = $request->validate([
            'type' => 'required|in:work,hot_work,confined_space,electrical,height_work,excavation,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'work_description' => 'required|string',
            'location' => 'required|string|max:255',
            'planned_start' => 'required|date',
            'planned_end' => 'required|date|after:planned_start',
            'actual_start' => 'nullable|date',
            'actual_end' => 'nullable|date',
            'status' => 'required|in:draft,pending_approval,approved,active,suspended,closed,cancelled',
            'hazards_identified' => 'nullable|string',
            'control_measures' => 'nullable|string',
            'ppe_required' => 'nullable|string',
            'emergency_procedures' => 'nullable|string',
            'closure_notes' => 'nullable|string',
            'approved_by' => 'nullable|exists:users,id',
            'issued_by' => 'nullable|exists:users,id',
            'closed_by' => 'nullable|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $permit->fill($validated);

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $permit->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('permits', 'public');
                $attachments[] = $path;
            }
            $permit->attachments = $attachments;
        }

        $permit->save();

        return redirect()->route('permits.show', $permit)->with('success', 'Permit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permit $permit)
    {
        $this->authorize('delete', $permit);

        // Delete attachments
        if ($permit->attachments) {
            foreach ($permit->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $permit->delete();

        return redirect()->route('permits.index')->with('success', 'Permit deleted successfully.');
    }

    /**
     * Approve a permit.
     */
    public function approve(Permit $permit)
    {
        $this->authorize('approve', $permit);

        if (!$permit->canBeApproved()) {
            return back()->with('error', 'Permit cannot be approved in its current status.');
        }

        $permit->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Permit approved successfully.');
    }

    /**
     * Issue a permit.
     */
    public function issue(Permit $permit)
    {
        $this->authorize('issue', $permit);

        if ($permit->status !== 'approved') {
            return back()->with('error', 'Permit must be approved before issuing.');
        }

        $permit->update([
            'status' => 'active',
            'issued_by' => Auth::id(),
            'actual_start' => now(),
        ]);

        return back()->with('success', 'Permit issued successfully.');
    }

    /**
     * Close a permit.
     */
    public function close(Request $request, Permit $permit)
    {
        $this->authorize('close', $permit);

        if (!$permit->canBeClosed()) {
            return back()->with('error', 'Permit cannot be closed in its current status.');
        }

        $validated = $request->validate([
            'closure_notes' => 'required|string',
        ]);

        $permit->update([
            'status' => 'closed',
            'closed_by' => Auth::id(),
            'actual_end' => now(),
            'closure_notes' => $validated['closure_notes'],
        ]);

        return back()->with('success', 'Permit closed successfully.');
    }
}
