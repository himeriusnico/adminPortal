<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Institution;
use App\Models\ProgramStudy;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Crypt\EC;
use Illuminate\Support\Str;
use Throwable;

class InstitutionController extends Controller
{
    /**
     * FUNGSI __construct() TELAH DIHAPUS.
     * * Otorisasi sudah ditangani oleh middleware 'role:super_admin'
     * di routes/web.php.
     */

    /**
     * Menampilkan daftar semua institusi.
     */
    public function index()
    {
        $institutions = Institution::all();
        return view('institutions.index', compact('institutions'));
    }

    /**
     * Menyimpan institusi baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:institutions,email',
            'alamat' => 'nullable|string|max:255',
        ]);


        try {
            // Tahap 1: Generate ECDSA keypair
            try {
                $ec = EC::createKey('secp256k1');
                $privateKeyPem = $ec->toString('PKCS8');
                $publicKeyPem  = $ec->getPublicKey()->toString('PKCS8');
            } catch (Throwable $e) {
                throw new \Exception("Gagal generate ECDSA keypair: " . $e->getMessage());
            }

            // Tahap 2: Simpan private key ke storage
            try {
                $safeName = Str::slug($request->name, '_');
                $directory = 'keys/institutions/';
                $fileName = $safeName . '_private.pem';
                $filePath = $directory . $fileName;

                if (!Storage::exists($directory)) {
                    Storage::makeDirectory($directory);
                }

                Storage::put($filePath, $privateKeyPem);

                // Atur permission file (opsional)
                $fullPath = storage_path('app/' . $filePath);
                if (file_exists($fullPath)) {
                    @chmod($fullPath, 0600);
                }
            } catch (Throwable $e) {
                throw new \Exception("Gagal menyimpan private key ke file: " . $e->getMessage());
            }

            // Tahap 3: Simpan record institusi ke database
            try {
                $institution = Institution::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'alamat' => $request->alamat,
                    'public_key' => $publicKeyPem,
                    // 'private_key_path' => $filePath,
                    'ca_cert' => ''
                ]);
            } catch (Throwable $e) {
                throw new \Exception("Gagal menyimpan data institusi ke database: " . $e->getMessage());
            }

            // Tahap 4: Buat user admin untuk institusi
            try {
                $institution_id = $institution->id;
                $adminRole = Role::where('name', 'admin')->first();

                if (!$adminRole) {
                    throw new \Exception("Role 'admin' tidak ditemukan di tabel roles.");
                }

                $newAdminUser = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make('password'), // Password default sementara
                    'role_id' => $adminRole->id,
                    'institution_id' => $institution_id
                ]);
            } catch (Throwable $e) {
                throw new \Exception("Gagal membuat user admin institusi: " . $e->getMessage());
            }

            // Jika semua sukses
            return response()->json([
                'success' => true,
                'message' => 'Institusi berhasil ditambahkan dan keypair dibuat',
                'data' => [
                    'id' => $institution->id,
                    'public_key' => $publicKeyPem,
                    // 'private_key_path' => $filePath,
                ]
            ]);
        } catch (Throwable $e) {
            // Tangkap error dari tahap mana pun
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null // hanya tampil jika APP_DEBUG=true
            ], 500);
        }
    }


    /**
     * Menampilkan detail institusi.
     */
    public function show(Institution $institution)
    {
        // return view('institutions.show', compact('institution'));
        return response()->json([
            'success' => true,
            'data' => $institution
        ]);
    }

    /**
     * Mengupdate institusi.
     */
    public function update(Request $request, Institution $institution)
    {
        // Untuk sementara, hanya return success message
        return response()->json([
            'success' => true,
            'message' => 'Institusi berhasil diperbarui (simulasi)'
        ]);
    }

    /**
     * Menghapus institusi.
     */
    public function destroy(Institution $institution)
    {
        // Untuk sementara, hanya return success message
        return response()->json([
            'success' => true,
            'message' => 'Institusi berhasil dihapus (simulasi)'
        ]);
    }

    public function setting()
    {
        // 1. Dapatkan ID institusi dari user yang sedang login (role 'admin')
        $institutionId = Auth::user()->institution_id;

        // Tambahkan pengaman jika admin tidak terikat ke institusi (walaupun seharusnya tidak terjadi)
        if (!$institutionId) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung ke institusi manapun.');
        }

        // 2. Ambil data Fakultas (Faculties)
        // Gunakan withCount('programStudies') untuk mendapatkan jumlah Prodi di setiap Fakultas,
        // sesuai dengan yang Anda tampilkan di Blade: {{ $faculty->program_studies_count }}
        $faculties = Faculty::where('institution_id', $institutionId)
            ->withCount('programStudies')
            ->orderBy('name')
            ->get();

        // 3. Ambil data Program Studi (ProgramStudies)
        // Gunakan with('faculty') untuk eager load nama fakultas,
        // sesuai dengan Blade: {{ $program->faculty->name }}
        $programStudies = ProgramStudy::where('university_id', $institutionId)
            ->with('faculty')
            ->orderBy('name')
            ->get();

        // 4. Kirim kedua variabel ke view
        return view('institutions.settings', compact('faculties', 'programStudies'));
    }
}
