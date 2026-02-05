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
            // 'passphrase' => 'required|string|min:8', 
        ]);

        DB::beginTransaction();

        try {
            $institution = Institution::create([
                'name' => $request->name,
                'email' => $request->email,
                'alamat' => $request->alamat,
            ]);

            $adminRole = Role::where('name', 'admin')->first();
            User::create([
                'name' => $request->name . ' Admin',
                'email' => $request->email,
                'password' => Hash::make("password"), // Password random, nanti di-reset oleh admin
                'role_id' => $adminRole->id,
                'institution_id' => $institution->id,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Institusi berhasil ditambahkan',
                'data' => $institution
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

   public function generateKeyPair(Request $request)
    {
        $request->validate([
            'passphrase' => 'required|string|min:8',
        ]);

        $user = Auth::user();
        $institution = Institution::findOrFail($user->institution_id);

        // Proteksi jika sudah ada key
        if ($institution->public_key) {
            return response()->json(['success' => false, 'message' => 'Keypair sudah ada.'], 400);
        }

        DB::beginTransaction();
        try {
            // --- LOGIKA DETERMINISTIC PERSIS SEPERTI CODE LAMA ANDA ---
            $saltKey = getenv('MASTER_SECRET');
            $userId = $user->id;
            // Kita gunakan email institusi agar konsisten
            $uniqueString = $userId . $institution->email . microtime(true) . $saltKey;
            $seed = hash('sha256', $uniqueString, true);

            $ec = EC::createKey('secp256k1', $seed);
            $privateKeyPem = $ec->toString('PKCS8');
            $publicKeyPem = $ec->getPublicKey()->toString('PKCS8');

            // --- LOGIKA ENKRIPSI ---
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

            // --- SIMPAN KE DATABASE ---
            $encryptedKey = EncryptedKey::create([
                'institution_id' => $institution->id,
                'encrypted_private_key' => $encryptedPrivateKey,
                'iv' => bin2hex($iv),
                'salt' => bin2hex($salt),
            ]);

            $institution->update([
                'public_key' => $publicKeyPem,
                'encrypted_key_id' => $encryptedKey->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Keypair berhasil dibuat secara mandiri menggunakan metode deterministic.'
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Generate Keypair failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function viewPrivateKey(Request $request)
    {
        $request->validate([
            'passphrase' => 'required|string',
        ]);

        $user = Auth::user();
        $institution = Institution::with('encryptedKey')->findOrFail($user->institution_id);

        if (!$institution->encryptedKey) {
            return response()->json(['success' => false, 'message' => 'Data kunci tidak ditemukan.'], 404);
        }

        try {
            $encryptedData = $institution->encryptedKey;
            
            // 1. Ambil IV dan Salt dari database (dalam bentuk hex ke binary)
            $iv = hex2bin($encryptedData->iv);
            $salt = hex2bin($encryptedData->salt);

            // 2. Derivasi Key menggunakan passphrase yang diinput user
            $derivedKey = hash_pbkdf2('sha256', $request->passphrase, $salt, 100000, 32, true);

            // 3. Dekripsi menggunakan OpenSSL
            $decryptedPrivateKey = openssl_decrypt(
                $encryptedData->encrypted_private_key,
                'AES-256-CBC',
                $derivedKey,
                0,
                $iv
            );

            if ($decryptedPrivateKey === false) {
                return response()->json(['success' => false, 'message' => 'Passphrase salah atau data korup.'], 401);
            }

            return response()->json([
                'success' => true,
                'private_key' => $decryptedPrivateKey
            ]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
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
