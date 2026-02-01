<?php

namespace App\Http\Controllers;

use App\Models\Cloth;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClothController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clothes = Cloth::with('category', 'sizes')->latest()->paginate(10);
        return view('clothes.index', compact('clothes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('clothes.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_baju' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'warna' => 'nullable|string|max:50',
            'harga_sewa' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('clothes', 'public');
        }

        Cloth::create($validated);

        return redirect()->route('clothes.index')->with('success', 'Baju berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cloth = Cloth::findOrFail($id);
        $categories = Category::all();
        return view('clothes.edit', compact('cloth', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cloth = Cloth::findOrFail($id);
        
        $validated = $request->validate([
            'nama_baju' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'warna' => 'nullable|string|max:50',
            'harga_sewa' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($cloth->gambar) {
                Storage::disk('public')->delete($cloth->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('clothes', 'public');
        }

        $cloth->update($validated);

        return redirect()->route('clothes.index')->with('success', 'Data baju berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cloth = Cloth::findOrFail($id);
        
        if ($cloth->gambar) {
            Storage::disk('public')->delete($cloth->gambar);
        }
        $cloth->delete();
        return redirect()->route('clothes.index')->with('success', 'Data baju berhasil dihapus.');
    }
}
