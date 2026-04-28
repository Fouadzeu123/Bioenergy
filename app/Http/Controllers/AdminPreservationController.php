<?php

namespace App\Http\Controllers;

use App\Models\Preservation;
use Illuminate\Http\Request;

class AdminPreservationController extends Controller
{
    public function index()
    {
        $fonds = Preservation::orderBy('rate', 'desc')->get();
        return view('admin.preservation.index', compact('fonds'));
    }

    public function create()
    {
        return view('admin.preservation.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'min_amount'  => 'required|numeric|min:0',
            'rate'        => 'required|numeric|min:0',
            'period_days' => 'required|integer|min:1',
            'limit_order' => 'nullable|integer|min:1',
        ]);

        Preservation::create($data);
        return redirect()->route('admin.preservation.index')->with('success', 'Fond de préservation créé.');
    }

    public function edit(Preservation $preservation)
    {
        return view('admin.preservation.edit', compact('preservation'));
    }

    public function update(Request $request, Preservation $preservation)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'min_amount'  => 'required|numeric|min:0',
            'rate'        => 'required|numeric|min:0',
            'period_days' => 'required|integer|min:1',
            'limit_order' => 'nullable|integer|min:1',
        ]);

        $preservation->update($data);
        return redirect()->route('admin.preservation.index')->with('success', 'Fond mis à jour.');
    }

    public function destroy(Preservation $preservation)
    {
        $preservation->delete();
        return redirect()->route('admin.preservation.index')->with('success', 'Fond supprimé.');
    }
}
