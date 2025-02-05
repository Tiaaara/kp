<?php

namespace App\Http\Controllers;

use App\Models\Popup;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    public function index()
    {
        $popups = Popup::all()->map(function ($popup) {
            return [
                'id' => $popup->id,
                'image_url' => asset('storage/' . $popup->image_popup)
            ];
        });

        return Inertia::render('Popups/Index', ['popups' => $popups]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_popup' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        if ($request->hasFile('image_popup')) {
            // Simpan gambar ke storage
            $path = $request->file('image_popup')->store('popups', 'public');

            // Simpan data ke database
            Popup::create([
                'image_popup' => $path
            ]);
        }

        return redirect()->route('popups.index')->with('success', 'Gambar berhasil diupload!');
    }

    public function destroy(Popup $popup)
    {
        // Hapus gambar dari storage
        Storage::disk('public')->delete($popup->image_popup);
        
        // Hapus data dari database
        $popup->delete();

        return redirect()->route('popups.index')->with('success', 'Gambar berhasil dihapus!');
    }
}
