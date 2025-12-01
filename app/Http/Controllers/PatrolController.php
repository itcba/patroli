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
            // Validasi sederhana
            $validated = $request->validate([
                'nama_anggota_1' => 'required',
                'tanggal' => 'required',
                'patrol_data' => 'required', // Ini string JSON dari frontend
                'e_sign' => 'required',
            ]);

            // Parsing data dari format JS frontend
            $patrolDetails = json_decode($request->patrol_data, true);

            // Parsing tanda tangan (Nama|||Base64)
            $signParts = explode('|||', $request->e_sign);
            $esignName = $signParts[0];
            $esignImage = isset($signParts[1]) ? $signParts[1] : '';

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
                'patrol_details' => $patrolDetails, // Laravel otomatis menjadikannya JSON
                'esign_name' => $esignName,
                'esign_image' => $esignImage,
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