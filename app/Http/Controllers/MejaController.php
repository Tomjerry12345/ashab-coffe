<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mejas = Meja::orderBy('nomorMeja', 'asc')->get();
        return view('meja', compact('mejas'));
    }

    public function status()
    {
        $mejas = Meja::orderBy('nomorMeja', 'asc')->get();
        return view('meja-status', compact('mejas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nomorMeja' => 'required|string|max:10|unique:mejas,nomorMeja',
        ]);

        $nomorMeja = $request->nomorMeja;

        $link = env('APP_URL') . "/order/meja/$nomorMeja/minuman";

        // Generate QR code pakai layanan eksternal
        $qrImage = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?data=$link&size=200x200");

        // 🔹 Simpan sementara ke file temp
        $tempFile = tempnam(sys_get_temp_dir(), 'qr_');
        file_put_contents($tempFile, $qrImage);

        $uploadedFileUrl = cloudinary()->uploadApi()->upload($tempFile, [
            "folder" => "qr-image"
        ]);
        $result = $uploadedFileUrl->getArrayCopy();

        // 🔹 Hapus file sementara
        @unlink($tempFile);

        Meja::create([
            'publicId' => $result["public_id"],
            'nomorMeja' => $nomorMeja,
            'qrCode' => $result["secure_url"],
        ]);

        return redirect()->back()->with('success', 'Meja berhasil ditambahkan!');
    }

    public function toggleStatus($id)
    {
        $meja = Meja::findOrFail($id);

        if ($meja->status === 'kosong') {
            // ubah ke terisi + isi timestamp
            $meja->status = 'terisi';
            $meja->terisi_sejak = Carbon::now();
        } else {
            // ubah ke kosong + reset timestamp
            $meja->status = 'kosong';
            $meja->terisi_sejak = null;
        }

        $meja->save();

        return back()->with('success', 'Status meja berhasil diupdate!');
    }

    public function downloadStatusQR()
    {
        $url = env('APP_URL') . "/meja/status";

        // Generate QR
        $qrImage = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($url) . "&size=300x300");

        return response($qrImage)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="QR-Status-Meja.png"');
    }

    public function destroy(Meja $meja)
    {
        if ($meja->publicId) {
            cloudinary()->uploadApi()->destroy($meja->publicId);
        }

        $meja->delete();

        return redirect()->back()->with('success', 'Meja berhasil dihapus!');
    }
}
