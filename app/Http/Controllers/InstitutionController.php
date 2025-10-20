<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Crypt\EC;
use Illuminate\Support\Str;
use Throwable;

class InstitutionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->user_type !== 'admin') {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar semua institusi.
     */
    public function index()
    {
        $institutions = Institution::latest()->paginate(10);
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
            // Generate ECDSA keypair menggunakan phpseclib
            $ec = EC::createKey('secp256k1');
            $privateKeyPem = $ec->toString('PKCS8');
            $publicKeyPem  = $ec->getPublicKey()->toString('PKCS8');

            // Simpan private key ke file (sementara untuk testing)
            $safeName = Str::slug($request->name, '_');
            $directory = 'keys/institutions/';
            $fileName = $safeName . '_private.pem';
            $filePath = $directory . $fileName;

            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }

            Storage::put($filePath, $privateKeyPem);

            // Atur permission file (opsional)
            try {
                $fullPath = storage_path('app/' . $filePath);
                if (file_exists($fullPath)) {
                    @chmod($fullPath, 0600);
                }
            } catch (Throwable $e) {
                // Abaikan error chmod
            }

            // Simpan record institusi ke database
            $institution = Institution::create([
                'name' => $request->name,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'public_key' => $publicKeyPem,
                'private_key_path' => $filePath,
                'ca_cert' => ''
            ]);

            // Response JSON
            return response()->json([
                'success' => true,
                'message' => 'Institusi berhasil ditambahkan dan keypair dibuat',
                'data' => [
                    'id' => $institution->id,
                    'public_key' => $publicKeyPem,
                    'private_key_path' => $filePath,
                ]
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate ECDSA keypair: ' . $e->getMessage()
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
}
