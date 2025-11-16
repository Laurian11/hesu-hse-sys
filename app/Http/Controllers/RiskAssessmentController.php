<?php

namespace App\Http\Controllers;

use App\Models\RiskAssessment;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiskAssessmentController extends Controller
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
        $riskAssessments = RiskAssessment::with(['creator', 'reviewer', 'company'])
            ->where('company_id', Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('risk-assessments.index', compact('riskAssessments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('risk-assessments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_level' => 'required|in:low,medium,high,critical',
            'probability' => 'required|in:low,medium,high',
            'impact' => 'required|in:low,medium,high',
            'mitigation_plan' => 'nullable|string',
            'residual_risk' => 'nullable|string',
            'assessment_date' => 'required|date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $riskAssessment = new RiskAssessment($validated);
        $riskAssessment->company_id = Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id;
        $riskAssessment->created_by = Auth::id();
        $riskAssessment->status = 'draft';

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('risk-assessments', 'public');
                $attachments[] = $path;
            }
            $riskAssessment->attachments = $attachments;
        }

        $riskAssessment->save();

        // Generate assessment number
        $riskAssessment->assessment_number = $riskAssessment->generateAssessmentNumber();
        $riskAssessment->save();

        return redirect()->route('risk-assessments.index')->with('success', 'Risk assessment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RiskAssessment $riskAssessment)
    {
        $this->authorize('view', $riskAssessment);
        return view('risk-assessments.show', compact('riskAssessment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RiskAssessment $riskAssessment)
    {
        $this->authorize('update', $riskAssessment);
        return view('risk-assessments.edit', compact('riskAssessment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RiskAssessment $riskAssessment)
    {
        $this->authorize('update', $riskAssessment);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_level' => 'required|in:low,medium,high,critical',
            'probability' => 'required|in:low,medium,high',
            'impact' => 'required|in:low,medium,high',
            'mitigation_plan' => 'nullable|string',
            'residual_risk' => 'nullable|string',
            'status' => 'required|in:draft,pending_review,approved,rejected',
            'assessment_date' => 'required|date',
            'review_date' => 'nullable|date',
            'reviewed_by' => 'nullable|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $riskAssessment->fill($validated);

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $riskAssessment->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('risk-assessments', 'public');
                $attachments[] = $path;
            }
            $riskAssessment->attachments = $attachments;
        }

        $riskAssessment->save();

        return redirect()->route('risk-assessments.show', $riskAssessment)->with('success', 'Risk assessment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RiskAssessment $riskAssessment)
    {
        $this->authorize('delete', $riskAssessment);

        // Delete attachments
        if ($riskAssessment->attachments) {
            foreach ($riskAssessment->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $riskAssessment->delete();

        return redirect()->route('risk-assessments.index')->with('success', 'Risk assessment deleted successfully.');
    }
}
