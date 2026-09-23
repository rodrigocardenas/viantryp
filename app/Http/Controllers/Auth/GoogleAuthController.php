<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // Sincronizar el avatar de Google cada vez que el usuario inicia sesión.
                if ($googleUser->getAvatar()) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
            } else {
                // Check if user exists with same email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Update existing user with Google ID
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                } else {
                    $fullName = $googleUser->getName();
                    $parts = explode(' ', trim($fullName));
                    $name = $parts[0] ?? '';
                    $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

                    // Create new user
                    $user = User::create([
                        'name' => $name,
                        'last_name' => $lastName,
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => bcrypt(uniqid()), // Random password for OAuth users
                        'plan' => User::PLAN_BASICO,
                        'theme_color' => 'gold',
                        'country' => request()->header('cf-ipcountry') ?? null,
                    ]);

                    // Enviar notificación de bienvenida
                    $user->notify(new \App\Notifications\WelcomeNotification($user));
                }
            }

            // Log the user in
            Auth::login($user);

            if ($user->wasRecentlyCreated) {
                return redirect()->intended(route('trips.index'))->with('success', '¡Bienvenido! Tu cuenta ha sido creada con Google.');
            }

            return redirect()->intended(route('trips.index'))->with('success', '¡Bienvenido! Has iniciado sesión con Google.');

        } catch (\Exception $e) {
            return redirect()->route('trips.index')->with('error', 'Error al iniciar sesión con Google. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Handle Native Google Auth from Capacitor Native App
     */
    public function handleNativeGoogleAuth(Request $request)
    {
        try {
            $email = $request->input('email');
            $idToken = $request->input('idToken') ?? $request->input('id_token');
            $googleId = $request->input('google_id') ?? $request->input('userId');
            $name = $request->input('name') ?? $request->input('givenName') ?? '';
            $lastName = $request->input('last_name') ?? $request->input('familyName') ?? '';
            $avatar = $request->input('avatar') ?? $request->input('imageUrl');

            if (!$email) {
                return response()->json(['success' => false, 'message' => 'Email no proporcionado'], 400);
            }

            // Find or create user
            $user = User::where('email', $email)->orWhere(function($query) use ($googleId) {
                if ($googleId) $query->where('google_id', $googleId);
            })->first();

            if ($user) {
                $updateData = [];
                if ($googleId && !$user->google_id) {
                    $updateData['google_id'] = $googleId;
                }
                if ($avatar) {
                    $updateData['avatar'] = $avatar;
                }
                if (!empty($updateData)) {
                    $user->update($updateData);
                }
            } else {
                if (!$name) {
                    $parts = explode('@', $email);
                    $name = $parts[0];
                }

                $user = User::create([
                    'name' => $name,
                    'last_name' => $lastName,
                    'email' => $email,
                    'google_id' => $googleId,
                    'avatar' => $avatar,
                    'password' => bcrypt(uniqid()),
                    'plan' => User::PLAN_BASICO,
                    'country' => $request->header('cf-ipcountry') ?? null,
                ]);

                $user->notify(new \App\Notifications\WelcomeNotification($user));
            }

            Auth::login($user, true);

            // Generate one-time login token for GET navigation
            $oneTimeToken = \Illuminate\Support\Str::random(40);
            \Illuminate\Support\Facades\Cache::put('native_login_' . $oneTimeToken, $user->id, 120);

            $redirectUrl = route('auth.native.token', [
                'token' => $oneTimeToken,
                'app' => '1',
                'is_new' => $user->wasRecentlyCreated ? '1' : '0'
            ]);

            $cookie = cookie('viantryp_app_mode', '1', 525600);

            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl,
                'message' => '¡Bienvenido! Has iniciado sesión con Google.'
            ])->withCookie($cookie);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al autenticar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Consume one-time token and perform full GET login navigation
     */
    public function handleNativeTokenLogin(Request $request)
    {
        $token = $request->input('token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'Token de acceso no válido.');
        }

        $userId = \Illuminate\Support\Facades\Cache::pull('native_login_' . $token);
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Token expirado o no válido.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado.');
        }

        Auth::login($user, true);
        $request->session()->put('viantryp_app_mode', '1');
        $request->session()->regenerate();

        $cookie = cookie('viantryp_app_mode', '1', 525600);
        $targetRoute = $request->input('is_new') === '1' ? route('profile.index', ['app' => '1']) : route('trips.index', ['app' => '1']);

        return redirect($targetRoute)->with('success', '¡Bienvenido! Has iniciado sesión con Google.')->withCookie($cookie);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('trips.index')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
