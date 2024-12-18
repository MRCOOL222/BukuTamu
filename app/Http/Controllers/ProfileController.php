<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'password' => 'nullable|string|min:8',
            'foto' => 'nullable|string', // Foto optional
        ]);
    
        // Ambil user yang sedang login
        $user = auth()->user();
    
        // Update nama dan username
        $user->nama = $request->nama;
        $user->username = $request->username;
    
        // Cek apakah password baru diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        // Cek jika ada foto baru
        if ($request->filled('foto')) {
            // Proses penyimpanan foto
            $fotoData = $request->foto;
    
            if (preg_match('/^data:image\/(\w+);base64,/', $fotoData, $type)) {
                $imageType = strtolower($type[1]); // jpg, png, gif, dll.
    
                // Validasi format gambar
                if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    return back()->withErrors(['foto' => 'Format gambar tidak didukung!']);
                }
    
                // Decode gambar Base64
                $imageBase64 = base64_decode(substr($fotoData, strpos($fotoData, ',') + 1));
    
                // Nama file foto yang unik
                $fotoName = uniqid() . '.' . $imageType;
    
                // Path penyimpanan foto
                $filePath = 'uploads/profile/' . $fotoName;
    
                // Simpan foto ke storage
                Storage::disk('public')->put($filePath, $imageBase64);
    
                // Hapus foto lama jika ada
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
    
                // Simpan path foto baru
                $user->foto = $filePath;
            } else {
                return back()->withErrors(['foto' => 'Format data gambar tidak valid!']);
            }
        }
    
        // Simpan perubahan
        $user->save();
    
        // Redirect dengan session success
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}    