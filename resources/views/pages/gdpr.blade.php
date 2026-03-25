@extends('pages._layout')

@section('title', 'RGPD — Protección de datos')
@section('meta_description', 'Cómo Viantryp cumple el Reglamento General de Protección de Datos para todos sus usuarios en la Unión Europea.')

@section('content')

<!-- HERO -->
<div class="page-hero" style="padding:4rem 2rem 3.5rem;">
  <div class="page-hero-dots"></div>
  <span class="page-hero-label">Legal</span>
  <h1 class="page-hero-title"><em>RGPD</em> — Protección de datos</h1>
  <p class="page-hero-sub">Viantryp cumple el Reglamento General de Protección de Datos (RGPD / GDPR) para todos sus usuarios.</p>
</div>

<!-- LEGAL NAV -->
<div style="background:var(--off-white); border-bottom:1px solid var(--mid-gray);">
  <div class="container">
    <div style="display:flex; gap:0.5rem; padding:0.75rem 0; flex-wrap:wrap;">
      <a href="{{ route('terms') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">Términos de uso</a>
      <a href="{{ route('privacy') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">Privacidad</a>
      <a href="{{ route('gdpr') }}" style="padding:0.4rem 1rem; border-radius:100px; background:var(--navy); color:white; font-size:0.82rem; font-weight:600; text-decoration:none;">RGPD</a>
      <a href="{{ route('security') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">Seguridad</a>
    </div>
  </div>
</div>

<!-- CONTENT -->
<section class="section">
  <div class="container" style="max-width:860px;">

    <!-- Commitment strip -->
    <div class="reveal" style="display:flex; align-items:center; gap:1.5rem; background:var(--teal-light); border-radius:16px; padding:1.5rem 2rem; margin-bottom:3.5rem; border:1.5px solid rgba(26,122,138,0.15);">
      <div style="width:44px; height:44px; border-radius:12px; background:var(--teal); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:white; font-size:1.1rem;">
        <i class="fas fa-check-double"></i>
      </div>
      <div>
        <div style="font-weight:700; color:var(--navy); font-size:0.95rem; margin-bottom:0.2rem;">Viantryp es RGPD compliant</div>
        <div style="font-size:0.87rem; color:var(--text-soft);">Aplicamos el Reglamento (UE) 2016/679 para todos los usuarios de la plataforma, tanto viajeros personales como cuentas profesionales. Tus datos siempre bajo tu control.</div>
      </div>
    </div>

    <!-- Who is affected -->
    <div class="reveal" style="margin-bottom:3rem;">
      <h2 style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; color:var(--navy); margin-bottom:1rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">¿A quién aplica el RGPD en Viantryp?</h2>
      <p class="section-text" style="margin-bottom:0;">El RGPD aplica a todos los usuarios residentes en el Espacio Económico Europeo, sin importar si usas Viantryp como viajero individual, para organizar un viaje con tu grupo de amigos, o si eres un profesional que crea propuestas de viaje para clientes. Si gestionas itinerarios con datos personales de tus propios clientes, también aplican reglas sobre responsabilidad de tratamiento que describimos más abajo.</p>
    </div>

    <!-- Roles -->
    <div class="reveal" style="margin-bottom:3rem;">
      <h2 style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; color:var(--navy); margin-bottom:1.2rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Roles de tratamiento</h2>
      <div class="card-grid-2" style="gap:1rem;">
        <div style="background:var(--off-white); border-radius:14px; padding:1.5rem; border:1.5px solid var(--mid-gray);">
          <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
            <i class="fas fa-user" style="color:var(--teal);"></i>
            <strong style="color:var(--navy); font-size:0.9rem;">Usuario personal o de grupo</strong>
          </div>
          <p style="font-size:0.87rem; color:var(--text-soft); line-height:1.6; margin:0;">Si usas Viantryp para tus propios viajes, somos Responsables del tratamiento de tus datos personales de cuenta. Tú defines qué información compartes.</p>
        </div>
        <div style="background:var(--off-white); border-radius:14px; padding:1.5rem; border:1.5px solid var(--mid-gray);">
          <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
            <i class="fas fa-briefcase" style="color:var(--teal);"></i>
            <strong style="color:var(--navy); font-size:0.9rem;">Usuario profesional o empresa</strong>
          </div>
          <p style="font-size:0.87rem; color:var(--text-soft); line-height:1.6; margin:0;">Si introduces datos de terceros (tus clientes), tú eres el Responsable del tratamiento de esos datos y Viantryp actúa como Encargado, procesándolos bajo tus instrucciones.</p>
        </div>
      </div>
    </div>

    <!-- Legal bases -->
    <div class="reveal" style="margin-bottom:3rem;">
      <h2 style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; color:var(--navy); margin-bottom:1.2rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Bases legales del tratamiento</h2>
      <ul class="icon-list">
        <li><i class="fas fa-file-signature"></i><div><strong style="color:var(--navy);">Ejecución del contrato (Art. 6.1.b):</strong> datos de cuenta y uso necesarios para prestarte el Servicio.</div></li>
        <li><i class="fas fa-check-circle"></i><div><strong style="color:var(--navy);">Consentimiento (Art. 6.1.a):</strong> newsletters y comunicaciones de marketing opcionales, con baja en cualquier momento.</div></li>
        <li><i class="fas fa-balance-scale"></i><div><strong style="color:var(--navy);">Interés legítimo (Art. 6.1.f):</strong> prevención de fraude, seguridad de la plataforma y mejora del producto (datos anónimos).</div></li>
        <li><i class="fas fa-gavel"></i><div><strong style="color:var(--navy);">Obligación legal (Art. 6.1.c):</strong> conservación de registros contables y fiscales según la normativa aplicable.</div></li>
      </ul>
    </div>

    <!-- Rights -->
    <div class="reveal" style="margin-bottom:3rem;">
      <h2 style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; color:var(--navy); margin-bottom:1.2rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Tus derechos bajo el RGPD</h2>
      <div class="card-grid-3" style="gap:1rem;">
        @foreach([
          ['fa-eye','Acceso','Solicita una copia completa de tus datos en formato JSON.'],
          ['fa-edit','Rectificación','Corrige cualquier dato incorrecto directamente desde tu perfil.'],
          ['fa-trash-alt','Supresión','Elimina tu cuenta y todos tus datos en un máximo de 30 días.'],
          ['fa-download','Portabilidad','Exporta tus itinerarios y datos en JSON o CSV cuando quieras.'],
          ['fa-pause-circle','Limitación','Solicita que pausemos el tratamiento durante una reclamación.'],
          ['fa-ban','Oposición','Oponte al tratamiento basado en interés legítimo o marketing.'],
        ] as [$icon,$name,$desc])
        <div style="background:var(--off-white); border-radius:12px; padding:1.2rem; border:1.5px solid var(--mid-gray);">
          <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.5rem;">
            <i class="fas {{ $icon }}" style="color:var(--teal); font-size:0.95rem;"></i>
            <strong style="color:var(--navy); font-size:0.88rem;">{{ $name }}</strong>
          </div>
          <p style="font-size:0.82rem; color:var(--text-soft); margin:0; line-height:1.5;">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
    </div>

    <!-- DPA + Transfers -->
    <div class="reveal card-grid-2" style="gap:1.5rem; margin-bottom:2.5rem;">
      <div style="background:var(--off-white); border-radius:14px; padding:1.5rem; border:1.5px solid var(--mid-gray);">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
          <i class="fas fa-file-contract" style="color:var(--teal);"></i>
          <strong style="color:var(--navy); font-size:0.9rem;">DPA para profesionales</strong>
        </div>
        <p style="font-size:0.87rem; color:var(--text-soft); line-height:1.6; margin:0;">Si gestionas datos de clientes en Viantryp y necesitas un Acuerdo de Procesamiento de Datos firmado, escríbenos a <a href="mailto:legal@viantryp.com" style="color:var(--teal);">legal@viantryp.com</a>. Lo enviamos en 5 días hábiles.</p>
      </div>
      <div style="background:var(--off-white); border-radius:14px; padding:1.5rem; border:1.5px solid var(--mid-gray);">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
          <i class="fas fa-globe" style="color:var(--teal);"></i>
          <strong style="color:var(--navy); font-size:0.9rem;">Dónde se almacenan tus datos</strong>
        </div>
        <p style="font-size:0.87rem; color:var(--text-soft); line-height:1.6; margin:0;">Servidores AWS en EU-West (Irlanda). Las transferencias fuera del EEE se rigen por Cláusulas Contractuales Tipo aprobadas por la Comisión Europea.</p>
      </div>
    </div>

    <div class="reveal" style="font-size:0.87rem; color:var(--text-muted); padding:1rem 1.2rem; background:var(--off-white); border-radius:10px; border:1px solid var(--mid-gray);">
      <i class="fas fa-envelope" style="margin-right:0.4rem; color:var(--teal);"></i>
      Contacto RGPD: <a href="mailto:privacidad@viantryp.com" style="color:var(--teal);">privacidad@viantryp.com</a>
      <span style="margin:0 0.5rem; color:var(--mid-gray);">·</span>
      Respondemos en máximo 5 días hábiles.
    </div>

  </div>
</section>

@endsection
