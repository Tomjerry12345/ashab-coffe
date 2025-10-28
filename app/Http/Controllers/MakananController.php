<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;

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
            $path = $request->file('fotoMakanan')->getRealPath();
            $result  = cloudinary()->uploadApi()->upload($path, [
                "folder" => "makanan-image"
            ]);

            $data['fotoMakanan'] = $result["url"];
            $data['publicIdMakanan'] = $result["public_id"];
        }

        if ($request->hasFile('model3D')) {
            $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
            }

            $path = $request->file('model3D')->getRealPath();
            $result  = cloudinary()->uploadApi()->upload($path, [
                "folder" => "model3d-makanan-image"
            ]);
            $data['model3D'] = $result["url"];
            $data['publicIdModel3dMakanan'] = $result["public_id"];
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
                cloudinary()->uploadApi()->destroy($makanan->publicId);
            }

            $path = $request->file('fotoMakanan')->getRealPath();
            $result  = cloudinary()->uploadApi()->upload($path, [
                "folder" => "makanan-image"
            ]);
            $data['fotoMakanan'] = $result["url"];
            $data['publicIdMakanan'] = $result["public_id"];
        }

        if ($request->hasFile('model3D')) {
            $ext = strtolower($request->file('model3D')->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['model3D' => 'File 3D harus berformat .glb atau .gltf']);
            }

            if ($makanan->model3D) {
                cloudinary()->uploadApi()->destroy($makanan->publicIdModel3d);
            }

            $path = $request->file('model3D')->getRealPath();
            $result  = cloudinary()->uploadApi()->upload($path, [
                "folder" => "model3d-makanan-image"
            ]);
            $data['model3D'] = $result["url"];
            $data['publicIdModel3dMakanan'] = $result["public_id"];
        }

        $makanan->update($data);

        return redirect()->route('makanan.index')->with('success', 'Makanan berhasil diupdate!');
    }

    public function destroy(Makanan $makanan)
    {
        cloudinary()->uploadApi()->destroy($makanan->publicIdMakanan);
        cloudinary()->uploadApi()->destroy($makanan->publicIdModel3dMakanan);

        $makanan->delete();

        return redirect()->route('makanan.index')->with('success', 'Makanan berhasil dihapus!');
    }
}
