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
        $guests = Guest::paginate(5);

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
            'no_hp' => 'required|digits_between:11,12',
            'foto' => 'required|string', // Validasi untuk Base64
        ]);

        try {
            $filePath = $this->processBase64Image($request->foto);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $guest = new Guest();
        $guest->fill($validated);
        $guest->foto = $filePath;
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
            'no_hp' => 'required|digits_between:11,12',
            'foto' => 'nullable|string',
        ]);

        $guest = Guest::findOrFail($id);

        // Hanya ganti foto jika ada foto baru
        if ($request->has('foto') && !empty($request->foto)) {
            // Hapus foto lama jika ada
            if ($guest->foto && Storage::disk('public')->exists($guest->foto)) {
                Storage::disk('public')->delete($guest->foto);
            }

            // Proses Base64 ke file
            try {
                $guest->foto = $this->processBase64Image($request->foto);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
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
        // Pastikan Base64 formatnya benar
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            throw new \Exception('Format data gambar tidak valid!');
        }

        $imageType = strtolower($type[1]);

        // Validasi tipe file
        if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new \Exception('Format gambar tidak didukung!');
        }

        // Decode base64 image
        $imageBase64 = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));

        // Validasi panjang data Base64 (minimumnya harus cukup besar)
        if (strlen($imageBase64) < 100) {
            throw new \Exception('Data gambar terlalu kecil atau rusak!');
        }

        $fileName = uniqid() . '.' . $imageType;
        $filePath = 'uploads/' . $fileName;

        // Simpan file ke storage
        Storage::disk('public')->put($filePath, $imageBase64);

        return $filePath;
    }
}
