<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class AdminProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::orderBy('level')->get();
        return view('admin.produits.index', compact('produits'));
    }

    public function create()
    {
        return view('admin.produits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'min_amount'  => 'required|numeric|min:0',
            'max_amount'  => 'nullable|numeric|min:0',
            'rate'        => 'required|numeric|min:0',
            'level'       => 'required|integer|min:0',
            'limit_order' => 'required|integer|min:1',
        ]);

        Produit::create($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Produit $produit)
    {
        return view('admin.produits.edit', compact('produit'));
    }

    public function update(Request $request, Produit $produit)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'min_amount'  => 'required|numeric|min:0',
            'max_amount'  => 'nullable|numeric|min:0',
            'rate'        => 'required|numeric|min:0',
            'level'       => 'required|integer|min:0',
            'limit_order' => 'required|integer|min:1',
        ]);

        $produit->update($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Produit $produit)
    {
        $produit->delete();
        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé.');
    }
}
