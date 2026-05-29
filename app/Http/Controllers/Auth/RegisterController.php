<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Rate limiting for registration attempts
        $throttleKey = 'register|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'general' => "Demasiados intentos de registro. Inténtalo de nuevo en {$seconds} segundos."
            ])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255|min:2|regex:/^[a-zA-ZÀ-ÿ\s\-\.\']+$/',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[^A-Za-z0-9])/'
            ],
            'terms' => 'required|accepted'
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'name.min' => 'El nombre completo debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre completo no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, espacios, guiones, puntos y apóstrofes.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'Esta dirección de correo electrónico ya está registrada.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula y un símbolo.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'terms.required' => 'Debes aceptar los términos y condiciones.',
            'terms.accepted' => 'Debes aceptar los términos y condiciones.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plan' => User::PLAN_BASICO,
            'country' => $request->header('cf-ipcountry') ?? null,
        ]);

        // Envío de correo de bienvenida
        try {
            $user->notify(new \App\Notifications\WelcomeNotification($user));
        } catch (\Exception $e) {
            \Log::error('Error enviando correo de bienvenida: ' . $e->getMessage());
        }

        Auth::login($user);
        RateLimiter::clear($throttleKey);

        return redirect()->intended(route('profile.index'))
                        ->with('success', '¡Cuenta creada exitosamente! Bienvenido a Viantryp. Por favor, completa tu información de perfil.');
    }
}
