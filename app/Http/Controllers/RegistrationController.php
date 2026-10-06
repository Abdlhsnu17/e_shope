<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationController extends Controller
{
    /** Sub fitur: Formulir Pendaftaran */
    public function create()
    {
        return view('public.pendaftaran.form', ['profil' => SchoolProfile::current()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nisn' => ['nullable', 'string', 'max:30'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'asal_sekolah' => ['required', 'string', 'max:150'],
            'jurusan_pilihan' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telepon' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:500'],
            'nama_wali' => ['required', 'string', 'max:150'],
            'telepon_wali' => ['required', 'string', 'max:30'],
        ]);

        $registration = Registration::daftarkan($data);

        // Pengisi formulir jelas pemilik data ini, jadi berkasnya langsung terbuka
        // untuknya tanpa perlu mengetik ulang nomor + email.
        $registration->beriAksesSesi();

        return redirect()
            ->route('pendaftaran.dokumen', $registration->nomor_pendaftaran)
            ->with('success', 'Pendaftaran berhasil dikirim. Simpan nomor pendaftaran Anda: '.$registration->nomor_pendaftaran);
    }

    /** Sub fitur: Unggah Dokumen — seluruh rutenya dijaga middleware 'pendaftar'. */
    public function dokumen(string $nomor)
    {
        return view('public.pendaftaran.dokumen', [
            'profil' => SchoolProfile::current(),
            'registration' => $this->pendaftaran($nomor)->load('documents'),
        ]);
    }

    public function unggahDokumen(Request $request, string $nomor)
    {
        $registration = $this->pendaftaran($nomor);

        $validated = $request->validate([
            'jenis' => ['required', Rule::in(['Kartu Keluarga', 'Akta Kelahiran', 'Ijazah/SKL', 'Rapor', 'Pas Foto', 'Lainnya'])],
            'berkas' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        if ($registration->documents()->count() >= Registration::MAKS_DOKUMEN) {
            return back()->withErrors([
                'berkas' => 'Batas '.Registration::MAKS_DOKUMEN.' dokumen per pendaftar sudah tercapai. '
                    .'Hapus dokumen yang tidak terpakai sebelum mengunggah lagi.',
            ]);
        }

        $file = $validated['berkas'];

        // Disk 'local' berada di luar document root: kartu keluarga, akta, dan rapor
        // hanya boleh keluar lewat rute unduh yang memeriksa hak akses.
        $path = $file->store("dokumen/{$registration->id}", 'local');

        $registration->documents()->create([
            'jenis' => $validated['jenis'],
            'nama_file' => $this->namaFileAman($file->getClientOriginalName()),
            'path' => $path,
            'ukuran' => $file->getSize(),
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function unduhDokumen(string $nomor, int $dokumen): StreamedResponse
    {
        $doc = $this->pendaftaran($nomor)->documents()->findOrFail($dokumen);

        return Storage::disk('local')->download($doc->path, $doc->nama_file);
    }

    public function hapusDokumen(string $nomor, int $dokumen)
    {
        $doc = $this->pendaftaran($nomor)->documents()->findOrFail($dokumen);

        Storage::disk('local')->delete($doc->path);
        $doc->delete();

        return back()->with('success', 'Dokumen dihapus.');
    }

    /** Sub fitur: Cek Status Pendaftaran */
    public function cekStatus(Request $request)
    {
        $registration = null;

        if ($request->filled('nomor')) {
            $request->validate([
                'nomor' => ['required', 'string', 'max:30'],
                'email' => ['required', 'email'],
            ]);

            $registration = Registration::with('documents')
                ->where('nomor_pendaftaran', $request->input('nomor'))
                ->where('email', $request->input('email'))
                ->first();

            if (! $registration) {
                return back()->withInput()->withErrors([
                    'nomor' => 'Data pendaftaran tidak ditemukan. Periksa kembali nomor pendaftaran dan email.',
                ]);
            }

            // Nomor + email yang cocok adalah bukti kepemilikan: sejak sekarang
            // pemiliknya boleh membuka dan mengurus berkasnya kembali.
            $registration->beriAksesSesi();
        }

        return view('public.pendaftaran.status', [
            'profil' => SchoolProfile::current(),
            'registration' => $registration,
        ]);
    }

    protected function pendaftaran(string $nomor): Registration
    {
        return Registration::where('nomor_pendaftaran', $nomor)->firstOrFail();
    }

    /**
     * Nama asli berkas hanya dipakai sebagai label dan nama saat diunduh, jadi
     * komponen direktori serta karakter yang bisa menyisipkan baris baru pada
     * header Content-Disposition dibuang lebih dulu.
     */
    protected function namaFileAman(string $nama): string
    {
        $bersih = preg_replace('/[\x00-\x1F\x7F"\\\\]/', '', basename($nama)) ?? '';

        return Str::limit(trim($bersih) ?: 'dokumen', 120, '');
    }
}
