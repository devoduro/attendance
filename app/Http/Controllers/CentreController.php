<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\LessonSchedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CentreController extends Controller
{
    /**
     * Display a listing of the centres.
     */
    public function index(Request $request)
    {
        $query = Centre::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }
        
        $centres = $query->orderBy('name')->get();
        
        // Add counts for each centre
        foreach ($centres as $centre) {
            $centre->student_count = Student::where('centre_id', $centre->id)->count();
            $centre->lesson_count = LessonSchedule::where('centre_id', $centre->id)->count();
        }
        
        return view('centres.index', compact('centres'));
    }

    /**
     * Show the form for creating a new centre.
     */
    public function create()
    {
        return view('centres.create');
    }

    /**
     * Store a newly created centre in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:centres,name',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:centres,email',
            'is_active' => 'sometimes|boolean',
        ], [
            'name.unique' => 'A centre with this name already exists.',
            'email.unique' => 'This email is already registered to another centre.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('centres.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? true : false;
            
            $centre = Centre::create($data);
            
            return redirect()->route('centres.index')
                ->with('success', "Centre '{$centre->name}' created successfully.");
        } catch (\Exception $e) {
            return redirect()->route('centres.create')
                ->with('error', 'Failed to create centre. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified centre.
     */
    public function show(Centre $centre)
    {
        return view('centres.show', compact('centre'));
    }

    /**
     * Show the form for editing the specified centre.
     */
    public function edit(Centre $centre)
    {
        return view('centres.edit', compact('centre'));
    }

    /**
     * Update the specified centre in storage.
     */
    public function update(Request $request, Centre $centre)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:centres,name,' . $centre->id,
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:centres,email,' . $centre->id,
            'is_active' => 'sometimes|boolean',
        ], [
            'name.unique' => 'A centre with this name already exists.',
            'email.unique' => 'This email is already registered to another centre.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('centres.edit', $centre->id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? true : false;
            
            $centre->update($data);
            
            return redirect()->route('centres.index')
                ->with('success', "Centre '{$centre->name}' updated successfully.");
        } catch (\Exception $e) {
            return redirect()->route('centres.edit', $centre->id)
                ->with('error', 'Failed to update centre. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified centre from storage.
     */
    public function destroy(Centre $centre)
    {
        try {
            // Check if centre has related records
            $studentCount = Student::where('centre_id', $centre->id)->count();
            $lessonCount = LessonSchedule::where('centre_id', $centre->id)->count();
            
            if ($studentCount > 0 || $lessonCount > 0) {
                return redirect()->route('centres.index')
                    ->with('error', "Cannot delete '{$centre->name}' because it has {$studentCount} students and {$lessonCount} lessons associated with it.");
            }
            
            $centreName = $centre->name;
            $centre->delete();

            return redirect()->route('centres.index')
                ->with('success', "Centre '{$centreName}' deleted successfully.");
        } catch (\Exception $e) {
            return redirect()->route('centres.index')
                ->with('error', 'Failed to delete centre. ' . $e->getMessage());
        }
    }
}
