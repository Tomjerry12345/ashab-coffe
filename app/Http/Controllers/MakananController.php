<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MakananController extends Controller
{
    public function index()
    {
        $makanans = Makanan::latest()->paginate(10);
        return view('makanan', compact('makanans'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'namaMakanan'  => 'required|string|max:255',
            'hargaMakanan' => 'required|integer|min:0',
            'stokMakanan'  => 'required|integer|min:0',
            'fotoMakanan'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'model3D'      => 'nullable|file|max:10240'
        ]);

        $data = $request->only(['namaMakanan', 'hargaMakanan', 'stokMakanan']);

        if ($request->hasFile('fotoMakanan')) {
            $data['fotoMakanan'] = $request->file('fotoMakanan')->store('makanan', 'public');
        }

        if ($request->hasFile('model3D')) {
            $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
            }

            $data['model3D'] = $request->file('model3D')->store('makanan_3d', 'public');
        }

        Makanan::create($data);

        return redirect()->route('makanan.index')->with('success', 'Makanan berhasil ditambahkan!');
    }

    public function update(Request $request, Makanan $makanan)
    {
        $request->validate([
            'namaMakanan'  => 'required|string|max:255',
            'hargaMakanan' => 'required|integer|min:0',
            'stokMakanan'  => 'required|integer|min:0',
            'fotoMakanan'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'model3D'      => 'nullable|file|max:10240'
        ]);

        $data = $request->only(['namaMakanan', 'hargaMakanan', 'stokMakanan']);

        if ($request->hasFile('fotoMakanan')) {
            if ($makanan->fotoMakanan) {
                Storage::disk('public')->delete($makanan->fotoMakanan);
            }
            $data['fotoMakanan'] = $request->file('fotoMakanan')->store('makanan', 'public');
        }

        if ($request->hasFile('model3D')) {
            $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
            }

            if ($makanan->model3D) {
                Storage::disk('public')->delete($makanan->model3D);
            }

            $data['model3D'] = $request->file('model3D')->store('makanan_3d', 'public');
        }

        $makanan->update($data);

        return redirect()->route('makanan.index')->with('success', 'Makanan berhasil diupdate!');
    }

    public function destroy(Makanan $makanan)
    {
        if ($makanan->fotoMakanan) {
            Storage::disk('public')->delete($makanan->fotoMakanan);
        }
        $makanan->delete();

        return redirect()->route('makanan.index')->with('success', 'Makanan berhasil dihapus!');
    }
}
