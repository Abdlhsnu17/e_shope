<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;

class SchoolProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['item' => SchoolProfile::current()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'npsn' => ['nullable', 'string', 'max:30'],
            'jenjang' => ['nullable', 'string', 'max:50'],
            'akreditasi' => ['nullable', 'string', 'max:10'],
            'kepala_sekolah' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'visi' => ['nullable', 'string', 'max:1000'],
            'misi' => ['nullable', 'string', 'max:2000'],
            'sejarah' => ['nullable', 'string', 'max:5000'],
        ]);

        SchoolProfile::current()->update($data);

        return back()->with('success', 'Profil sekolah diperbarui.');
    }
}
