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
    public function handleGoogleCallback(Request $request)
    {
        try {
            // If request has credential (GSI redirect POST), process with handleGsiCallback
            if ($request->filled('credential') || $request->filled('id_token') || $request->filled('idToken')) {
                return $this->handleGsiCallback($request);
            }

            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // Sincronizar el avatar de Google solo si el usuario aún no tiene avatar o si aún usa el avatar de Google (no uno personalizado)
                if ($googleUser->getAvatar() && (empty($user->avatar) || str_starts_with($user->avatar, 'http'))) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
            } else {
                // Check if user exists with same email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    $updateFields = [
                        'google_id' => $googleUser->getId(),
                    ];
                    if ($googleUser->getAvatar() && (empty($user->avatar) || str_starts_with($user->avatar, 'http'))) {
                        $updateFields['avatar'] = $googleUser->getAvatar();
                    }
                    $user->update($updateFields);
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

            // Log the user in with remember token
            Auth::login($user, true);

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
            $idToken = $request->input('credential') ?? $request->input('idToken') ?? $request->input('id_token');
            $googleId = $request->input('google_id') ?? $request->input('userId');
            $name = $request->input('name') ?? $request->input('givenName') ?? '';
            $lastName = $request->input('last_name') ?? $request->input('familyName') ?? '';
            $avatar = $request->input('avatar') ?? $request->input('imageUrl');

            // Verificar criptográficamente el token de Google
            if ($idToken) {
                try {
                    $googleClient = new \Google\Client(['client_id' => config('services.google.client_id')]);
                    $verifiedPayload = $googleClient->verifyIdToken($idToken);
                    if ($verifiedPayload) {
                        $email = $verifiedPayload['email'] ?? $email;
                        $googleId = $verifiedPayload['sub'] ?? $googleId;
                        $name = $name ?: ($verifiedPayload['given_name'] ?? $verifiedPayload['name'] ?? '');
                        $lastName = $lastName ?: ($verifiedPayload['family_name'] ?? '');
                        $avatar = $avatar ?: ($verifiedPayload['picture'] ?? null);
                    } else {
                        \Illuminate\Support\Facades\Log::warning('Token de Google inválido recibido en handleNativeGoogleAuth.');
                        return response()->json(['success' => false, 'message' => 'Token de autenticación de Google inválido.'], 401);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Excepción al verificar token de Google: ' . $e->getMessage());
                    return response()->json(['success' => false, 'message' => 'Error al validar credenciales con Google.'], 401);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Token de Google no proporcionado.'], 400);
            }

            if (!$email) {
                return response()->json(['success' => false, 'message' => 'Email no proporcionado por Google.'], 400);
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
                if ($avatar && (empty($user->avatar) || str_starts_with($user->avatar, 'http'))) {
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
            $request->session()->put('viantryp_app_mode', '1');
            $request->session()->regenerate();

            $cookie = cookie('viantryp_app_mode', '1', 525600);
            $targetRoute = $user->wasRecentlyCreated ? route('profile.index', ['app' => '1']) : route('trips.index', ['app' => '1']);

            return response()->json([
                'success' => true,
                'redirect' => $targetRoute,
                'message' => '¡Bienvenido! Has iniciado sesión con Google.'
            ])->withCookie($cookie);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Native auth error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al autenticar con Google. Por favor, inténtalo de nuevo.'], 500);
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
     * Handle Google Identity Services (GSI) redirect POST callback
     */
    public function handleGsiCallback(Request $request)
    {
        try {
            $credential = $request->input('credential') ?? $request->input('id_token') ?? $request->input('idToken');

            if (!$credential) {
                return redirect()->route('login')->with('error', 'No se recibió la credencial de Google.');
            }

            // Verificar criptográficamente el token JWT con Google
            try {
                $googleClient = new \Google\Client(['client_id' => config('services.google.client_id')]);
                $verifiedPayload = $googleClient->verifyIdToken($credential);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('GSI verification error: ' . $e->getMessage());
                $verifiedPayload = false;
            }

            if (!$verifiedPayload) {
                return redirect()->route('login')->with('error', 'Credencial de Google inválida o expirada.');
            }

            $email = $verifiedPayload['email'] ?? null;
            $googleId = $verifiedPayload['sub'] ?? null;
            $name = $verifiedPayload['given_name'] ?? $verifiedPayload['name'] ?? '';
            $lastName = $verifiedPayload['family_name'] ?? '';
            $avatar = $verifiedPayload['picture'] ?? null;

            if (!$email) {
                return redirect()->route('login')->with('error', 'No se pudo obtener el correo de Google.');
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
            $request->session()->regenerate();

            $cookie = cookie()->forget('viantryp_app_mode');
            $targetRoute = $user->wasRecentlyCreated ? route('profile.index') : route('trips.index');

            return redirect($targetRoute)->with('success', '¡Bienvenido! Has iniciado sesión con Google.')->withCookie($cookie);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GSI auth error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error al procesar el inicio de sesión con Google.');
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->has('app') || $request->cookie('viantryp_app_mode') === '1') {
            return redirect()->route('login', ['app' => '1'])->with('success', 'Has cerrado sesión exitosamente.');
        }

        return redirect()->route('login')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
