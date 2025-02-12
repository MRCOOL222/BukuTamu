<?php

namespace App\Http\Controllers;

use App\Models\WorkField;
use Illuminate\Http\Request;

class WorkFieldController extends Controller
{
    public function index()
    {
        $workFields = WorkField::all();
        return view('WorkField.index', compact('workFields'));
    }

    public function create()
    {
        return view('WorkField.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        WorkField::create($request->all());

        return redirect()->route('workfield.index')->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit(WorkField $workField)
    {
        return view('WorkField.edit', compact('workField'));
    }

    public function update(Request $request, WorkField $workField)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $workField->update($request->all());

        return redirect()->route('workfield.index')->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy(WorkField $workField)
    {
        $workField->delete();

        return redirect()->route('workfield.index')->with('success', 'Bidang berhasil dihapus.');
    }
}
