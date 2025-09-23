<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\MenuTransaction;
use Midtrans\Snap;
use Midtrans\Config;

class OrderController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // public function submit(Request $request)
    // {
    //     dd($request->all());
    //     // Validasi form
    //     $request->validate([
    //         'nama_pelanggan' => 'required',
    //         'nomor_meja' => 'required',
    //         'metode_pembayaran' => 'required|in:manual,midtrans',
    //     ]);

    //     // Generate kode transaksi unik
    //     $kode = 'TRX-' . strtoupper(Str::random(8));
    //     $total = 0;
    //     $menuTransactions = [];

    //     // Hitung total harga dari input jumlah menu
    //     foreach ($request->jumlah ?? [] as $menuId => $jumlah) {
    //         $menu = Menu::find($menuId);
    //         if ($menu && $jumlah > 0) {
    //             $menuTransactions[] = [
    //                 'menu_id' => $menu->id,
    //                 'jumlah' => $jumlah,
    //                 'harga' => $menu->harga,
    //             ];
    //             $total += $menu->harga * $jumlah;
    //         }
    //     }

    //     if (empty($menuTransactions)) {
    //         return back()->with('error', 'Pilih menu terlebih dahulu.');
    //     }

    //     // Simpan transaksi
    //     $transaction = Transaction::create([
    //         'kode_transaksi' => $kode,
    //         'nama_pelanggan' => $request->nama_pelanggan,
    //         'nomor_meja' => $request->nomor_meja,
    //         'metode_pembayaran' => $request->metode_pembayaran,
    //         'total_harga' => $total,
    //         'status' => 'menunggu',
    //     ]);

    //     // Simpan item menu dalam transaksi
    //     foreach ($menuTransactions as $item) {
    //         MenuTransaction::create([
    //             'transaction_id' => $transaction->id,
    //             'menu_id' => $item['menu_id'],
    //             'jumlah' => $item['jumlah'],
    //             'harga' => $item['harga'],
    //         ]);
    //     }

    //     // Jika pembayaran via Midtrans, redirect ke Snap URL
    //     if ($request->metode_pembayaran === 'midtrans') {
    //         $params = [
    //             'transaction_details' => [
    //                 'order_id' => $transaction->kode_transaksi,
    //                 'gross_amount' => $transaction->total_harga,
    //             ],
    //             'customer_details' => [
    //                 'first_name' => $transaction->nama_pelanggan,
    //             ]
    //         ];

    //         $snapUrl = Snap::createTransaction($params)->redirect_url;
    //         return redirect($snapUrl);
    //     }

    //     // Jika manual, kembali ke halaman dengan notifikasi
    //     return redirect()->back()->with('success', 'Pesanan berhasil dibuat.');
    // }

    public function midtransCallback(Request $request){
        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;

        $trx = Transaction::where('kode_transaksi', $orderId)->first();
        if(!$trx) return response()->json(['error' => 'not found'], 404);
        
        if($transaction == 'settlement'){
            $trx->status = 'diproses';
        } elseif($transaction == 'expire' || $transaction == 'cancel'){
            $trx->status = 'batal';
        }
        $trx->save();

        if($request->has('redirec')){
            return redirec()->route('order.detail', $trx->kode_transaksi);
        }
        return response()->json(['success' => true]);
    }

    public function submit(Request $request)
{
    $request->validate([
        'nama_pelanggan' => 'required',
        'nomor_meja' => 'required',
        'metode_pembayaran' => 'required|in:manual,midtrans',
        'menus' => 'required',
    ]);

    $menuDipilih = unserialize(base64_decode($request->menus));

    $kode = 'TRX-' . strtoupper(Str::random(8));
    $total = array_sum(array_column($menuDipilih, 'subtotal'));

    $transaction = Transaction::create([
        'kode_transaksi' => $kode,
        'nama_pelanggan' => $request->nama_pelanggan,
        'nomor_meja' => $request->nomor_meja,
        'metode_pembayaran' => $request->metode_pembayaran,
        'total_harga' => $total,
        'status' => 'menunggu',
    ]);

    foreach ($menuDipilih as $item) {
        MenuTransaction::create([
            'transaction_id' => $transaction->id,
            'menu_id' => $item['menu']->id,
            'jumlah' => $item['jumlah'],
            'harga' => $item['menu']->harga,
            'catatan' => '',
        'level_pedas' => $request->input("level_pedas.{$item['menu']->id}"),
        'jenis' => $request->input("jenis.{$item['menu']->id}"),
        ]);
    }

    if ($request->metode_pembayaran === 'midtrans') {
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->kode_transaksi,
                'gross_amount' => $transaction->total_harga,
            ],
            'customer_details' => [
                'first_name' => $request->nama_pelanggan,
            ]
        ];

        $snapUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
        return redirect($snapUrl);
    }

    // return redirect()->route('menu')->with('success', 'Pesanan berhasil dibuat.');
    return view('public.manual_confirmation', [
    'kode_transaksi' => $transaction->kode_transaksi,
    'nama_pelanggan' => $transaction->nama_pelanggan, // tambahkan ini
]);
}


    public function showForm(Request $request){
          $menuDipilih = [];

    foreach ($request->jumlah ?? [] as $menuId => $qty) {
        if ($qty > 0) {
            $menu = Menu::find($menuId);
            if ($menu) {
                $menuDipilih[] = [
                    'menu' => $menu,
                    'jumlah' => $qty,
                    'subtotal' => $qty * $menu->harga,
                ];
            }
        }
    }

    if (count($menuDipilih) === 0) {
        return back()->with('error', 'Pilih menu terlebih dahulu.');
    }

    return view('public.order_form', compact('menuDipilih'));
    }

    //  public function midtransCallback(Request $request)
    // {
    //     $notif = new \Midtrans\Notification();

    //     $transaction = $notif->transaction_status;
    //     $orderId = $notif->order_id;

    //     $trx = Transaction::where('kode_transaksi', $orderId)->first();
    //     if (!$trx) return response()->json(['error' => 'not found'], 404);

    //     if ($transaction == 'settlement') {
    //         $trx->status = 'diproses';
    //     } elseif ($transaction == 'expire' || $transaction == 'cancel') {
    //         $trx->status = 'batal';
    //     }

    //     $trx->save();
    //     return response()->json(['success' => true]);
    // }

      public function success()
    {
        return view('public.success');
    }

    public function failed()
    {
        return view('public.failed');
    }

    public function detail($kode_transaksi){
        $transaction = Transaction::with('menuTransactions.menu')->where('kode_transaksi', $kode_transaksi)->firstOrFail();
        return view('public.order_detaile', compact('transaction'));
    }
}
