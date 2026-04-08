<?php

namespace App\Http\Controllers;

use App\Models\BonusCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminBonusController extends Controller
{
    public function index()
    {
        $codes = BonusCode::withCount('users')->orderByDesc('created_at')->get();
        return view('admin.bonus', compact('codes'));
    }

    public function create()
    {
        return view('admin.bonus-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:bonus_codes,code',
            'montant' => 'required|integer|min:0.1',
            'max_usage' => 'required|integer|min:1',
        ]);

        BonusCode::create([
            'code' => strtoupper($request->code),
            'montant' => $request->montant,
            'max_usage' => $request->max_usage,
            'is_active' => true,
        ]);

        return redirect()->route('admin.bonus.index')->with('success', 'Code bonus créé avec succès ✅');
    }

    public function toggle($id)
    {
        $code = BonusCode::findOrFail($id);
        $code->is_active = !$code->is_active;
        $code->save();

        return back()->with('success', 'Statut du code mis à jour ✅');
    }
}
