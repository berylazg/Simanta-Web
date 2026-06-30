<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->validate([
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('foto')) {
            $user = $request->user();

            if ($user->foto && Storage::disk('public')->exists('foto-profil/' . $user->foto)) {
                Storage::disk('public')->delete('foto-profil/' . $user->foto);
            }

            $namaFile = 'foto_' . $user->id . '_' . time() . '.' . $request->file('foto')->extension();
            $request->file('foto')->storeAs('foto-profil', $namaFile, 'public');
            $request->user()->foto = $namaFile;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update cepat dari popup di topbar (nama, foto, password).
     */
    public function perbaruiCepat(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'foto'     => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_lama' => ['required_with:password', 'string'],
        ]);

        // Kalau mau ganti password, validasi password lama
        if (!empty($data['password'])) {
            if (!Hash::check($data['password_lama'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah.',
                ], 422);
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name = $data['name'];

        // Upload foto kalau ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama kalau ada
            if ($user->foto && Storage::disk('public')->exists('foto-profil/' . $user->foto)) {
                Storage::disk('public')->delete('foto-profil/' . $user->foto);
            }

            $namaFile = 'foto_' . $user->id . '_' . time() . '.' . $request->file('foto')->extension();
            $request->file('foto')->storeAs('foto-profil', $namaFile, 'public');
            $user->foto = $namaFile;
        }

        $user->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Profil berhasil diperbarui.',
            'name'     => $user->name,
            'foto_url' => $user->foto ? asset('storage/foto-profil/' . $user->foto) : null,
        ]);
    }
}
