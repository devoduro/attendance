<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CentreController extends Controller
{
    /**
     * Display a listing of the centres.
     */
    public function index()
    {
        $centres = Centre::orderBy('name')->get();
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
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('centres.create')
                ->withErrors($validator)
                ->withInput();
        }

        Centre::create($request->all());

        return redirect()->route('centres.index')
            ->with('success', 'Centre created successfully.');
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
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('centres.edit', $centre->id)
                ->withErrors($validator)
                ->withInput();
        }

        $centre->update($request->all());

        return redirect()->route('centres.index')
            ->with('success', 'Centre updated successfully.');
    }

    /**
     * Remove the specified centre from storage.
     */
    public function destroy(Centre $centre)
    {
        $centre->delete();

        return redirect()->route('centres.index')
            ->with('success', 'Centre deleted successfully.');
    }
}
