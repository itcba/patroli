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
        $patrol->tanggal = $request->tanggal;
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
            'patrol_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Validasi gambar per entry
        ]);

        $patrolDetails = json_decode($request->patrol_data, true);

        // ==============================
        // PROSES TANDA TANGAN BASE64
        // ==============================
        $esignName = ''; // No longer used
        $base64Image = $request->e_sign;

        $esignStoredImage = null;

        if ($base64Image) {
            // Buang prefix seperti "data:image/png;base64,"
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            $imageData = base64_decode($base64Image);

            if ($imageData === false) {
                throw new \Exception('Invalid base64 data for e-signature');
            }

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
            $esignStoredImage = $fileName;
        }

        if (!$esignStoredImage) {
            throw new \Exception('Failed to process e-signature image');
        }

        // ==============================
        // PROSES GAMBAR PATROLI PER ENTRY
        // ==============================
        $patrolImages = array_values($request->file('patrol_images', []));
        \Log::info('Total patrol images received: ' . count($patrolImages));
        $imageIndex = 0; // Track position in the flat array
        $allStoredImages = [];

        foreach ($patrolDetails as $index => &$detail) {
            $storedImages = [];
            // Each entry can have up to 5 images
            for ($i = 0; $i < 5; $i++) {
                if (isset($patrolImages[$imageIndex])) {
                    $image = $patrolImages[$imageIndex];
                    \Log::info("Checking file at index $imageIndex: isValid=" . ($image->isValid() ? 'true' : 'false') . ", originalName=" . $image->getClientOriginalName() . ", size=" . $image->getSize() . ", mime=" . $image->getMimeType());
                    if ($image->isValid()) {
                        $fileName = 'patrol_' . time() . '_' . $index . '_' . $i . '.' . $image->getClientOriginalExtension();

                        // Pastikan direktori ada: storage/app/public/patrols
                        $dir = storage_path('app/public/patrols');
                        if (! is_dir($dir)) {
                            mkdir($dir, 0755, true);
                        }

                        // Simpan file
                        $image->move($dir, $fileName);
                        if (file_exists($dir . DIRECTORY_SEPARATOR . $fileName)) {
                            $storedImages[] = $fileName;
                            $allStoredImages[] = $fileName;
                            \Log::info("File saved: $fileName");
                        } else {
                            \Log::error("Failed to save file: $fileName");
                        }
                    } else {
                        \Log::warning("File at index $imageIndex is not valid. Error: " . implode(', ', $image->getError()));
                    }
                } else {
                    \Log::info("No file at index $imageIndex");
                }
                $imageIndex++;
            }
            \Log::info("Entry $index stored images: " . count($storedImages));
            $detail['gambar'] = $storedImages; // Store as array per detail
        }

        Patrol::create([
            'nama_anggota_1' => $request->nama_anggota_1,
            'hari' => $request->hari,
            'tanggal' => $request->tanggal,
            'shift' => $request->shift,
            'jabatan' => $request->jabatan,
            'area' => $request->area,

            'patrol_details' => $patrolDetails,

            'esign_image' => $esignStoredImage, // FILE URL, BUKAN BASE64
            'patrol_image' => $allStoredImages, // Array of filenames
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