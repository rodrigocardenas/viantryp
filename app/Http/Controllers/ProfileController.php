<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $tripCount = $user->trips()->count();
        $editorCount = \DB::table('trip_collaborators')
            ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
            ->where('trips.user_id', $user->id)
            ->where('trip_collaborators.role', 'editor')
            ->distinct('trip_collaborators.email')
            ->count();
        


        $subscription = null;
        $transactions = collect([]);
        try {
            if (method_exists($user, 'subscriptions')) {
                $subscription = $user->subscriptions()->orderByDesc('created_at')->first();
            }
            if (method_exists($user, 'transactions')) {
                $transactions = $user->transactions()->orderByDesc('billed_at')->get();
            }
        } catch (\Throwable $e) {
            \Log::info('Paddle subscription check in profile: ' . $e->getMessage());
        }

        return view('profile.index', [
            'user' => $user,
            'tripCount' => $tripCount,
            'editorCount' => $editorCount,
            'subscription' => $subscription,
            'transactions' => $transactions,
        ]);
    }

    public function getUsage()
    {
        $user = auth()->user();
        if (!$user) return response()->json(['success' => false], 401);

        $tripCount = \App\Models\Trip::where('user_id', $user->id)->count();
        $editorCount = \DB::table('trip_collaborators')
            ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
            ->where('trips.user_id', $user->id)
            ->where('trip_collaborators.role', 'editor')
            ->distinct('trip_collaborators.email')
            ->count();


        $limits = $user->getPlanLimits();

        return response()->json([
            'success' => true,
            'usage' => [
                'trips' => $tripCount,
                'editors' => $editorCount,
            ],
            'limits' => [
                'max_trips' => $limits['max_trips'],
                'max_editors' => $limits['max_editors'] ?? 0,
            ]
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
            'display_name_type' => 'nullable|string|in:personal,agency',
        ]);

        $user->update([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'bio' => $validated['bio'],
            'display_name_type' => $validated['display_name_type'] ?? $user->display_name_type,
        ]);

        return response()->json(['success' => true, 'message' => 'Información personal actualizada']);
    }

    public function updateAgency(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'agency_name' => 'nullable|string|max:255',
            'agency_website' => 'nullable|string|max:255',
            'agency_whatsapp' => 'nullable|string|max:20',
            'agency_slogan' => 'nullable|string|max:255',
            'display_name_type' => 'nullable|string|in:personal,agency',
        ];

        if ($user->account_type === 'agency') {
            $rules['name'] = 'required|string|max:255';
            $rules['last_name'] = 'nullable|string|max:255';
            $rules['phone'] = 'nullable|string|max:20';
            $rules['country'] = 'nullable|string|max:100';
            $rules['bio'] = 'nullable|string|max:1000';
        }

        $validated = $request->validate($rules);
        $user->update($validated);

        return response()->json(['success' => true, 'message' => 'Información de la agencia y agente actualizada']);
    }

    public function updateTheme(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'theme_color' => 'required|string|in:default,ocean,gold,sunset,blush,silver,mint,lavender',
        ]);

        $user->update(['theme_color' => $validated['theme_color']]);

        return response()->json(['success' => true, 'message' => 'Tema actualizado']);
    }

    public function uploadAvatar(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'avatar' => 'required|image|max:10240'
        ]);

        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior del almacenamiento si era un archivo local
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
            return response()->json([
                'success' => true, 
                'url' => asset('storage/' . $path),
                'message' => 'Foto de perfil actualizada con éxito'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No se recibió ninguna imagen'], 400);
    }

    public function uploadLogo(Request $request)
    {
        $user = auth()->user();

        $effectivePlan = $user->effective_plan;

        if (!in_array($effectivePlan, [\App\Models\User::PLAN_COLABORATIVO, \App\Models\User::PLAN_CORPORATIVO])) {
            return response()->json([
                'success' => false,
                'error_code' => 'LIMIT_REACHED',
                'message' => 'La carga del Logo de Agencia y Marca Blanca es una función exclusiva del Plan Negocios.'
            ], 403);
        }

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
            if (!str_starts_with($user->avatar, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $user->update(['avatar' => null]);
            return response()->json(['success' => true, 'message' => 'Foto de perfil eliminada']);
        }
        return response()->json(['success' => false, 'message' => 'No hay avatar para eliminar'], 400);
    }
    public function completeTutorial(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'tutorial' => 'required|string|max:50',
        ]);

        $tutorials = $user->tutorials_seen ?? [];
        if (!in_array($validated['tutorial'], $tutorials)) {
            $tutorials[] = $validated['tutorial'];
            $user->update(['tutorials_seen' => $tutorials]);
        }

        return response()->json(['success' => true]);
    }

    public function updatePlan(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'plan' => 'required|string|in:básico,avanzado,colaborativo,corporativo',
        ]);

        $newPlan = strtolower($validated['plan']);
        if ($newPlan === 'basico') {
            $newPlan = 'básico';
        }

        // Security check: Only allow voluntary downgrade to 'básico' via direct endpoint.
        // Upgrades to paid plans must go through verified Paddle checkout or promotion codes.
        if ($newPlan !== User::PLAN_BASICO) {
            return response()->json([
                'success' => false,
                'message' => 'Para actualizar a un plan superior, debes suscribirte a través de la pasarela de pago o canjear un código válido.'
            ], 403);
        }
        
        // Define limits for validation
        $planLimits = [
            'básico'        => ['trips' => 1, 'editors' => 0],
            'avanzado'      => ['trips' => 20, 'editors' => 2],
            'colaborativo'  => ['trips' => 1000000, 'editors' => 1000000],
        ];

        $targetLimits = $planLimits[$newPlan] ?? null;

        if ($targetLimits) {
            // Check Current Trips
            $tripCount = \App\Models\Trip::where('user_id', $user->id)->count();
            if ($tripCount > $targetLimits['trips']) {
                return response()->json([
                    'success' => false, 
                    'message' => "No puedes bajar al Plan Básico porque tienes {$tripCount} itinerarios y el plan solo permite {$targetLimits['trips']}."
                ], 422);
            }

            // Check Current Editors
            $editorCount = \DB::table('trip_collaborators')
                ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
                ->where('trips.user_id', $user->id)
                ->where('trip_collaborators.role', 'editor')
                ->distinct('trip_collaborators.email')
                ->count();

            if ($editorCount > $targetLimits['editors']) {
                return response()->json([
                    'success' => false, 
                    'message' => "No puedes bajar al Plan Básico porque has invitado a {$editorCount} editores y el plan solo permite {$targetLimits['editors']}."
                ], 422);
            }
        }

        $user->update(['plan' => $newPlan]);
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Plan actualizado a Plan ' . $user->plan_display_name,
            'plan' => $newPlan
        ]);
    }

    public function syncPaddlePlan(Request $request)
    {
        $user = auth()->user();
        if (!$user) return response()->json(['success' => false], 401);

        $priceId = $request->input('price_id');
        $planKey = strtolower($request->input('plan', ''));
        $transactionId = $request->input('transaction_id');

        $paddlePrices = config('plans.paddle', []);
        $matchedPlan = null;

        if ($priceId) {
            foreach ($paddlePrices as $key => $prices) {
                if (($prices['monthly'] ?? '') === $priceId || ($prices['annual'] ?? '') === $priceId) {
                    $matchedPlan = $key;
                    break;
                }
            }
        }

        // Allow explicit downgrade to 'básico' without price_id
        if (!$matchedPlan && in_array($planKey, ['básico', 'basico'])) {
            $matchedPlan = 'básico';
        }

        if (!$matchedPlan) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo verificar el identificador de precio del plan seleccionado.'
            ], 422);
        }

        if ($matchedPlan !== 'básico') {
            $apiKey = config('cashier.api_key');
            $baseUrl = config('cashier.sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';

            if ($apiKey) {
                $verified = false;

                // 1. Verify via transaction_id from Paddle.Checkout callback
                if ($transactionId && is_string($transactionId) && str_starts_with($transactionId, 'txn_')) {
                    try {
                        $txResponse = \Illuminate\Support\Facades\Http::withToken($apiKey)
                            ->timeout(10)
                            ->get("{$baseUrl}/transactions/{$transactionId}");

                        if ($txResponse->successful()) {
                            $txData = $txResponse->json('data') ?? [];
                            $txStatus = $txData['status'] ?? '';
                            $txUserId = $txData['custom_data']['user_id'] ?? null;

                            if (in_array($txStatus, ['completed', 'paid', 'billed']) && (empty($txUserId) || (string) $txUserId === (string) $user->id)) {
                                $verified = true;

                                // Extract verified price_id from transaction items if present
                                $txItems = $txData['items'] ?? [];
                                foreach ($txItems as $item) {
                                    $itemPriceId = $item['price_id'] ?? ($item['price']['id'] ?? null);
                                    if ($itemPriceId) {
                                        foreach ($paddlePrices as $key => $prices) {
                                            if (($prices['monthly'] ?? '') === $itemPriceId || ($prices['annual'] ?? '') === $itemPriceId) {
                                                $matchedPlan = $key;
                                                break 2;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Paddle transaction verification warning: ' . $e->getMessage());
                    }
                }

                // 2. Fallback: verify via active subscriptions or local Cashier webhook state
                if (!$verified) {
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('subscriptions') && $user->subscriptions()->valid()->exists()) {
                            $verified = true;
                        } else {
                            $subResponse = \Illuminate\Support\Facades\Http::withToken($apiKey)
                                ->timeout(10)
                                ->get("{$baseUrl}/subscriptions", [
                                    'search' => $user->email,
                                    'status' => 'active,trialing'
                                ]);
                            if ($subResponse->successful()) {
                                foreach (($subResponse->json('data') ?? []) as $sub) {
                                    if ($this->subscriptionBelongsToUser($sub, $user)) {
                                        $verified = true;
                                        break;
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Paddle subscription fallback check warning: ' . $e->getMessage());
                    }
                }

                if (!$verified) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró una transacción o suscripción activa verificada en Paddle.'
                    ], 403);
                }
            }
        }

        if ($matchedPlan) {
            // Si baja a Básico, intentar cancelar suscripciones activas en Paddle
            if ($matchedPlan === 'básico') {
                try {
                    $apiKey = config('cashier.api_key');
                    $baseUrl = config('cashier.sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
                    if ($apiKey) {
                        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                            ->get("{$baseUrl}/subscriptions", [
                                'search' => $user->email,
                                'status' => 'active,trialing'
                            ]);

                        if ($response->successful()) {
                            $subs = $response->json('data') ?? [];
                            foreach ($subs as $sub) {
                                if (isset($sub['id']) && $this->subscriptionBelongsToUser($sub, $user)) {
                                    \Illuminate\Support\Facades\Http::withToken($apiKey)
                                        ->post("{$baseUrl}/subscriptions/{$sub['id']}/cancel", [
                                            'effective_from' => 'next_billing_period'
                                        ]);
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Error canceling Paddle subscription on downgrade: ' . $e->getMessage());
                }
            }

            $user->update([
                'plan' => $matchedPlan,
                'trial_ends_at' => null
            ]);
            $user->refresh();

            if ($matchedPlan !== 'básico') {
                try {
                    $alreadySent = $user->notifications()
                        ->where('type', \App\Notifications\SubscriptionConfirmedNotification::class)
                        ->where('created_at', '>=', now()->subMinutes(10))
                        ->exists();
                    if (!$alreadySent) {
                        $user->notify(new \App\Notifications\SubscriptionConfirmedNotification($user, $user->plan_display_name));
                    }
                } catch (\Throwable $e) {
                    \Log::warning('SubscriptionConfirmedNotification error: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => '¡Tu plan ha sido actualizado a Plan ' . $user->plan_display_name . ' exitosamente!',
                'plan' => $matchedPlan
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Plan no reconocido.'], 422);
    }

    public function verifyPlanCode(Request $request)
    {
        $request->validate([
            'plan' => 'required|string',
            'code' => 'required|string',
        ]);

        $submittedCode = strtoupper(trim($request->code));
        $submittedPlan = strtolower(trim($request->plan));

        // Normalización de nombres de plan (Explorador -> básico, Viajero Pro -> avanzado, Negocios -> colaborativo)
        $planMap = [
            'explorador'   => 'básico',
            'basico'       => 'básico',
            'básico'       => 'básico',
            'viajero pro'  => 'avanzado',
            'viajeropro'   => 'avanzado',
            'pro'          => 'avanzado',
            'avanzado'     => 'avanzado',
            'esencial'     => 'avanzado',
            'negocios'     => 'colaborativo',
            'colaborativo' => 'colaborativo',
            'corporativo'  => 'colaborativo',
        ];

        // Códigos válidos para cada nivel de plan
        $proCodes = array_filter(array_map('trim', explode(',',
            config('plans.codes.viajero_pro', '') . ',' .
            config('plans.codes.viajero pro', '') . ',' .
            config('plans.codes.avanzado', '')
        )));

        $negociosCodes = array_filter(array_map('trim', explode(',',
            config('plans.codes.negocios', '') . ',' .
            config('plans.codes.colaborativo', '') . ',' .
            config('plans.codes.corporativo', '')
        )));

        $targetPlan = null;
        if (in_array($submittedCode, array_map('strtoupper', $proCodes))) {
            $targetPlan = 'avanzado'; // Viajero Pro
        } elseif (in_array($submittedCode, array_map('strtoupper', $negociosCodes))) {
            $targetPlan = 'colaborativo'; // Negocios
        }

        if (!$targetPlan) {
            return response()->json([
                'success' => false,
                'message' => 'Código promocional inválido o no reconocido. Por favor verifica el código.'
            ], 422);
        }

        // Actualizar el plan del usuario en la base de datos
        $user = auth()->user();
        $user->update([
            'plan' => $targetPlan,
            'trial_ends_at' => null
        ]);

        $displayNames = [
            'básico' => 'Básico',
            'avanzado' => 'Viajero Pro',
            'colaborativo' => 'Negocios'
        ];

        $planName = $displayNames[$targetPlan] ?? ucfirst($targetPlan);

        try {
            $alreadySent = $user->notifications()
                ->where('type', \App\Notifications\SubscriptionConfirmedNotification::class)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->exists();
            if (!$alreadySent) {
                $user->notify(new \App\Notifications\SubscriptionConfirmedNotification($user, $planName));
            }
        } catch (\Throwable $e) {
            \Log::warning('SubscriptionConfirmedNotification error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => '¡Felicidades! Has desbloqueado el Plan ' . $planName . ' exitosamente.',
            'plan' => $targetPlan
        ]);
    }

    public function requestPlanUpgrade(Request $request)
    {
        $request->validate([
            'plan'          => 'required|string',
            'contact_name'  => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
        ]);

        $user = auth()->user();
        $planNames = [
            'explorador'   => 'Básico',
            'básico'       => 'Básico',
            'basico'       => 'Básico',
            'viajero pro'  => 'Viajero Pro',
            'viajeropro'   => 'Viajero Pro',
            'avanzado'     => 'Viajero Pro',
            'esencial'     => 'Viajero Pro',
            'negocios'     => 'Negocios',
            'colaborativo' => 'Negocios',
            'corporativo'  => 'Negocios',
        ];

        $to = env('PLAN_REQUEST_EMAIL', env('MAIL_FROM_ADDRESS', 'hola@viantryp.com'));

        \Illuminate\Support\Facades\Mail::send(
            'emails.plan-request',
            [
                'requestedPlan' => $planNames[$request->plan] ?? $request->plan,
                'contactName'   => $request->contact_name,
                'contactEmail'  => $request->contact_email,
                'contactPhone'  => $request->contact_phone,
                'userEmail'     => $user->email,
            ],
            function ($message) use ($to, $request, $planNames) {
                $message->to($to)
                        ->subject('🚀 Solicitud de Plan ' . ($planNames[$request->plan] ?? $request->plan) . ' — Viantryp');
            }
        );

        return response()->json(['success' => true, 'message' => '¡Solicitud enviada! Nuestro equipo se comunicará contigo pronto.']);
    }

    public function chooseInitialPlan(Request $request)
    {
        $user = auth()->user();
        if ($user->initial_plan_chosen_at) {
            return response()->json(['success' => false, 'message' => 'Plan inicial ya seleccionado.'], 403);
        }

        $validated = $request->validate([
            'plan' => 'nullable|string|in:básico,avanzado,colaborativo,corporativo',
            'account_type' => 'required|string|in:personal,agency',
        ]);

        $accountType = $validated['account_type'];
        // Asignación automática del Plan Viajero Pro con 1 mes gratis por lanzamiento oficial
        $plan = User::PLAN_AVANZADO;

        $updateData = [
            'plan' => $plan,
            'account_type' => $accountType,
            'display_name_type' => $accountType,
            'initial_plan_chosen_at' => now(),
            'trial_ends_at' => now()->addDays(30), // 1 mes gratis de prueba
        ];

        $user->update($updateData);

        return response()->json([
            'success' => true, 
            'message' => '¡Bienvenido a la comunidad Viantryp! Tu mes gratis de Viajero Pro ha sido activado.',
            'plan' => $plan
        ]);
    }

    public function changeAccountType(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'account_type' => 'required|string|in:personal,agency',
        ]);

        $type = $validated['account_type'];
        $user->update([
            'account_type' => $type,
            'display_name_type' => $type,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de cuenta cambiado a ' . ($type === 'personal' ? 'Personal' : 'Agencia')
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual no es correcta.'
            ], 422);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada exitosamente.'
        ]);
    }

    public function destroyAccount(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        // 1. Cancel active Paddle subscriptions if any
        try {
            $apiKey = config('cashier.api_key');
            $baseUrl = config('cashier.sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
            if ($apiKey) {
                $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                    ->timeout(8)
                    ->get("{$baseUrl}/subscriptions", [
                        'search' => $user->email,
                        'status' => 'active,trialing'
                    ]);

                if ($response->successful()) {
                    $subs = $response->json('data') ?? [];
                    foreach ($subs as $sub) {
                        if (isset($sub['id']) && $this->subscriptionBelongsToUser($sub, $user)) {
                            \Illuminate\Support\Facades\Http::withToken($apiKey)
                                ->timeout(8)
                                ->post("{$baseUrl}/subscriptions/{$sub['id']}/cancel", [
                                    'effective_from' => 'immediately'
                                ]);
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Error canceling Paddle subscription during account deletion: ' . $e->getMessage());
        }

        // 2. Delete uploaded files (avatar, agency logo, and trip documents)
        try {
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            if ($user->agency_logo && !str_starts_with($user->agency_logo, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->agency_logo);
            }

            $documents = \App\Models\TripDocument::where('user_id', $user->id)
                ->orWhereIn('trip_id', $user->trips()->pluck('id'))
                ->get();

            foreach ($documents as $doc) {
                if (\Illuminate\Support\Facades\Storage::disk('local')->exists($doc->path)) {
                    \Illuminate\Support\Facades\Storage::disk('local')->delete($doc->path);
                }
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->path);
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Error deleting user storage files during account deletion: ' . $e->getMessage());
        }

        // 3. Delete user record (foreign keys cascade trips, documents, persons, and collaborator rows)
        \Illuminate\Support\Facades\Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'redirect' => route('home'),
            'message' => 'Tu cuenta y todos tus datos han sido eliminados permanentemente.'
        ]);
    }

    /**
     * Verify that a Paddle subscription object returned by search belongs to the target user.
     */
    protected function subscriptionBelongsToUser(array $sub, $user): bool
    {
        $customUserId = $sub['custom_data']['user_id'] ?? null;
        if ($customUserId !== null) {
            return (string) $customUserId === (string) $user->id;
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('customers') && $user->customer && !empty($sub['customer_id'])) {
                return $user->customer->paddle_id === $sub['customer_id'];
            }
        } catch (\Throwable $e) {
            // Ignore schema lookup errors
        }

        return true;
    }
}
