<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
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
        $incidents = Incident::with(['reporter', 'investigator', 'company'])
            ->where('company_id', Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('incidents.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('incidents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:injury,property_damage,near_miss,environmental,other',
            'severity' => 'required|in:minor,moderate,major,critical',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_date' => 'required|date',
            'location' => 'required|string|max:255',
            'affected_parties' => 'nullable|array',
            'witnesses' => 'nullable|array',
            'immediate_actions' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $incident = new Incident($validated);
        $incident->company_id = Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id;
        $incident->reported_by = Auth::id();
        $incident->status = 'open';

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('incidents', 'public');
                $attachments[] = $path;
            }
            $incident->attachments = $attachments;
        }

        $incident->save();

        // Generate incident number
        $incident->incident_number = $incident->generateIncidentNumber();
        $incident->save();

        return redirect()->route('incidents.index')->with('success', 'Incident reported successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Incident $incident)
    {
        $this->authorize('view', $incident);
        return view('incidents.show', compact('incident'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Incident $incident)
    {
        $this->authorize('update', $incident);
        return view('incidents.edit', compact('incident'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incident $incident)
    {
        $this->authorize('update', $incident);

        $validated = $request->validate([
            'type' => 'required|in:injury,property_damage,near_miss,environmental,other',
            'severity' => 'required|in:minor,moderate,major,critical',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_date' => 'required|date',
            'location' => 'required|string|max:255',
            'affected_parties' => 'nullable|array',
            'witnesses' => 'nullable|array',
            'immediate_actions' => 'nullable|string',
            'status' => 'required|in:open,investigating,closed',
            'root_cause' => 'nullable|string',
            'corrective_actions' => 'nullable|string',
            'preventive_actions' => 'nullable|string',
            'closure_date' => 'nullable|date',
            'investigated_by' => 'nullable|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $incident->fill($validated);

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $incident->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('incidents', 'public');
                $attachments[] = $path;
            }
            $incident->attachments = $attachments;
        }

        $incident->save();

        return redirect()->route('incidents.show', $incident)->with('success', 'Incident updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incident $incident)
    {
        $this->authorize('delete', $incident);

        // Delete attachments
        if ($incident->attachments) {
            foreach ($incident->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $incident->delete();

        return redirect()->route('incidents.index')->with('success', 'Incident deleted successfully.');
    }
}
