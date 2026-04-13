<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        
        $maxAttachments = \DB::table('trip_documents')
            ->select('trip_id', \DB::raw('count(*) as count'))
            ->where('type', 'pro_attachment')
            ->whereIn('trip_id', $user->trips()->pluck('id'))
            ->groupBy('trip_id')
            ->orderByDesc('count')
            ->first()
            ->count ?? 0;

        return view('profile.index', [
            'user' => $user,
            'tripCount' => $tripCount,
            'editorCount' => $editorCount,
            'maxAttachments' => $maxAttachments
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
        $validated = $request->validate([
            'agency_name' => 'nullable|string|max:255',
            'agency_website' => 'nullable|string|max:255',
            'agency_whatsapp' => 'nullable|string|max:20',
            'agency_slogan' => 'nullable|string|max:255',
            'display_name_type' => 'nullable|string|in:personal,agency',
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
            'plan' => 'required|string|in:básico,esencial,avanzado,colaborativo,corporativo',
        ]);

        $newPlan = strtolower($validated['plan']);
        
        // Define limits for validation
        $planLimits = [
            'básico'       => ['trips' => 1, 'attachments' => 10, 'editors' => 0],
            'esencial'      => ['trips' => 3, 'attachments' => 50, 'editors' => 0],
            'avanzado'      => ['trips' => 10, 'attachments' => 1000000, 'editors' => 2],
            'colaborativo'  => ['trips' => 1000000, 'attachments' => 1000000, 'editors' => 1000000],
            'corporativo'   => ['trips' => 1000000, 'attachments' => 1000000, 'editors' => 1000000],
        ];

        $targetLimits = $planLimits[$newPlan] ?? null;

        if ($targetLimits) {
            // Check Current Trips
            $tripCount = \App\Models\Trip::where('user_id', $user->id)->count();
            if ($tripCount > $targetLimits['trips']) {
                return response()->json([
                    'success' => false, 
                    'message' => "No puedes bajar al plan ".ucfirst($newPlan)." porque tienes {$tripCount} itinerarios y el plan solo permite {$targetLimits['trips']}."
                ], 422);
            }

            // Check Max Attachments in any trip
            $maxAttachments = \DB::table('trip_documents')
                ->join('trips', 'trip_documents.trip_id', '=', 'trips.id')
                ->where('trips.user_id', $user->id)
                ->where('trip_documents.type', 'pro_attachment')
                ->groupBy('trip_documents.trip_id')
                ->selectRaw('count(*) as aggregate')
                ->get()
                ->max('aggregate') ?? 0;

            if ($maxAttachments > $targetLimits['attachments']) {
                return response()->json([
                    'success' => false, 
                    'message' => "No puedes bajar al plan ".ucfirst($newPlan)." porque tienes itinerarios con {$maxAttachments} archivos y el plan solo permite {$targetLimits['attachments']}."
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
                    'message' => "No puedes bajar al plan ".ucfirst($newPlan)." porque has invitado a {$editorCount} editores y el plan solo permite {$targetLimits['editors']}."
                ], 422);
            }
        }

        $user->update(['plan' => $newPlan]);

        return response()->json([
            'success' => true,
            'message' => 'Plan actualizado a ' . ucfirst($newPlan),
            'plan' => $newPlan
        ]);
    }
}
