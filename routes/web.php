<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes - StepUp Supply (Kelompok 03 - 05SIFE001)
|--------------------------------------------------------------------------
*/

$harga_produk_list = [
    "Nike Air Force 1 '07 White" => 1549000,
    "Adidas Samba OG Black White" => 2200000,
    "New Balance 550 White Grey" => 1999000,
    "Vans Old Skool Black/White" => 999000,
    "Carhartt WIP Pocket Tee Black" => 650000,
];

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response('OK', 200)->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response('ERROR', 500);
    }
});

Route::get('/', function () {
    return view('welcome');
});

// ROUTE ORDER: Dilindungi wajib login
Route::post('/order', function (Request $request) use ($harga_produk_list) {
    if (!session('user_name')) {
        return redirect('/?open_login=1')->with('error', 'Akses ditolak! Silakan login terlebih dahulu untuk memesan.');
    }

    $nama = $request->input('nama_pelanggan');
    $produk = $request->input('produk');
    $ukuran = $request->input('ukuran');
    $harga_int = $harga_produk_list[$produk] ?? 1500000;

    DB::table('pesanan')->insert([
        'nama_pelanggan' => $nama,
        'produk' => $produk,
        'ukuran' => $ukuran,
        'tanggal' => now()
    ]);

    $cart = session()->get('cart', []);
    $cart[] = [
        'nama_pelanggan' => $nama,
        'produk' => $produk,
        'ukuran' => $ukuran,
        'harga_int' => $harga_int,
        'harga' => 'Rp ' . number_format($harga_int, 0, ',', '.'),
        'waktu' => now()->format('H:i')
    ];
    session(['cart' => $cart]);

    return redirect('/?open_cart=1')->with('sukses', 'Pesanan berhasil disimpan & ditambahkan ke Cart!');
});

Route::post('/cart/clear', function () {
    session()->forget('cart');
    return redirect('/?open_cart=1')->with('sukses', 'Keranjang belanja berhasil dikosongkan.');
});

// ROUTE CHECKOUT: Dilindungi wajib login
Route::post('/checkout', function (Request $request) {
    if (!session('user_name')) {
        return redirect('/?open_login=1')->with('error', 'Akses ditolak! Silakan login untuk checkout.');
    }

    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect('/')->with('sukses', 'Keranjang Anda kosong.');
    }

    $nama = $request->input('nama');
    $no_hp = $request->input('no_hp');
    $alamat = $request->input('alamat');
    $metode_pembayaran = $request->input('metode_pembayaran');
    $kode_transaksi = 'TRX-' . strtoupper(substr(md5(time()), 0, 8));

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['harga_int'] ?? 1500000;
    }

    $kode_pembayaran = '';
    $is_qris = false;

    if (str_contains($metode_pembayaran, 'BCA')) {
        $kode_pembayaran = '88001' . rand(10000000, 99999999);
    } elseif (str_contains($metode_pembayaran, 'Mandiri')) {
        $kode_pembayaran = '88700' . rand(10000000, 99999999);
    } elseif (str_contains($metode_pembayaran, 'BRI/BNI') || str_contains($metode_pembayaran, 'Virtual Account')) {
        $kode_pembayaran = '98801' . rand(10000000, 99999999);
    } elseif (str_contains($metode_pembayaran, 'QRIS')) {
        $is_qris = true;
        $kode_pembayaran = 'QRIS-STEPUP-' . strtoupper(substr(md5(time()), 0, 6));
    } else {
        $kode_pembayaran = 'COD-' . rand(100000, 999999);
    }

    $payment_pending = [
        'kode_transaksi' => $kode_transaksi,
        'nama' => $nama,
        'no_hp' => $no_hp,
        'alamat' => $alamat,
        'metode_pembayaran' => $metode_pembayaran,
        'kode_pembayaran' => $kode_pembayaran,
        'is_qris' => $is_qris,
        'items' => $cart,
        'total_int' => $total,
        'total' => 'Rp ' . number_format($total, 0, ',', '.'),
        'tanggal' => now()->format('d M Y, H:i') . ' WIB'
    ];

    session(['payment_pending' => $payment_pending]);

    return redirect('/?open_payment=1')->with('sukses', 'Pesanan dibuat! Silakan selesaikan pembayaran.');
});

Route::post('/pay-confirm', function () {
    $pending = session('payment_pending');
    if (!$pending) {
        return redirect('/')->with('sukses', 'Tidak ada pembayaran pending.');
    }
    session(['invoice' => $pending]);
    session()->forget(['payment_pending', 'cart']);

    return redirect('/?open_invoice=1')->with('sukses', 'Pembayaran Berhasil Dikonfirmasi! Transaksi Lunas.');
});

Route::post('/login', function (Request $request) {
    $nama = $request->input('name');
    if (!$nama && $request->input('email')) {
        $nama = explode('@', $request->input('email'))[0];
    }
    $nama = ucfirst($nama ?? 'User');
    session(['user_name' => $nama]);
    return redirect('/')->with('sukses', "Selamat datang kembali, $nama!");
});

Route::post('/register', function (Request $request) {
    $nama = ucfirst($request->input('name', 'User'));
    session(['user_name' => $nama]);
    return redirect('/')->with('sukses', "Akun berhasil dibuat! Selamat datang, $nama.");
});

Route::post('/logout', function () {
    session()->forget('user_name');
    return redirect('/')->with('sukses', 'Berhasil Log Out.');
});