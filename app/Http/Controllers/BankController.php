<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nama_bank' => 'required|string|max:50|unique:banks,nama_bank',
            'gambar_qris' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $path = $request->file('gambar_qris')->getRealPath();

        $result  = cloudinary()->uploadApi()->upload($path, [
            "folder" => "bank-image"
        ]);

        Bank::create([
            'publicId' => $result["public_id"],
            'nama_bank' => $request->nama_bank,
            'gambar_qris' => $result["secure_url"]
        ]);

        return redirect()->back()->with('success', 'Bank berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        cloudinary()->uploadApi()->destroy($bank->publicId);

        $bank->delete();

        return redirect()->back()->with('success', 'Bank berhasil dihapus!');
    }
}
