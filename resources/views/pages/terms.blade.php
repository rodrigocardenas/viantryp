@extends('pages._layout')

@section('title', 'Términos de uso')
@section('meta_description', 'Términos y condiciones de uso de Viantryp, la plataforma para crear y compartir itinerarios de viaje digitales.')

@section('content')

<!-- HERO -->
<div class="page-hero" style="padding:4rem 2rem 3.5rem;">
  <div class="page-hero-dots"></div>
  <span class="page-hero-label">Legal</span>
  <h1 class="page-hero-title">Términos de <em>uso</em></h1>
  <p class="page-hero-sub">Estos términos rigen el uso de Viantryp por parte de cualquier persona — viajero individual, grupo, familia o empresa.</p>
</div>

<!-- LEGAL NAV -->
<div style="background:var(--off-white); border-bottom:1px solid var(--mid-gray);">
  <div class="container">
    <div style="display:flex; gap:0.5rem; padding:0.75rem 0; flex-wrap:wrap;">
      <a href="{{ route('terms') }}" style="padding:0.4rem 1rem; border-radius:100px; background:var(--navy); color:white; font-size:0.82rem; font-weight:600; text-decoration:none;">Términos de uso</a>
      <a href="{{ route('privacy') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='var(--navy)'" onmouseout="this.style.color='var(--text-soft)'">Privacidad</a>
      <a href="{{ route('gdpr') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='var(--navy)'" onmouseout="this.style.color='var(--text-soft)'">RGPD</a>
      <a href="{{ route('security') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='var(--navy)'" onmouseout="this.style.color='var(--text-soft)'">Seguridad</a>
    </div>
  </div>
</div>

<!-- CONTENT -->
<section class="section">
  <div class="container">
    <div style="display:grid; grid-template-columns:220px 1fr; gap:4rem; align-items:start;">

      <!-- STICKY INDEX -->
      <div style="position:sticky; top:80px;">
        <div style="font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin-bottom:1rem;">Contenido</div>
        <ul style="list-style:none; display:flex; flex-direction:column; gap:0.5rem;">
          @foreach([
            ['aceptacion','1. Aceptación'],
            ['descripcion','2. El servicio'],
            ['cuenta','3. Tu cuenta'],
            ['uso-aceptable','4. Uso aceptable'],
            ['propiedad','5. Tu contenido'],
            ['pagos','6. Pagos'],
            ['limitacion','7. Responsabilidad'],
            ['cancelacion','8. Cancelación'],
            ['modificaciones','9. Cambios'],
            ['contacto-legal','10. Contacto'],
          ] as [$id,$label])
          <li><a href="#{{ $id }}" style="font-size:0.84rem; color:var(--text-soft); text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-soft)'">{{ $label }}</a></li>
          @endforeach
        </ul>
        <div style="margin-top:2rem; padding:1rem 1.2rem; background:var(--teal-light); border-radius:12px; font-size:0.82rem; color:var(--teal);">
          <i class="fas fa-clock" style="margin-right:0.4rem;"></i><strong>Última actualización:</strong><br>Enero 2026
        </div>
      </div>

      <!-- BODY -->
      <div style="max-width:720px;">

        <div id="aceptacion" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">1. Aceptación de los términos</h2>
          <p class="section-text" style="margin-bottom:0;">Al acceder o usar Viantryp, aceptas estos Términos. Viantryp es una plataforma de itinerarios digitales disponible para cualquier tipo de usuario: viajeros individuales, grupos, familias, profesionales del turismo y empresas. Si usas el Servicio en nombre de una organización, estas condiciones aplican también a ella. Si no estás de acuerdo, por favor no uses el Servicio.</p>
        </div>

        <div id="descripcion" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">2. El servicio</h2>
          <p class="section-text" style="margin-bottom:0;">Viantryp es una plataforma SaaS que permite crear, personalizar y compartir itinerarios de viaje digitales. Incluye un editor visual, sistema de colaboración por enlace, almacenamiento de documentos de viaje y herramientas de personalización. Nos reservamos el derecho de actualizar o modificar el Servicio en cualquier momento, con previo aviso cuando sea posible.</p>
        </div>

        <div id="cuenta" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">3. Tu cuenta</h2>
          <p class="section-text" style="margin-bottom:0;">Para acceder al Servicio necesitas crear una cuenta con información verídica. Eres responsable de mantener la seguridad de tus credenciales y de todo lo que ocurra desde tu sesión. Una cuenta es de uso personal; si gestionas un equipo, usa los planes con soporte de colaboradores. Notifícanos inmediatamente ante cualquier acceso no autorizado.</p>
        </div>

        <div id="uso-aceptable" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">4. Uso aceptable</h2>
          <p class="section-text">Viantryp puede usarse libremente para planificar viajes personales, colaborar con amigos o familia, y crear propuestas profesionales. Sin embargo, no está permitido:</p>
          <ul class="icon-list">
            <li><i class="fas fa-times" style="color:#ef4444;"></i>Publicar contenido ilegal, ofensivo o que infrinja derechos de terceros.</li>
            <li><i class="fas fa-times" style="color:#ef4444;"></i>Intentar acceder sin autorización a cuentas o sistemas de otros usuarios.</li>
            <li><i class="fas fa-times" style="color:#ef4444;"></i>Usar el Servicio para distribuir spam, malware o realizar ataques.</li>
            <li><i class="fas fa-times" style="color:#ef4444;"></i>Revender o sublicenciar el Servicio sin autorización expresa de Viantryp.</li>
          </ul>
        </div>

        <div id="propiedad" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">5. Tu contenido</h2>
          <p class="section-text" style="margin-bottom:0;">Los itinerarios, textos, imágenes y datos que creas en Viantryp son tuyos. Al subirlos, nos otorgas una licencia limitada y no exclusiva para almacenarlos y mostrarlos según tus configuraciones de privacidad. Puedes exportar o eliminar tu contenido en cualquier momento. El software, diseño y tecnología de Viantryp son propiedad de Viantryp SAS y están protegidos por la legislación aplicable.</p>
        </div>

        <div id="pagos" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">6. Pagos y planes</h2>
          <p class="section-text" style="margin-bottom:0;">Viantryp ofrece un plan gratuito permanente y planes de pago con funcionalidades avanzadas. Los planes de pago incluyen 14 días de prueba gratuita sin necesidad de tarjeta de crédito. Tras el período de prueba, el cobro es automático salvo cancelación previa. Los precios se expresan en USD. Nos reservamos el derecho de actualizar precios con 30 días de preaviso.</p>
        </div>

        <div id="limitacion" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">7. Limitación de responsabilidad</h2>
          <p class="section-text" style="margin-bottom:0;">El Servicio se provee "tal cual". No garantizamos disponibilidad ininterrumpida ni ausencia de errores. Viantryp no es responsable por la pérdida de datos derivada de un uso incorrecto, ni por decisiones de viaje tomadas con base en información que el usuario introduce en la plataforma. Nuestra responsabilidad total no superará el importe pagado en los últimos 12 meses.</p>
        </div>

        <div id="cancelacion" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">8. Cancelación</h2>
          <p class="section-text" style="margin-bottom:0;">Puedes cancelar tu suscripción en cualquier momento desde la configuración de tu cuenta. Los datos se conservan 30 días después de la cancelación y luego se eliminan de forma definitiva. El plan gratuito no caduca y puede mantenerse indefinidamente.</p>
        </div>

        <div id="modificaciones" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">9. Cambios en los términos</h2>
          <p class="section-text" style="margin-bottom:0;">Podemos actualizar estos Términos. Te avisaremos por email con al menos 15 días de antelación ante cambios relevantes. El uso continuado del Servicio tras esa notificación implica la aceptación de los nuevos términos.</p>
        </div>

        <div id="contacto-legal">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">10. Contacto</h2>
          <p class="section-text" style="margin-bottom:0;">¿Tienes preguntas sobre estos Términos? Escríbenos a <a href="mailto:legal@viantryp.com" style="color:var(--teal);">legal@viantryp.com</a>. Respondemos en un plazo máximo de 48 horas hábiles.</p>
        </div>

      </div>
    </div>
  </div>
</section>

@endsection
