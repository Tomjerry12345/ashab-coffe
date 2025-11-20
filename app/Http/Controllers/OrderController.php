<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Makanan;
use App\Models\Meja;
use App\Models\Minuman;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index($nomor)
    {
        // misalnya ambil data meja dari DB
        $meja = Meja::where('nomorMeja', $nomor)->firstOrFail();

        Log::info($meja);

        return view('menu', [
            'meja' => $meja
        ]);
    }

    public function create(Meja $meja, $kategori = null)
    {
        function formatItem($item, $jenis)
        {
            return [
                'id' => $item->id,
                'nama' => $jenis === 'makanan' ? $item->namaMakanan : $item->namaMinuman,
                'harga' => $jenis === 'makanan' ? $item->hargaMakanan : $item->hargaMinuman,
                'stok' => $jenis === 'makanan' ? $item->stokMakanan : $item->stokMinuman,
                'foto' => $jenis === 'makanan' ? $item->fotoMakanan : $item->fotoMinuman,
                'model3D' => $item->model3D
            ];
        }

        if ($kategori === 'makanan') {
            $items = Makanan::all()->map(fn($m) => (object) formatItem($m, 'makanan'));
        } elseif ($kategori === 'minuman') {
            $items = Minuman::all()->map(fn($m) => (object) formatItem($m, 'minuman'));
        } else {
            $items = collect();
        }

        $banks = Bank::all();


        return view('menu', compact('meja', 'items', 'kategori', 'banks'));
    }

    public function checkout(Request $request, $meja_id)
    {
        $cart = $request->input('cart'); // array dari frontend
        $payment = $request->input('payment'); // array dari frontend

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        $orderKey = Str::uuid(); // ID unik per transaksi
        $antrian  = $this->generateQueueNumber();

        $now = now()->setTimezone('UTC')->toDateTimeString();

        $orders = [];

        foreach ($cart as $item) {
            $orders[] = [
                'meja_id'   => $meja_id,
                'order_key' => $orderKey,
                'antrian'   => $antrian,
                'nama'      => $item['name'],
                'harga'     => $item['price'],
                'jumlah'    => $item['qty'],
                'catatan'   => $item['note'] ?? null,
                'payment_method' => $payment ?? "cash",
                'status'    => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Order::insert($orders);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil disimpan!',
            'order_key' => $orderKey
        ]);
    }

    public function orderBaru()
    {
        $rows = Order::whereNotIn('status', ['finished', 'cancelled'])
            ->orderBy('created_at', 'asc')
            ->get();

        $orders = $rows->groupBy('order_key')->map(function ($group) {
            return (object)[
                'id'            => $group->first()->id,
                'order_key'     => $group->first()->order_key,
                'meja_id'       => $group->first()->meja_id,
                'status'        => $group->first()->status,
                'payment_method' => $group->first()->payment_method ?? 'cash', // default kalau belum ada
                'total'         => $group->sum(function ($item) {
                    return $item->harga * $item->jumlah;
                }),
                'items'         => $group->map(function ($item) {
                    return (object)[
                        'nama'   => $item->nama,
                        'jumlah' => $item->jumlah,
                        'harga'  => $item->harga,
                    ];
                })->values(),
            ];
        })->sortBy('created_at')->values();

        return view('order.baru', compact('orders'));
    }

    public function rekap(Request $request)
    {
        $jenis = $request->get('jenis', 'harian'); // default harian
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        // Query dasar
        $query = Order::where('status', 'finished');

        // Filter berdasarkan jenis rekap
        if ($jenis === 'harian') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($jenis === 'bulanan') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($jenis === 'tanggal') {
            $query->whereDate('created_at', $tanggal);
        }

        // Ambil data & grup berdasarkan order_key
        $rows = $query->orderBy('created_at', 'desc')->get();

        Log::info($rows);

        $orders = $rows->groupBy('order_key')->map(function ($group) {
            $first = $group->first();

            return (object)[
                'meja_id'       => $first->meja_id,
                'time'          => $first->created_at
                    ->setTimezone('Asia/Makassar')
                    ->format('Y-m-d H:i:s'),
                'total_belanja' => $group->sum(fn($item) => $item->harga * $item->jumlah),
                'payment_method' => $first->payment_method,
                'detail'        => $group->map(function ($item) {
                    return $item->nama . ' (' . $item->jumlah . 'x)';
                })->implode(', '),
            ];
        })->values();

        return view('order.rekap', compact('orders'));
    }

    public function konfirmasiPesanan($order_key)
    {
        $order = Order::where('order_key', $order_key)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $nextStatus = $order->status;

        // Tentukan status selanjutnya
        switch ($order->status) {
            case 'pending':
                $nextStatus = 'cooking';
                break;

            case 'cooking':
                $nextStatus = 'finished';
                break;

            case 'finished':
                $nextStatus = 'finished';
                break;
        }

        // Jika berubah menjadi finished, set finished_at
        if ($order->status !== 'finished' && $nextStatus === 'finished') {
            Order::where('order_key', $order_key)
                ->update([
                    'status'       => $nextStatus,
                    'finished_at'  => now()
                ]);
        } else {
            // update biasa
            Order::where('order_key', $order_key)
                ->update(['status' => $nextStatus]);
        }

        return redirect()->back()->with('success', "Status pesanan diubah menjadi {$nextStatus}.");
    }

    public function batalPesanan($order_key)
    {
        Order::where('order_key', $order_key)
            ->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pesanan dibatalkan.');
    }

    public function orderMe($meja_id)
    {
        $orders = Order::where('meja_id', $meja_id)
            ->where(function ($q) {
                $q->where('status', '!=', 'finished')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'finished')
                            ->where('finished_at', '>=', now()->subMinute());
                    });
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('order_key')
            ->map(function ($items) {
                $firstItem = $items->first();

                $beforeCount = Order::whereDate('created_at', today())
                    ->where('created_at', '<', $firstItem->created_at)
                    ->whereIn('status', ['pending', 'cooking'])
                    ->distinct('order_key')
                    ->count('order_key');

                return [
                    'antrian'      => $firstItem->antrian,
                    'status'       => $firstItem->status,
                    'sebelum_saya' => $beforeCount,
                    'pesanan'      => $items->map(function ($i) {
                        return [
                            'nama_menu' => $i->nama,
                            'jumlah'    => $i->jumlah,
                            'harga'     => $i->harga,
                        ];
                    })->values()
                ];
            });

        return response()->json($orders->values());
    }

    public function generateQueueNumber()
    {
        // Ambil order terakhir HARI INI
        $lastOrder = Order::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastOrder || !$lastOrder->antrian) {
            return "A1"; // kalau belum ada order hari ini
        }

        $lastNumber = (int) substr($lastOrder->antrian, 1); // ambil angka dari "A12"
        return "A" . ($lastNumber + 1);
    }
}
