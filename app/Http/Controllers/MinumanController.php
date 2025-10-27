<?php

namespace App\Http\Controllers;

use App\Models\Minuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MinumanController extends Controller
{

    public function index()
    {
        $minumans = Minuman::paginate(10);
        return view('minuman', compact('minumans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaMinuman' => 'required|string|max:255',
            'hargaMinuman' => 'required|numeric',
            'stokMinuman' => 'required|integer|min:0',
            'fotoMinuman' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'model3D'      => 'nullable|file|max:10240'
        ]);

        try {
            $data = $request->only(['namaMinuman', 'hargaMinuman', 'stokMinuman']);

            if ($request->hasFile('fotoMinuman')) {
                $data['fotoMinuman'] = $request->file('fotoMinuman')->store('minuman', 'public');
            }

            if ($request->hasFile('model3D')) {
                $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
                if (!in_array($ext, ['glb', 'gltf'])) {
                    return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
                }

                $data['model3D'] = $request->file('model3D')->store('minuman_3d', 'public');
            }

            Minuman::create($data);

            return redirect()->route('minuman.index')->with('success', 'Minuman berhasil ditambahkan!');
        } catch (\Throwable $e) {
            Log::error('Gagal menambahkan minuman: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Minuman $minuman)
    {
        $request->validate([
            'namaMinuman' => 'required|string|max:255',
            'hargaMinuman' => 'required|numeric',
            'stokMinuman' => 'required|integer|min:0',
            'fotoMinuman' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'model3D'      => 'nullable|file|max:10240'
        ]);

        $data = $request->only(['namaMinuman', 'hargaMinuman', 'stokMinuman']);

        if ($request->hasFile('fotoMinuman')) {
            if ($minuman->fotoMinuman) {
                Storage::disk('public')->delete($minuman->fotoMinuman);
            }
            $data['fotoMinuman'] = $request->file('fotoMinuman')->store('minuman', 'public');
        }

        if ($request->hasFile('model3D')) {
            $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
            }

            if ($minuman->modelZ3D) {
                Storage::disk('public')->delete($minuman->model3D);
            }

            $data['model3D'] = $request->file('model3D')->store('minuman_3d', 'public');
        }

        $minuman->update($data);

        return redirect()->route('minuman.index')->with('success', 'Minuman berhasil diperbarui!');
    }

    public function destroy(Minuman $minuman)
    {
        if ($minuman->fotoMinuman) {
            Storage::disk('public')->delete($minuman->fotoMinuman);
        }
        $minuman->delete();

        return redirect()->route('minuman.index')->with('success', 'Minuman berhasil dihapus!');
    }
}
