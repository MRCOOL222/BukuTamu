<?php

namespace App\Http\Controllers;

use App\Models\Guest; // Pastikan untuk menggunakan model Guest
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guests = Guest::all(); // Ambil semua data tamu
        return view('Guest.index', compact('guests')); // Menggunakan 'Guest.index' untuk path view yang benar
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Guest.create'); // Menggunakan 'Guest.create' untuk path view yang benar
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'foto' => 'required|string',
        ]);

        // Decode foto base64
        $fotoData = $request->foto;
        $image_parts = explode(";base64,", $fotoData);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $fotoName = uniqid() . '.' . $image_type;
        $filePath = public_path('uploads/' . $fotoName);

        // Simpan foto ke folder uploads
        file_put_contents($filePath, $image_base64);

        // Simpan data tamu
        $guest = new Guest();
        $guest->nama = $request->nama;
        $guest->alamat = $request->alamat;
        $guest->tujuan = $request->tujuan;
        $guest->no_hp = $request->no_hp;
        $guest->foto = $fotoName;
        $guest->save();

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guest = Guest::findOrFail($id); // Cari data tamu berdasarkan ID
        return view('Guest.show', compact('guest')); // Menggunakan 'Guest.show' untuk path view yang benar
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guest = Guest::findOrFail($id); // Cari data tamu berdasarkan ID
        return view('Guest.edit', compact('guest')); // Menggunakan 'Guest.edit' untuk path view yang benar
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $guest = Guest::findOrFail($id); // Ambil data tamu berdasarkan ID
        $guest->nama = $request->nama;
        $guest->tujuan = $request->tujuan;
        $guest->instansi = $request->instansi;
        $guest->alamat = $request->alamat;
        $guest->no_hp = $request->no_hp;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($guest->foto && Storage::exists('public/foto_tamu/' . $guest->foto)) {
                Storage::delete('public/foto_tamu/' . $guest->foto);
            }
            $file = $request->file('foto');
            $path = $file->store('public/foto_tamu');
            $guest->foto = basename($path);
        }

        $guest->save();

        return redirect()->route('guest.index')->with('success', 'Data Tamu berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guest = Guest::findOrFail($id); // Cari tamu berdasarkan ID
        
        // Hapus foto jika ada
        if ($guest->foto && Storage::exists('public/foto_tamu/' . $guest->foto)) {
            Storage::delete('public/foto_tamu/' . $guest->foto);
        }
        
        $guest->delete();

        return redirect()->route('guest.index')->with('success', 'Data Tamu berhasil dihapus.');
    }
}
