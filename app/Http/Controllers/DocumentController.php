<?php
namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{
    // Menampilkan semua dokumen
    public function index()
    {
        $documents = Document::all(); // Mengambil semua dokumen
        return inertia('DocumentsList', ['documents' => $documents]);
    }

    public function uploadPage()
    {
        $documents = Document::all(); // Mengambil semua dokumen
        return inertia('UploadDocument', ['documents' => $documents]);
    }
    
    // Menyimpan dokumen dengan kategori
    public function store(Request $request)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx',
            'category' => 'required|string|max:100', // Validasi kategori wajib diisi
        ]);
    
        // Menyimpan file di disk 'public'
        $path = $request->file('file')->store('documents', 'public');
    
        Document::create([
            'document_name' => $request->document_name,
            'file_path' => $path,
            'upload_date' => now(),
            'category' => $request->category, // Menyimpan kategori ke database
        ]);
    
        // Redirect ke halaman upload-document setelah berhasil menyimpan
        return redirect()->route('documents.upload')->with('success', 'Document uploaded successfully!');
    }
    
    
    // Mengunduh dokumen dan meningkatkan jumlah unduhan
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // Meningkatkan jumlah unduhan
        $document->download_count += 1;
        $document->save();

        // Mengembalikan file untuk diunduh
        return response()->download(storage_path('app/public/' . $document->file_path));
    }

    // Mengupdate dokumen
    public function update(Request $request, $id)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
        ]);
    
        $document = Document::findOrFail($id);
        $document->update([
            'document_name' => $request->document_name,
        ]);
    
        // Redirect ke halaman upload-document setelah berhasil mengupdate
        return redirect()->route('documents.upload')->with('success', 'Document updated successfully!');
    }

    // Menghapus dokumen
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
    
        // Hapus file dari storage jika ada
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
    
        // Hapus data dokumen dari database
        $document->delete();
    
        // Redirect ke halaman upload-document setelah berhasil menghapus
        return redirect()->route('documents.upload')->with('success', 'Document deleted successfully!');
    }

    public function DataStatistik()
    {
        $documents = Document::where('category', 'Data Statistik')->get();
        return Inertia::render('InformasiPublik/DataStatistik', [
            'documents' => $documents
        ]);
    }

    public function PeraturanBupati()
    {
        $documents = Document::where('category', 'Peraturan Bupati')->get();
        return Inertia::render('InformasiPublik/PeraturanBupati', [
            'documents' => $documents
        ]);
    }
    public function LaporanKeuangan2020()
    {
        $documents = Document::where('category', 'Laporan Keuangan 2020')->get();
        return Inertia::render('InformasiPublik/LapKeu2020', [
            'documents' => $documents
        ]);
    }
    public function LaporanKeuangan2021()
    {
        $documents = Document::where('category', 'Laporan Keuangan 2021')->get();
        return Inertia::render('InformasiPublik/LapKeu2021', [
            'documents' => $documents
        ]);
    }
    public function LaporanKeuangan2022()
    {
        $documents = Document::where('category', 'Laporan Keuangan 2022')->get();
        return Inertia::render('InformasiPublik/LapKeu2022', [
            'documents' => $documents
        ]);
    }
    public function LaporanKeuangan2023()
    {
        $documents = Document::where('category', 'Laporan Keuangan 2023')->get();
        return Inertia::render('InformasiPublik/LapKeu2023', [
            'documents' => $documents
        ]);
    }
    public function LaporanKeuangan2024()
    {
        $documents = Document::where('category', 'Laporan Keuangan 2024')->get();
        return Inertia::render('InformasiPublik/LapKeu2024', [
            'documents' => $documents
        ]);
    }
    public function LaporanKeuangan2025()
    {
        $documents = Document::where('category', 'Laporan Keuangan 2025')->get();
        return Inertia::render('InformasiPublik/LapKeu2025', [
            'documents' => $documents
        ]);
    }
}

