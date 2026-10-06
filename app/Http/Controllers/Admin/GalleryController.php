<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.galleries.index', [
            'items' => Gallery::when($request->input('kategori'), fn ($query, $kategori) => $query->where('kategori', $kategori))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'filterKategori' => $request->input('kategori'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(Gallery::KATEGORI)],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'gambar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['path'] = $request->file('gambar')->store('galeri', 'public');
        unset($data['gambar']);

        Gallery::create($data);

        return back()->with('success', 'Foto ditambahkan ke galeri.');
    }

    public function destroy(Gallery $gallery)
    {
        Storage::disk('public')->delete($gallery->path);
        $gallery->delete();

        return back()->with('success', 'Foto dihapus.');
    }
}
