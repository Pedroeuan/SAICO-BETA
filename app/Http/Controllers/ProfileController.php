<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** Guarda la fotografía procesada en el almacenamiento privado del usuario. */
    public function updatePhoto(\App\Http\Requests\ProfilePhotoUpdateRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $stored = $request->file('photo')->storeAs(
            'profile-photos/'.$request->user()->getKey(), 'photo', 'local'
        );
        if (!$stored) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'photo' => 'No se pudo guardar la fotografía. Intenta de nuevo.',
            ]);
        }
        // El editor usa JSON; el retorno HTML permite reutilizar la ruta sin JavaScript.
        if ($request->expectsJson()) {
            $request->session()->flash('status', 'profile-updated');
            return response()->json(['saved' => true]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /** Elimina únicamente la fotografía del usuario que inició sesión. */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        $path = 'profile-photos/'.$request->user()->getKey().'/photo';
        if ($disk->exists($path) && !$disk->delete($path)) {
            return back()->withErrors(['photo' => 'No se pudo quitar la foto. Intenta de nuevo.']);
        }
        return Redirect::route('profile.edit')->with('status', 'profile-photo-removed');
    }

    /** Entrega la foto privada sin permitir caché compartida entre usuarios. */
    public function photo(Request $request)
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        $path = 'profile-photos/'.$request->user()->getKey().'/photo';
        abort_unless($disk->exists($path), 404);

        return response()->file($disk->path($path), [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
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
}
