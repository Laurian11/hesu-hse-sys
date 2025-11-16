<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
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
        $trainings = Training::with(['creator', 'company'])
            ->where('company_id', Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id)
            ->orderBy('scheduled_date', 'desc')
            ->paginate(15);

        return view('trainings.index', compact('trainings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trainings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:general,safety,compliance,technical',
            'scheduled_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'trainer' => 'required|string|max:255',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
            'objectives' => 'nullable|string',
            'materials' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:5120',
        ]);

        $training = new Training($validated);
        $training->company_id = Auth::user()->currentCompany->id ?? Auth::user()->companies->first()->id;
        $training->created_by = Auth::id();
        $training->status = 'scheduled';

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('trainings', 'public');
                $attachments[] = $path;
            }
            $training->attachments = $attachments;
        }

        $training->save();

        // Generate training number
        $training->training_number = $training->generateTrainingNumber();
        $training->save();

        return redirect()->route('trainings.index')->with('success', 'Training session created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        $this->authorize('view', $training);
        return view('trainings.show', compact('training'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training)
    {
        $this->authorize('update', $training);
        return view('trainings.edit', compact('training'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Training $training)
    {
        $this->authorize('update', $training);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:general,safety,compliance,technical',
            'scheduled_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'trainer' => 'required|string|max:255',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
            'objectives' => 'nullable|string',
            'materials' => 'nullable|string',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'completion_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:5120',
        ]);

        $training->fill($validated);

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $training->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('trainings', 'public');
                $attachments[] = $path;
            }
            $training->attachments = $attachments;
        }

        $training->save();

        return redirect()->route('trainings.show', $training)->with('success', 'Training session updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        $this->authorize('delete', $training);

        // Delete attachments
        if ($training->attachments) {
            foreach ($training->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $training->delete();

        return redirect()->route('trainings.index')->with('success', 'Training session deleted successfully.');
    }
}
