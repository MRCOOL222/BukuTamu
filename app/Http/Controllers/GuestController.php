<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::paginate(5);
        return view('guest.index', compact('guests'));
    }

    public function create()
    {
        return view('guest.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'instansi' => 'required|string',
            'alamat' => 'required|string',
            'tujuan' => 'required|string',
            'no_hp' => 'required|numeric',
            'foto' => 'required|string',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan'
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
        ]);

        try {
            $filePath = $this->processBase64Image($request->foto);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        Guest::create(array_merge($request->all(), ['foto' => $filePath, 'tanggal' => now()]));

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil disimpan!');
    }

    public function edit($id)
    {
        $guest = Guest::findOrFail($id);
        return view('guest.edit', compact('guest'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_hp' => 'required|digits_between:11,12',
            'foto' => 'nullable|string',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan'
        ]);

        $guest = Guest::findOrFail($id);

        if ($request->has('foto') && !empty($request->foto)) {
            if ($guest->foto && Storage::disk('public')->exists($guest->foto)) {
                Storage::disk('public')->delete($guest->foto);
            }
            try {
                $guest->foto = $this->processBase64Image($request->foto);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        $guest->update($validated);

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);

        if ($guest->foto && Storage::disk('public')->exists($guest->foto)) {
            Storage::disk('public')->delete($guest->foto);
        }

        $guest->delete();

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil dihapus!');
    }

    private function processBase64Image($base64Image)
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            throw new \Exception('Format data gambar tidak valid!');
        }

        $imageType = strtolower($type[1]);

        if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new \Exception('Format gambar tidak didukung!');
        }

        $imageBase64 = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));

        if (strlen($imageBase64) < 100) {
            throw new \Exception('Data gambar terlalu kecil atau rusak!');
        }

        $fileName = uniqid() . '.' . $imageType;
        $filePath = 'uploads/' . $fileName;

        Storage::disk('public')->put($filePath, $imageBase64);

        return $filePath;
    }
}
