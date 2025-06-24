<?php

namespace App\Http\Controllers;

use App\Models\LessonSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonSectionController extends Controller
{
    /**
     * Display a listing of the lesson sections.
     */
    public function index()
    {
        $lessonSections = LessonSection::orderBy('start_time')->get();
        return view('lesson-sections.index', compact('lessonSections'));
    }

    /**
     * Show the form for creating a new lesson section.
     */
    public function create()
    {
        return view('lesson-sections.create');
    }

    /**
     * Store a newly created lesson section in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->route('lesson-sections.create')
                ->withErrors($validator)
                ->withInput();
        }

        LessonSection::create($request->all());

        return redirect()->route('lesson-sections.index')
            ->with('success', 'Lesson section created successfully.');
    }

    /**
     * Display the specified lesson section.
     */
    public function show(LessonSection $lessonSection)
    {
        return view('lesson-sections.show', compact('lessonSection'));
    }

    /**
     * Show the form for editing the specified lesson section.
     */
    public function edit(LessonSection $lessonSection)
    {
        return view('lesson-sections.edit', compact('lessonSection'));
    }

    /**
     * Update the specified lesson section in storage.
     */
    public function update(Request $request, LessonSection $lessonSection)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->route('lesson-sections.edit', $lessonSection->id)
                ->withErrors($validator)
                ->withInput();
        }

        $lessonSection->update($request->all());

        return redirect()->route('lesson-sections.index')
            ->with('success', 'Lesson section updated successfully.');
    }

    /**
     * Remove the specified lesson section from storage.
     */
    public function destroy(LessonSection $lessonSection)
    {
        $lessonSection->delete();

        return redirect()->route('lesson-sections.index')
            ->with('success', 'Lesson section deleted successfully.');
    }
}
