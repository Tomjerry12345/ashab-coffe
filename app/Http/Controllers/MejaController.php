<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomorMeja' => 'required|string|max:10|unique:mejas,nomorMeja',
        ]);

        $nomorMeja = $request->nomorMeja;
        // $link = url("/meja/$nomorMeja");

        $host = "10.223.205.225";
        $port = request()->getPort();
        $link = "http://$host:$port/order/meja/$nomorMeja/minuman";

        // Generate QR code pakai layanan eksternal
        $qrImage = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?data=$link&size=200x200");

        $fileName = 'qr_meja_' . $nomorMeja . '.png';
        Storage::disk('public')->put("qr_meja/$fileName", $qrImage);

        Meja::create([
            'nomorMeja' => $nomorMeja,
            'qrCode' => "qr_meja/$fileName"
        ]);

        return redirect()->back()->with('success', 'Meja berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meja $meja)
    {
        // Hapus file QR
        if ($meja->qrCode && Storage::disk('public')->exists($meja->qrCode)) {
            Storage::disk('public')->delete($meja->qrCode);
        }

        $meja->delete();

        return redirect()->back()->with('success', 'Meja berhasil dihapus!');
    }
}
