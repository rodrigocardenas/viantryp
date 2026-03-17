<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $trip->title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>
<body>
    <script>
        // Set up variables for pro-viewer.js
        window.tripId = {{ $trip->id }};
        window.viantrypUserName = @json($trip->user->display_name ?? 'Viantryp');
        window.proStatus = "{{ $trip->status }}";
        
        let proState = @json($trip->pro_state);
        if (typeof proState === 'string') {
            try { proState = JSON.parse(proState); } catch(e){}
        }
        
        // Ensure proState is an object
        if (!proState) proState = {};
        
        // Force public link settings
        proState.isPublicLink = true;
        proState.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        proState.tripId = window.tripId;
        proState.userName = window.viantrypUserName;
        proState.status = window.proStatus;
        proState.origin = window.location.origin;
        proState.themeColor = "{{ $trip->user->theme_color ?? 'default' }}";
        proState.displayNameType = "{{ $trip->user->display_name_type ?? 'personal' }}";
        proState.agencyLogo = "{{ $trip->user->agency_logo ? asset('storage/' . $trip->user->agency_logo) : '' }}";
        proState.agencyName = @json($trip->user->agency_name ?? '');
        proState.userFullName = @json($trip->user->name . ' ' . $trip->user->last_name);
        window.shareToken = @json(request()->route('token'));
    </script>
    <script src="{{ asset('js/trips/pro-viewer.js') }}?v={{ time() }}"></script>
    <script>
        // Generate and inject the HTML document
        const generatedHTML = buildPreviewHTML(proState);
        document.open();
        document.write(generatedHTML);
        document.close();
    </script>
</body>
</html>
