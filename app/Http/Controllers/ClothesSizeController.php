<?php

namespace App\Http\Controllers;

use App\Models\ClothesSize;
use App\Models\Cloth;
use Illuminate\Http\Request;

class ClothesSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clothesId = $request->query('clothes_id');
        if (!$clothesId) {
            return redirect()->route('clothes.index')->with('error', 'Silakan pilih baju terlebih dahulu.');
        }

        $cloth = Cloth::findOrFail($clothesId);
        $sizes = ClothesSize::where('clothes_id', $clothesId)->get();

        return view('clothes_sizes.index', compact('cloth', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clothes_id' => 'required|exists:clothes,id',
            'ukuran' => 'required|string|max:10',
            'stok' => 'required|integer|min:0',
        ]);

        $validated['ukuran'] = strtoupper($validated['ukuran']);

        // Check if size exists, update if so
        $existing = ClothesSize::where('clothes_id', $validated['clothes_id'])
                               ->where('ukuran', $validated['ukuran'])
                               ->first();

        if ($existing) {
            $existing->stok = $validated['stok']; // Or add? Use replace logic for simple management
            $existing->save();
             return redirect()->route('clothes-sizes.index', ['clothes_id' => $validated['clothes_id']])->with('success', 'Stok ukuran berhasil diperbarui.');
        } else {
            ClothesSize::create($validated);
             return redirect()->route('clothes-sizes.index', ['clothes_id' => $validated['clothes_id']])->with('success', 'Ukuran baru berhasil ditambahkan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ClothesSize $clothesSize)
    {
        $clothesId = $request->input('clothes_id') ?? $clothesSize->clothes_id;
        $clothesSize->delete();

        return redirect()->route('clothes-sizes.index', ['clothes_id' => $clothesId])->with('success', 'Ukuran berhasil dihapus.');
    }
}
