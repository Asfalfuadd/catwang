<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user profile settings page.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Process avatar upload from either base64 (crop) or file upload.
     */
    private function processAvatarUpload(Request $request, User $user): ?string
    {
        if ($request->filled('avatar_base64')) {
            $data = (string) $request->input('avatar_base64');
            if (preg_match('/^data:image\/([a-zA-Z0-9\+\-]+).*?;base64,(.*)$/s', $data, $matches)) {
                $type = strtolower($matches[1]);
                $base64Data = $matches[2];
                if (! in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    throw ValidationException::withMessages([
                        'avatar' => 'Format gambar hasil crop tidak didukung. Gunakan JPG, PNG, atau WebP.',
                    ]);
                }
                $decoded = base64_decode(str_replace(' ', '+', $base64Data), true);
                if ($decoded === false) {
                    throw ValidationException::withMessages([
                        'avatar' => 'Gagal memproses data gambar.',
                    ]);
                }

                $filename = 'avatars/'.Str::random(40).'.'.($type === 'jpeg' ? 'jpg' : $type);

                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                Storage::disk('public')->put($filename, $decoded);

                return $filename;
            }
        }

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            return $request->file('avatar')->store('avatars', 'public');
        }

        return null;
    }

    /**
     * Update the user profile information, theme preference, and optional avatar.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'theme' => ['nullable', 'string', Rule::in(['light', 'dark'])],
            'avatar_base64' => ['nullable', 'string'],
        ];

        if (! $request->filled('avatar_base64') && $request->hasFile('avatar')) {
            $rules['avatar'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'];
        }

        $validated = $request->validate($rules, [
            'avatar.uploaded' => 'Ukuran file foto melebihi batas upload server. Silakan gunakan fitur crop agar foto dikompresi otomatis.',
        ]);

        $newAvatar = $this->processAvatarUpload($request, $user);
        if ($newAvatar) {
            $validated['avatar'] = $newAvatar;
        } else {
            unset($validated['avatar']);
        }
        unset($validated['avatar_base64']);

        $user->fill($validated);
        $user->save();

        return back()->with('success', 'Profil dan pengaturan berhasil diperbarui!');
    }

    /**
     * Update the user avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($request->filled('avatar_base64')) {
            $request->validate([
                'avatar_base64' => ['required', 'string'],
            ]);
        } else {
            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            ], [
                'avatar.uploaded' => 'Ukuran file foto melebihi batas upload server. Silakan gunakan fitur crop agar foto dikompresi otomatis.',
                'avatar.max' => 'Ukuran file foto maksimal 10MB.',
            ]);
        }

        if (! $request->hasFile('avatar') && ! $request->filled('avatar_base64')) {
            return back()->withErrors(['avatar' => 'Silakan pilih gambar terlebih dahulu.']);
        }

        $path = $this->processAvatarUpload($request, $user);
        if ($path) {
            $user->update(['avatar' => $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Remove the user avatar.
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus dan dikembalikan ke inisial!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui!');
    }

    /**
     * Update the user's theme preference.
     */
    public function updateTheme(Request $request): JsonResponse|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'theme' => ['required', 'string', Rule::in(['light', 'dark'])],
        ]);

        $user->update([
            'theme' => $validated['theme'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'theme' => $validated['theme'],
                'message' => 'Tema berhasil disimpan!',
            ]);
        }

        return back()->with('success', 'Tema berhasil diubah!');
    }
}
