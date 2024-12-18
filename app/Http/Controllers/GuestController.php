<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuestController extends Controller
{
    // Menampilkan daftar tamu
    public function index()
    {
        // Pagination
        $guests = Guest::paginate(5); // Menampilkan 5 data per halaman
        return view('guest.index', compact('guests'));
        // Mengambil semua data tamu
        $guests = Guest::all();
        return view('guest.index', compact('guests')); // Pastikan view 'guest.index' ada
    }

    public function create()
    {
        return view('guest.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'foto' => 'required|string', // Pastikan Base64 valid
        ]);

        // Ambil data foto Base64
        $fotoData = $request->foto; 

        // Pisahkan data Base64 menjadi tipe dan konten
        if (preg_match('/^data:image\/(\w+);base64,/', $fotoData, $type)) {
            $imageType = strtolower($type[1]); // jpg, png, gif, etc.

            // Validasi tipe file
            if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                return back()->withErrors(['foto' => 'Format gambar tidak didukung!']);
            }

            $imageBase64 = base64_decode(substr($fotoData, strpos($fotoData, ',') + 1));

            // Generate nama file yang unik
            $fotoName = uniqid() . '.' . $imageType;

            // Simpan ke folder storage/app/public/uploads
            $filePath = 'uploads/' . $fotoName; // Path relatif untuk database
            Storage::disk('public')->put($filePath, $imageBase64);
        } else {
            return back()->withErrors(['foto' => 'Format data gambar tidak valid!']);
        }

        // Simpan data tamu ke database
        $guest = new Guest();
        $guest->nama = $request->nama;
        $guest->alamat = $request->alamat;
        $guest->tujuan = $request->tujuan;
        $guest->instansi = $request->instansi;
        $guest->no_hp = $request->no_hp;
        $guest->foto = $filePath; // Simpan path relatif
        $guest->tanggal = now(); // Simpan tanggal hari ini
        $guest->save();

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil disimpan!');
    }

    // Menampilkan form untuk mengedit data tamu
    public function edit($id)
    {
        // Mencari tamu berdasarkan ID
        $guest = Guest::findOrFail($id);
        return view('guest.edit', compact('guest')); // Pastikan view 'guest.edit' ada
    }

    // Mengupdate data tamu
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|string', // Validasi base64
        ]);

        // Mencari tamu berdasarkan ID
        $guest = Guest::findOrFail($id);

        // Jika ada foto baru, proses dan simpan foto
        if ($request->has('foto') && !empty($request->foto)) {
            $fotoData = $request->foto; 
            $image_parts = explode(";base64,", $fotoData);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            // Generate nama file foto yang unik
            $fotoName = uniqid() . '.' . $image_type;

            // Menyimpan foto ke folder public/uploads di storage
            $filePath = storage_path('app/public/uploads/' . $fotoName);  // Menyimpan di storage/app/public/uploads
            file_put_contents($filePath, $image_base64);

            // Hapus foto lama jika ada
            if ($guest->foto && file_exists(storage_path('app/public/uploads/' . $guest->foto))) {
                unlink(storage_path('app/public/uploads/' . $guest->foto));
            }

            // Update nama foto
            $guest->foto = 'uploads/' . $fotoName;
        }

        // Update data tamu
        $guest->nama = $request->nama;
        $guest->alamat = $request->alamat;
        $guest->tujuan = $request->tujuan;
        $guest->instansi = $request->instansi;
        $guest->no_hp = $request->no_hp;
        $guest->save();

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil diperbarui!');
    }

    // Menghapus data tamu
    public function destroy($id)
    {
        // Mencari tamu berdasarkan ID
        $guest = Guest::findOrFail($id);

        // Hapus foto terkait jika ada
        if ($guest->foto && file_exists(storage_path('app/public/uploads/' . $guest->foto))) {
            unlink(storage_path('app/public/uploads/' . $guest->foto));
        }

        // Hapus data tamu dari database
        $guest->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil dihapus!');
    }
}
