<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.announcements.index', [
            'items' => Announcement::when($request->input('q'), fn ($query, $term) => $query->where('judul', 'like', "%{$term}%"))
                ->when($request->input('kategori'), fn ($query, $kategori) => $query->where('kategori', $kategori))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'filterKategori' => $request->input('kategori'),
        ]);
    }

    public function create()
    {
        return view('admin.announcements.form', ['item' => new Announcement(['is_published' => true, 'kategori' => 'pengumuman'])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['gambar'] = $this->simpanGambar($request);

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Konten berhasil dipublikasikan.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.form', ['item' => $announcement]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validated($request);

        if ($gambar = $this->simpanGambar($request)) {
            if ($announcement->gambar) {
                Storage::disk('public')->delete($announcement->gambar);
            }
            $data['gambar'] = $gambar;
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Konten diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->gambar) {
            Storage::disk('public')->delete($announcement->gambar);
        }

        $announcement->delete();

        return back()->with('success', 'Konten dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['required', Rule::in(Announcement::KATEGORI)],
            'ringkasan' => ['nullable', 'string', 'max:300'],
            'konten' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        unset($data['gambar']);

        return $data;
    }

    protected function simpanGambar(Request $request): ?string
    {
        return $request->hasFile('gambar')
            ? $request->file('gambar')->store('konten', 'public')
            : null;
    }
}
