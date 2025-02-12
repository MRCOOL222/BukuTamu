<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\WorkField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::with('workField')->paginate(5);
        return view('guest.index', compact('guests'));
    }

    public function create()
    {
        $workFields = WorkField::all();
        return view('guest.create', compact('workFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'instansi' => 'required|in:Dinas,Non Kedinasan',
            'nama_instansi' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'tujuan_bidang' => 'required|exists:work_fields,id',
            'tujuan_pengunjung' => 'nullable|string',
            'no_hp' => 'required|numeric',
            'foto' => 'required|string',
            'jenis_kelamin' => 'required|in:l,p',
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
        ]);

        try {
            $filePath = $this->processBase64Image($request->foto);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        Guest::create([
            'nama' => $request->nama,
            'instansi' => $request->instansi,
            'nama_instansi' => $request->nama_instansi,
            'alamat' => $request->alamat,
            'tujuan_bidang' => $request->tujuan_bidang, 
            'tujuan_pengunjung' => $request->tujuan_pengunjung,
            'no_hp' => $request->no_hp,
            'foto' => $filePath,
            'tanggal' => now(),
            'jenis_kelamin' => $request->jenis_kelamin,
            'status' => 'sedang kunjungan',
        ]);

        return redirect()->route('guest.index')->with('success', 'Data tamu berhasil disimpan!');
    }

    public function destroy(Guest $guest)
    {
        try {
            // Hapus file foto jika ada
            if ($guest->foto) {
                // Ambil path file tanpa URL
                $filePath = str_replace('/storage/', '', $guest->getRawOriginal('foto'));
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // Hapus data tamu
            $guest->delete();

            return redirect()->route('guest.index')
                ->with('success', 'Data tamu berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('guest.index')
                ->with('error', 'Gagal menghapus data tamu: ' . $e->getMessage());
        }
    }

    private function processBase64Image($base64Image)
    {
        if (!$base64Image) {
            throw new \Exception('Gambar belum diambil!');
        }

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

    public function updateStatus(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);
        $guest->status = $request->status;
        $guest->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui!']);
    }

    

}