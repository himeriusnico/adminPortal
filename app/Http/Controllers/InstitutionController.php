<?php

namespace App\Http\Controllers;

use App\Models\EncryptedKey;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Institution::all();
        return view('institutions.index', compact('institutions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:institutions,name',
            'email' => 'required|email|unique:institutions,email',
            'alamat' => 'nullable|string|max:255',
            'passphrase' => 'required|string|min:8', 
        ]);

        DB::beginTransaction();

        try {
            // generate ECDSA keypair
            try {
                // catatan: eterministic only used when
                // you must be able to recreate the same key from the same secret (wallet seeds, backup systems, HD wallets).

                $saltKey = getenv('MASTER_SECRET');
                $userId = $request->user()->id;
                $uniqueString = $userId . $request->email . microtime(true) . $saltKey;
                $seed = hash('sha256', $uniqueString, true);

                $ec = EC::createKey('secp256k1', $seed);
                $privateKeyPem = $ec->toString('PKCS8');
                $publicKeyPem = $ec->getPublicKey()->toString('PKCS8');

                // non deterministic
                // sebenernya pake ini aja udah aman soalnya CSPRNG dan karenaa...
                // The private key is simply a random integer in [1, n-1] where n is the curve order (~10⁷⁷ possible values). 
                // The chance of collision is astronomically small — about 1 in 2¹²⁸ for any two keys.
                // source jurnal
                // $ec = EC::createKey('secp256k1');

                // $privateKeyPem = $ec->toString('PKCS8');
                // $publicKeyPem  = $ec->getPublicKey()->toString('PKCS8');
            } catch (Throwable $e) {
                Log::error("Generate ECDSA keypair failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                throw new \Exception("Gagal generate ECDSA keypair: " . $e->getMessage());
            }

            try {
                $iv = random_bytes(16);
                $salt = random_bytes(16);
                $derivedKey = hash_pbkdf2('sha256', $request->passphrase, $salt, 100000, 32, true);


                $encryptedPrivateKey = openssl_encrypt(
                    $privateKeyPem,
                    'AES-256-CBC',
                    $derivedKey,
                    0,
                    $iv
                );

                unset($derivedKey, $privateKeyPem);
            } catch (Throwable $e) {
                Log::error("Encrypt private key failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                throw new \Exception("Gagal mengenkripsi private key: " . $e->getMessage());
            }

            // simpan private key ke storage
            // try {
            //     $safeName = Str::slug($request->name, '_');
            //     $directory = 'keys/institutions/';
            //     $fileName = $safeName . '_private.pem';
            //     $filePath = $directory . $fileName;

            //     if (!Storage::exists($directory)) {
            //         Storage::makeDirectory($directory);
            //     }

            //     Storage::put($filePath, $privateKeyPem);

            //     // Atur permission file (opsional)
            //     $fullPath = storage_path('app/' . $filePath);
            //     if (file_exists($fullPath)) {
            //         @chmod($fullPath, 0600);
            //     }
            // } catch (Throwable $e) {
            //     throw new \Exception("Gagal menyimpan private key ke file: " . $e->getMessage());
            // }

            // simpan record institusi ke database
            try {
                $institution = Institution::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'alamat' => $request->alamat,
                    'public_key' => $publicKeyPem,
                    // HARUS DITANYAKAN KE PAK MIFTAH PRIVATE KEY ENAKANYA GIMANA DISIMPANNYA
                    // 'private_key_path' => $filePath,
                    // 'ca_cert' => ''
                ]);
            } catch (Throwable $e) {
                Log::error("Save institution failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                throw new \Exception("Gagal menyimpan data institusi ke database: " . $e->getMessage());
            }

            // $newInstitutionId = $institution->id;

            // // buat user admin untuk institusi
            // try {
            //     $directory = 'keys/institutions/';

            //     // $institution_id = $institution->id;
            //     $fileName = $newInstitutionId . "_private.pem";
            //     $filePath = $directory . $fileName;

            //     if (!Storage::disk('local')->exists($directory)) {
            //         Storage::disk('local')->makeDirectory($directory);
            //     }

            //     Storage::disk('local')->put($filePath, $privateKeyPem);

            //     $fullPath = storage_path('app/' . $filePath);
            //     if (file_exists($fullPath)) {
            //         @chmod($fullPath, 0600);
            //     }
            // } catch (Throwable $e) {
            //     $institution->delete();
            //     throw new \Exception("Gagal menyimpan private key ke file: " . $e->getMessage());
            // }

            try {
                $encryptedKey = EncryptedKey::create([
                    'institution_id' => $institution->id,
                    'encrypted_private_key' => $encryptedPrivateKey,
                    'iv' => bin2hex($iv),
                    'salt' => bin2hex($salt),
                    // 'created_by' => Auth::id(),
                ]);

                $institution->update(['encrypted_key_id' => $encryptedKey->id]);
            } catch (Throwable $e) {
                Log::error("Save encrypted key failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                $institution->delete();
                throw new \Exception("Gagal menyimpan data encrypted key: " . $e->getMessage());
            }

            try {
                $adminRole = Role::where('name', 'admin')->first();

                if (!$adminRole) {
                    throw new \Exception("Role 'admin' tidak ditemukan di tabel roles.");
                }

                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make('password'),
                    'role_id' => $adminRole->id,
                    'institution_id' => $institution->id
                ]);
            } catch (Throwable $e) {
                Log::error("Create admin user failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                $institution->delete();
                throw new \Exception("Gagal membuat user admin institusi: " . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Institusi berhasil ditambahkan, keypair dibuat dan dienkripsi.',
                'data' => [
                    'institution_id' => $institution->id,
                    'public_key' => $publicKeyPem,
                    'encrypted_key_id' => $encryptedKey->id
                ]
            ]);
        } catch (Throwable $e) {
            Log::error("Store institution process failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function show(Institution $institution)
    {
        // return view('institutions.show', compact('institution'));
        return response()->json([
            'success' => true,
            'data' => $institution
        ]);
    }

    public function update(Request $request, Institution $institution)
    {
        return response()->json([
            'success' => true,
            'message' => 'Institusi berhasil diperbarui (simulasi)'
        ]);
    }

    public function destroy(Institution $institution)
    {
        return response()->json([
            'success' => true,
            'message' => 'Institusi berhasil dihapus (simulasi)'
        ]);
    }

    public function setting()
    {
        $institutionId = Auth::user()->institution_id;

        // sebenere ga mungkin kejadian but well tulis sek ae 
        if (!$institutionId) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung ke institusi manapun.');
        }

        $faculties = Faculty::where('institution_id', $institutionId)
            ->withCount('programStudies')
            ->orderBy('name')
            ->get();

        $programStudies = ProgramStudy::where('university_id', $institutionId)
            ->with('faculty')
            ->orderBy('name')
            ->get();

        return view('institutions.settings', compact('faculties', 'programStudies'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
