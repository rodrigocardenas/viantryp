<x-layouts.email>
    <x-slot:title>
        ¡Bienvenido/a a Viantryp, {{ $name }}!
    </x-slot:title>

    <div style="text-align: center; margin-bottom: 25px;">
        <span style="font-size: 50px;">🌍</span>
    </div>

    <p>Estamos emocionados de tenerte con nosotros. En <strong>Viantryp</strong>, nuestra misión es ayudarte a diseñar itinerarios de viaje inolvidables y gestionar cada detalle de forma profesional y sencilla.</p>

    <div class="accent-box">
        <p style="margin: 0; font-weight: 700; color: #1a9a8a;">¿Qué puedes hacer ahora?</p>
        <ul style="margin: 10px 0 0 20px; padding: 0;">
            <li>Diseña itinerarios personalizados con nuestro editor PRO.</li>
            <li>Gestiona tus clientes y sus experiencias de viaje.</li>
            <li>Comparte planes detallados en segundos.</li>
        </ul>
    </div>

    <p>Haz clic en el botón de abajo para completar tu perfil y empezar a crear tu primer viaje:</p>

    <div class="cta-container">
        <a href="{{ route('profile.index') }}" class="cta-button">
            Ir a mi perfil
        </a>
    </div>

    <p style="font-size: 14px; text-align: center; color: #64748b;">
        Si tienes alguna pregunta, simplemente responde a este correo. ¡Estamos aquí para ayudarte!
    </p>
</x-layouts.email>
