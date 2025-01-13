<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuestController extends Controller
{
    // Menampilkan daftar tamu
    public function index(Request $request)
    {
        $search = $request->input('search');

        $guests = Guest::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', "%$search%")
                             ->orWhere('alamat', 'like', "%$search%")
                             ->orWhere('tujuan', 'like', "%$search%")
                             ->orWhere('instansi', 'like', "%$search%")
                             ->orWhere('no_hp', 'like', "%$search%");
            })
            ->paginate(5);

        return view('guest.index', compact('guests'));
    }

    // Menampilkan form tambah tamu
    public function create()
    {
        return view('guest.create');
    }

    // Menyimpan data tamu baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'foto' => 'required|string', // Validasi untuk Base64
        ]);

        // Proses Base64 ke file
        $filePath = $this->processBase64Image($request->foto);

        // Simpan data tamu ke database
        $guest = new Guest();
        $guest->nama = $request->nama;
        $guest->alamat = $request->alamat;
        $guest->tujuan = $request->tujuan;
        $guest->instansi = $request->instansi;
        $guest->no_hp = $request->no_hp;
        $guest->foto = $filePath; // Simpan path relatif
        $guest->tanggal = now();
        $guest->save();

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil disimpan!');
    }

    // Menampilkan form edit tamu
    public function edit($id)
    {
        $guest = Guest::findOrFail($id);
        return view('guest.edit', compact('guest'));
    }

    // Memperbarui data tamu
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|string',
        ]);

        $guest = Guest::findOrFail($id);

        if ($request->has('foto') && !empty($request->foto)) {
            // Hapus foto lama jika ada
            if ($guest->foto && Storage::disk('public')->exists($guest->foto)) {
                Storage::disk('public')->delete($guest->foto);
            }

            // Proses Base64 ke file
            $guest->foto = $this->processBase64Image($request->foto);
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
        $guest = Guest::findOrFail($id);

        // Hapus foto terkait jika ada
        if ($guest->foto && Storage::disk('public')->exists($guest->foto)) {
            Storage::disk('public')->delete($guest->foto);
        }

        $guest->delete();

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil dihapus!');
    }

    // Fungsi untuk memproses Base64 ke file
    private function processBase64Image($base64Image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $imageType = strtolower($type[1]);

            // Validasi tipe file
            if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception('Format gambar tidak didukung!');
            }

            $imageBase64 = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));
            $fileName = uniqid() . '.' . $imageType;
            $filePath = 'uploads/' . $fileName;

            // Simpan file ke storage
            Storage::disk('public')->put($filePath, $imageBase64);

            return $filePath;
        } else {
            throw new \Exception('Format data gambar tidak valid!');
        }
    }
}
