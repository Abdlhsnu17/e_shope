<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.registrations.index', [
            'items' => Registration::search($request->input('q'))
                ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
                ->withCount('documents')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'filterStatus' => $request->input('status'),
        ]);
    }

    public function show(Registration $registration)
    {
        return view('admin.registrations.show', [
            'item' => $registration->load('documents'),
        ]);
    }

    /** Berkas pendaftar tersimpan di disk privat, jadi hanya bisa diambil lewat sini. */
    public function unduhDokumen(Registration $registration, int $dokumen): StreamedResponse
    {
        $doc = $registration->documents()->findOrFail($dokumen);

        return Storage::disk('local')->download($doc->path, $doc->nama_file);
    }

    public function update(Request $request, Registration $registration)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Registration::STATUSES)],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $registration->update($data);

        return back()->with('success', 'Status pendaftaran diperbarui.');
    }

    /** Promosikan pendaftar yang diterima menjadi siswa aktif. */
    public function terima(Registration $registration)
    {
        if ($registration->status !== 'diterima') {
            return back()->withErrors(['status' => 'Hanya pendaftar berstatus "diterima" yang bisa dijadikan siswa.']);
        }

        if ($registration->nisn && Student::where('nisn', $registration->nisn)->exists()) {
            return back()->withErrors(['status' => 'Siswa dengan NISN tersebut sudah terdaftar.']);
        }

        // Penomoran NIS dan penyimpanan siswa dalam satu transaksi — dua panitia
        // yang mempromosikan pendaftar pada saat yang sama tidak boleh memperoleh
        // NIS yang sama.
        DB::transaction(fn () => Student::create([
            'nis' => Student::generateNis(),
            'nisn' => $registration->nisn,
            'nama' => $registration->nama_lengkap,
            'email' => $registration->email,
            'jenis_kelamin' => $registration->jenis_kelamin,
            'jurusan' => $registration->jurusan_pilihan,
            'tanggal_lahir' => $registration->tanggal_lahir,
            'alamat' => $registration->alamat,
            'nama_wali' => $registration->nama_wali,
            'telepon_wali' => $registration->telepon_wali,
            'status' => 'aktif',
        ]));

        return redirect()->route('admin.students.index')
            ->with('success', $registration->nama_lengkap.' berhasil ditambahkan sebagai siswa.');
    }

    public function destroy(Registration $registration)
    {
        foreach ($registration->documents as $doc) {
            Storage::disk('local')->delete($doc->path);
        }

        $registration->delete();

        return redirect()->route('admin.registrations.index')->with('success', 'Data pendaftaran dihapus.');
    }
}
