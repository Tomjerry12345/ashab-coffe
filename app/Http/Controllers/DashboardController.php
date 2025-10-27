<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::where('status', 'finished')->count();

        $revenue = Order::where('status', 'finished')
            ->select(DB::raw('SUM(harga * jumlah) as total'))
            ->value('total') ?? 0;

        $totalUsers = Order::where('status', 'finished')
            ->distinct('meja_id')
            ->count('meja_id');

        return view('dashboard', compact('totalOrders', 'revenue', 'totalUsers'));
    }
}
