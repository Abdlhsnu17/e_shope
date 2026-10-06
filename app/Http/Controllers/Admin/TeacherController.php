<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.teachers.index', [
            'items' => Teacher::search($request->input('q'))
                ->orderBy('nama')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.teachers.form', ['item' => new Teacher(['status' => 'aktif', 'jenis_kelamin' => 'L'])]);
    }

    public function store(Request $request)
    {
        Teacher::create($this->validated($request));

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.form', ['item' => $teacher]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $teacher->update($this->validated($request, $teacher));

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return back()->with('success', 'Data guru dihapus.');
    }

    protected function validated(Request $request, ?Teacher $teacher = null): array
    {
        return $request->validate([
            'nip' => ['required', 'string', 'max:30', Rule::unique('teachers', 'nip')->ignore($teacher)],
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'mata_pelajaran' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'tanggal_bergabung' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['aktif', 'cuti', 'nonaktif'])],
        ]);
    }
}
