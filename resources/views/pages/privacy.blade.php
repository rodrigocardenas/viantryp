@extends('pages._layout')

@section('title', 'Política de Privacidad')
@section('meta_description', 'Cómo Viantryp recopila, usa y protege tu información personal. Válido para todos los usuarios de la plataforma.')

@section('content')

<!-- HERO -->
<div class="page-hero" style="padding:4rem 2rem 3.5rem;">
  <div class="page-hero-dots"></div>
  <span class="page-hero-label">Legal</span>
  <h1 class="page-hero-title">Política de <em>Privacidad</em></h1>
  <p class="page-hero-sub">Tu información es tuya. Sin anuncios, sin venta de datos. Aquí te explicamos exactamente cómo la tratamos.</p>
</div>

<!-- LEGAL NAV -->
<div style="background:var(--off-white); border-bottom:1px solid var(--mid-gray);">
  <div class="container">
    <div style="display:flex; gap:0.5rem; padding:0.75rem 0; flex-wrap:wrap;">
      <a href="{{ route('terms') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">Términos de uso</a>
      <a href="{{ route('privacy') }}" style="padding:0.4rem 1rem; border-radius:100px; background:var(--navy); color:white; font-size:0.82rem; font-weight:600; text-decoration:none;">Privacidad</a>
      <a href="{{ route('gdpr') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">RGPD</a>
      <a href="{{ route('security') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">Seguridad</a>
    </div>
  </div>
</div>


<!-- CONTENT -->
<section class="section">
  <div class="container">
    <div class="legal-layout">
      <div class="legal-sidebar">
        <div style="font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin-bottom:1rem;">Contenido</div>
        <ul style="list-style:none; display:flex; flex-direction:column; gap:0.5rem;">
          @foreach([
            ['a-quien','¿A quién aplica?'],
            ['recopilacion','Datos que recopilamos'],
            ['uso','Cómo los usamos'],
            ['compartir','Con quién los compartimos'],
            ['cookies','Cookies'],
            ['derechos','Tus derechos'],
            ['retencion','Retención'],
            ['contacto-privacidad','Contacto'],
          ] as [$id,$label])
          <li><a href="#{{ $id }}" style="font-size:0.84rem; color:var(--text-soft); text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='var(--teal)'" onmouseout="this.style.color='var(--text-soft)'">{{ $label }}</a></li>
          @endforeach
        </ul>
        <div style="margin-top:2rem; padding:1rem 1.2rem; background:var(--teal-light); border-radius:12px; font-size:0.82rem; color:var(--teal);">
          <i class="fas fa-clock" style="margin-right:0.4rem;"></i><strong>Última actualización:</strong><br>Enero 2026
        </div>
      </div>

      <div class="legal-content">

        <div id="a-quien" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">¿A quién aplica esta política?</h2>
          <p class="section-text" style="margin-bottom:0;">Esta política aplica a todos los usuarios de Viantryp: viajeros individuales que crean itinerarios personales, grupos que planifican juntos, familias que organizan vacaciones, y profesionales o empresas que usan la plataforma para gestionar viajes de terceros. Independientemente de tu caso de uso, tratamos tu información con el mismo nivel de cuidado y transparencia.</p>
        </div>

        <div id="recopilacion" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Datos que recopilamos</h2>
          <ul class="icon-list">
            <li><i class="fas fa-user"></i><div><strong style="color:var(--navy);">Datos de cuenta:</strong> nombre, email y foto de perfil al registrarte. Sólo lo imprescindible para que funcione.</div></li>
            <li><i class="fas fa-file-alt"></i><div><strong style="color:var(--navy);">Contenido que creas:</strong> itinerarios, notas, imágenes y documentos de viaje que subes voluntariamente.</div></li>
            <li><i class="fas fa-chart-bar"></i><div><strong style="color:var(--navy);">Datos de uso anónimos:</strong> qué funcionalidades usas y cuánto tiempo, para mejorar la plataforma. Sin ids personales vinculados.</div></li>
            <li><i class="fas fa-credit-card"></i><div><strong style="color:var(--navy);">Datos de pago:</strong> procesados de forma segura por Stripe. Viantryp nunca almacena números de tarjeta.</div></li>
          </ul>
        </div>

        <div id="uso" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Cómo usamos tu información</h2>
          <p class="section-text" style="margin-bottom:0;">Usamos tus datos para: hacer funcionar el Servicio, enviarte notificaciones relevantes del producto (puedes desuscribirte en cualquier momento), mejorar la experiencia de todos los usuarios con base en patrones anónimos de uso, y cumplir con obligaciones legales. <strong style="color:var(--navy);">Nunca</strong> usamos tus datos para publicidad de terceros ni los compartimos con anunciantes.</p>
        </div>

        <div id="compartir" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Con quién compartimos tus datos</h2>
          <p class="section-text">Solo con proveedores esenciales para operar el Servicio, sujetos a contratos de procesamiento de datos conformes al RGPD:</p>
          <ul class="icon-list">
            <li><i class="fas fa-server"></i><div><strong style="color:var(--navy);">AWS (Irlanda):</strong> Infraestructura y almacenamiento.</div></li>
            <li><i class="fas fa-credit-card"></i><div><strong style="color:var(--navy);">Stripe:</strong> Procesamiento seguro de pagos.</div></li>
            <li><i class="fas fa-envelope"></i><div><strong style="color:var(--navy);">Hostinger Mail:</strong> Email transaccional (confirmaciones, notificaciones).</div></li>
          </ul>
          <p class="section-text" style="margin-top:1rem; margin-bottom:0;">No compartimos ningún dato personal identificable con otros terceros sin tu consentimiento explícito, salvo requerimiento legal.</p>
        </div>

        <div id="cookies" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Cookies</h2>
          <p class="section-text" style="margin-bottom:0;">Usamos cookies de sesión (esenciales, no se pueden desactivar) y cookies analíticas anónimas con Google Analytics (IP anonimizada). Puedes rechazar las analíticas desde la configuración de tu navegador o el panel de consentimiento dentro de la app.</p>
        </div>

        <div id="derechos" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Tus derechos</h2>
          <p class="section-text">Como usuario de Viantryp — seas viajero individual, miembro de un grupo o profesional — tienes derecho a: acceder a tus datos, corregir información incorrecta, descargar una copia de tu contenido (portabilidad), eliminar tu cuenta y datos de forma permanente, y oponerte al tratamiento en ciertos casos.</p>
          <p class="section-text" style="margin-bottom:0;">Ejerce cualquiera de estos derechos escribiendo a <a href="mailto:privacidad@viantryp.com" style="color:var(--teal);">privacidad@viantryp.com</a>. Respondemos en un máximo de 5 días hábiles.</p>
        </div>

        <div id="retencion" style="margin-bottom:2.5rem;">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Retención de datos</h2>
          <p class="section-text" style="margin-bottom:0;">Conservamos tus datos mientras tu cuenta esté activa. Si eliminas tu cuenta, borramos toda tu información personal en 30 días. Los datos anonimizados de uso pueden conservarse para análisis de producto sin posibilidad de identificación.</p>
        </div>

        <div id="contacto-privacidad">
          <h2 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; color:var(--navy); margin-bottom:0.85rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Contacto</h2>
          <p class="section-text" style="margin-bottom:0;">Para cualquier consulta sobre privacidad escríbenos a <a href="mailto:privacidad@viantryp.com" style="color:var(--teal);">privacidad@viantryp.com</a>. Somos un equipo pequeño y cercano — te responderemos de forma humana, no con plantillas.</p>
        </div>

      </div>
    </div>
  </div>
</section>

@endsection
