<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => auth()->user()
        ]);
    }

    public function updatePersonal(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'bio' => $validated['bio'],
        ]);

        return response()->json(['success' => true, 'message' => 'Información personal actualizada']);
    }

    public function updateAgency(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'agency_name' => 'nullable|string|max:255',
            'agency_website' => 'nullable|string|max:255',
            'agency_whatsapp' => 'nullable|string|max:20',
            'agency_slogan' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return response()->json(['success' => true, 'message' => 'Información de la agencia actualizada']);
    }

    public function updateTheme(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'theme_color' => 'nullable|string|max:50',
        ]);

        $user->update(['theme_color' => $validated['theme_color']]);

        return response()->json(['success' => true, 'message' => 'Tema actualizado']);
    }

    public function uploadAvatar(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
            return response()->json(['success' => true, 'url' => asset('storage/' . $path)]);
        }

        return response()->json(['success' => false], 400);
    }

    public function uploadLogo(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'logo' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $user->update(['agency_logo' => $path]);
            return response()->json(['success' => true, 'url' => asset('storage/' . $path)]);
        }

        return response()->json(['success' => false], 400);
    }

    public function deleteAvatar()
    {
        $user = auth()->user();
        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            return response()->json(['success' => true, 'message' => 'Avatar eliminado']);
        }
        return response()->json(['success' => false, 'message' => 'No hay avatar para eliminar'], 400);
    }
}
