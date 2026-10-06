<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.users.index', [
            'items' => User::search($request->input('q'))->orderBy('name')->paginate(10)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.users.form', ['item' => new User(['role' => 'staf', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')],
            'role' => ['required', Rule::in(User::ROLES)],
            'telepon' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', ['item' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in(User::ROLES)],
            'telepon' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if (blank($data['password'])) {
            unset($data['password']);
        }

        // Jangan sampai admin terakhir mengunci dirinya sendiri.
        if ($user->is($request->user()) && (! $data['is_active'] || $data['role'] !== 'admin')) {
            return back()->withInput()->withErrors([
                'role' => 'Anda tidak dapat mencabut akses admin milik akun Anda sendiri.',
            ]);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return back()->with('success', 'Pengguna dihapus.');
    }
}
