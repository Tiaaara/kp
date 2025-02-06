<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Popup; // Tambahkan model Popup
use Inertia\Inertia;
use Carbon\Carbon;
use App\Http\Controllers\NewsHomeController;

class BerandaController extends Controller
{
    public function index()
    {
        // Simpan data pengunjung
        Visitor::create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Hitung jumlah pengunjung
        $visitorCount = Visitor::count();
        $todayVisitorCount = Visitor::whereDate('created_at', Carbon::today())->count();
        $monthlyVisitorCount = Visitor::whereMonth('created_at', Carbon::now()->month)
                                      ->whereYear('created_at', Carbon::now()->year)
                                      ->count();
        $yearlyVisitorCount = Visitor::whereYear('created_at', Carbon::now()->year)->count();

        // Ambil data berita dari NewsController
        $newsHomeController = new NewsHomeController();
        $data = $newsHomeController->getBerita();

        // Ambil semua gambar popup dan gabungkan menjadi satu string
        $popupImages = Popup::pluck('image_popup')->toArray(); // Ambil semua gambar dari kolom `image_popup`
        $popup = [
            'image_popup' => implode(',', $popupImages) // Gabungkan array menjadi string dipisahkan koma
        ];

        // Kirim data ke halaman Beranda melalui Inertia
        return Inertia::render('Beranda/Beranda', [
            'visitorCount' => $visitorCount,
            'todayVisitorCount' => $todayVisitorCount,
            'monthlyVisitorCount' => $monthlyVisitorCount,
            'yearlyVisitorCount' => $yearlyVisitorCount,
            'mainNews' => $data['mainNews'],
            'newsCards' => $data['newsCards'],
            'popup' => $popup, // Kirim data popup ke frontend
        ]);
    }
}
