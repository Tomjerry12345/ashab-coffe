<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banks = Bank::orderBy('nama_bank', 'asc')->get();
        return view('bank', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        Log::info("Awal berhasil...");

        $request->validate([
            'nama_bank' => 'required|string|max:50|unique:banks,nama_bank',
            'gambar_qris' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Log::info($request);

        Log::info("Validasi berhasil...");


        // Simpan gambar QRIS ke storage
        $path = $request->file('gambar_qris')->store('qr_bank', 'public');

        Log::info("Gambar berhasil...");

        Bank::create([
            'nama_bank' => $request->nama_bank,
            'gambar_qris' => $path
        ]);

        Log::info("Database berhasil...");

        return redirect()->back()->with('success', 'Bank berhasil ditambahkan!');
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
    public function destroy(Bank $bank)
    {
        if ($bank->gambarQris && Storage::disk('public')->exists($bank->gambar_qris)) {
            Storage::disk('public')->delete($bank->gambar_qris);
        }

        $bank->delete();

        return redirect()->back()->with('success', 'Bank berhasil dihapus!');
    }
}
