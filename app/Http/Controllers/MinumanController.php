<?php

namespace App\Http\Controllers;

use App\Models\Minuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                $path = $request->file('fotoMinuman')->getRealPath();
                $result  = cloudinary()->uploadApi()->upload($path, [
                    "folder" => "minuman-image"
                ]);

                $data['fotoMinuman'] = $result["url"];
                $data['publicIdMinuman'] = $result["public_id"];
            }

            if ($request->hasFile('model3D')) {
                $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
                if (!in_array($ext, ['glb', 'gltf'])) {
                    return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
                }

                $path = $request->file('model3D')->getRealPath();
                $result  = cloudinary()->uploadApi()->upload($path, [
                    "folder" => "model3d-minuman-image"
                ]);

                $data['model3D'] = $result["url"];
                $data['publicIdModel3dMinuman'] = $result["public_id"];
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
                cloudinary()->uploadApi()->destroy($minuman->publicId);
            }
            $path = $request->file('fotoMinuman')->getRealPath();
            $result  = cloudinary()->uploadApi()->upload($path, [
                "folder" => "minuman-image"
            ]);
            $data['fotoMinuman'] = $result["url"];
            $data['publicIdMinuman'] = $result["public_id"];
        }

        if ($request->hasFile('model3D')) {
            $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
            }

            if ($minuman->model3D) {
                cloudinary()->uploadApi()->destroy($minuman->publicIdModel3d);
            }

            $path = $request->file('model3D')->getRealPath();
            $result  = cloudinary()->uploadApi()->upload($path, [
                "folder" => "model3d-minuman-image"
            ]);
            $data['model3D'] = $result["url"];
            $data['publicIdModel3dMinuman'] = $result["public_id"];
        }

        $minuman->update($data);

        return redirect()->route('minuman.index')->with('success', 'Minuman berhasil diperbarui!');
    }

    public function destroy(Minuman $minuman)
    {
        cloudinary()->uploadApi()->destroy($minuman->publicIdMinuman);
        cloudinary()->uploadApi()->destroy($minuman->publicIdModel3dMinuman);

        $minuman->delete();

        return redirect()->route('minuman.index')->with('success', 'Minuman berhasil dihapus!');
    }
}
