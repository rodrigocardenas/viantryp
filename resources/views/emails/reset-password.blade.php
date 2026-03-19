<x-layouts.email>
    <x-slot:title>
        Recupera tu contraseña
    </x-slot:title>

    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Viantryp</strong>.</p>
    
    <p>Si no has solicitado este cambio, por favor ignora este mensaje o contáctanos si crees que hay un problema.</p>

    <div class="cta-container">
        <a href="{{ $url }}" class="cta-button">
            Reestablecer Contraseña
        </a>
    </div>

    <p>Este enlace de recuperación de contraseña expirará en {{ $count }} minutos.</p>

    <div class="accent-box" style="font-size: 13px; color: #64748b;">
        <p style="margin: 0;"><strong>Si tienes problemas con el botón:</strong></p>
        <p style="word-break: break-all; margin: 5px 0 0 0;">
            Copia y pega la siguiente URL en tu navegador:<br>
            <a href="{{ $url }}" style="color: #1a9a8a;">{{ $url }}</a>
        </p>
    </div>
</x-layouts.email>
