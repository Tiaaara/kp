<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class LambangController extends Controller
{
    public function index()
    {
        // Render halaman Vue tanpa mengirim data apa pun
        return Inertia::render('Profil/LambangDaerah');
    }
}
