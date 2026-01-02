<?php

namespace App\Http\Controllers;

use App\Models\Patrol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatrolController extends Controller
{
    // Halaman Utama
    public function index()
    {
        return view('patrol.index');
    }

    // API untuk mengambil data (agar fitur Filter JS di frontend tetap jalan)
    public function list()
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $data = Patrol::orderBy('created_at', 'desc')->get();
        return response()->json($data);
    }

    // Tampilkan form edit (admin only)
    public function edit($id)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized');
        }

        $patrol = Patrol::findOrFail($id);
        return view('patrol.edit', ['patrol' => $patrol]);
    }

    // Update data (admin only)
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $patrol = Patrol::findOrFail($id);
        $validated = $request->validate([
            'nama_anggota_1' => 'required',
            'tanggal' => 'required',
        ]);

        $patrol->nama_anggota_1 = $request->nama_anggota_1;
        $patrol->nama_anggota_2 = $request->nama_anggota_2;
        $patrol->nama_anggota_3 = $request->nama_anggota_3;
        $patrol->tanggal = $request->tanggal;
        $patrol->keterangan_absensi = $request->keterangan_absensi;
        $patrol->save();

        return redirect('/')->with('success', 'Patrol updated');
    }

    // Simpan Data
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'nama_anggota_1' => 'required',
            'tanggal' => 'required',
            'patrol_data' => 'required',
            'e_sign' => 'required',
            'patrol_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar per entry
        ]);

        $patrolDetails = json_decode($request->patrol_data, true);

        // ==============================
        // PROSES TANDA TANGAN BASE64
        // ==============================
        $signParts = explode('|||', $request->e_sign);
        $esignName = $signParts[0];
        $base64Image = $signParts[1] ?? null;

        $storedImage = null;

        if ($base64Image) {
            // Buang prefix seperti "data:image/png;base64,"
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            $imageData = base64_decode($base64Image);

            // Nama file unik
            $fileName = 'sign_' . time() . '.png';

            // Pastikan direktori ada: storage/app/public/signatures
            $dir = storage_path('app/public/signatures');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Simpan file ke disk
            $fullPath = $dir . DIRECTORY_SEPARATOR . $fileName;
            file_put_contents($fullPath, $imageData);

            // Simpan hanya filename ke database
            $storedImage = $fileName;
        }

        // ==============================
        // PROSES GAMBAR PATROLI PER ENTRY
        // ==============================
        $patrolImages = $request->file('patrol_images', []);
        foreach ($patrolDetails as $index => &$detail) {
            $storedImage = null;
            if (isset($patrolImages[$index]) && $patrolImages[$index]->isValid()) {
                $image = $patrolImages[$index];
                $fileName = 'patrol_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();

                // Pastikan direktori ada: storage/app/public/patrols
                $dir = storage_path('app/public/patrols');
                if (! is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                // Simpan file
                $image->move($dir, $fileName);
                $storedImage = $fileName;
            }
            $detail['gambar'] = $storedImage;
        }

        Patrol::create([
            'nama_anggota_1' => $request->nama_anggota_1,
            'nama_anggota_2' => $request->nama_anggota_2,
            'nama_anggota_3' => $request->nama_anggota_3,
            'hari' => $request->hari,
            'tanggal' => $request->tanggal,
            'jam_dinas' => $request->jam_dinas,
            'shift' => $request->shift,
            'jabatan' => $request->jabatan,
            'area' => $request->area,
            'keterangan_absensi' => $request->keterangan_absensi,

            'patrol_details' => $patrolDetails,

            'esign_name' => $esignName,
            'esign_image' => $storedImage, // FILE URL, BUKAN BASE64
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan']);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}


    // Hapus Data
    public function destroy($id)
    {
        $patrol = Patrol::find($id);
        if ($patrol) {
            $patrol->delete();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 404);
    }
}