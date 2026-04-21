@extends('pages._layout')

@section('title', 'Seguridad')
@section('meta_description', 'Las medidas de seguridad que Viantryp aplica para proteger tus datos y los de tus clientes.')

@section('content')

<style>
  @media (max-width: 768px) {
    .btn-security-mobile {
      width: 200px !important;
      white-space: normal !important;
      text-align: center;
      justify-content: center;
      font-size: 0.85rem !important;
      padding: 0.75rem 0.5rem !important;
    }
  }
</style>

<!-- HERO -->
<div class="page-hero" style="padding:4rem 2rem 3.5rem;">
  <div class="page-hero-dots"></div>
  <span class="page-hero-label">Legal · Seguridad</span>
  <h1 class="page-hero-title">Tu seguridad es nuestra <em>prioridad</em></h1>
  <p class="page-hero-sub">Aplicamos las mejores prácticas del sector para proteger tu información y la de tus clientes en todo momento.</p>
</div>

<!-- LEGAL NAV -->
<div style="background:var(--off-white); border-bottom:1px solid var(--mid-gray);">
  <div class="container">
    <div style="display:flex; gap:0.5rem; padding:0.75rem 0; flex-wrap:wrap;">
      <a href="{{ route('terms') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">Términos de uso</a>
      <a href="{{ route('privacy') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">Privacidad</a>
      <a href="{{ route('gdpr') }}" style="padding:0.4rem 1rem; border-radius:100px; color:var(--text-soft); font-size:0.82rem; font-weight:500; text-decoration:none;">RGPD</a>
      <a href="{{ route('security') }}" style="padding:0.4rem 1rem; border-radius:100px; background:var(--navy); color:white; font-size:0.82rem; font-weight:600; text-decoration:none;">Seguridad</a>
    </div>
  </div>
</div>

<!-- CONTENT -->
<section class="section">
  <div class="container" style="max-width:960px;">

    <!-- Security pillars -->
    <div class="reveal" style="margin-bottom:3rem;">
      <h2 style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; color:var(--navy); margin-bottom:1.5rem; padding-bottom:0.65rem; border-bottom:1.5px solid var(--mid-gray);">Medidas de seguridad implementadas</h2>
      <div class="card-grid-2">
        @foreach([
          ['fa-lock','var(--teal)','Cifrado de extremo a extremo','Datos en tránsito cifrados con TLS 1.3 y en reposo con AES-256. Contraseñas almacenadas con bcrypt. Tokens de sesión con rotación automática.'],
          ['fa-cloud','var(--lime)','Infraestructura en la nube','Desplegado en AWS EU-West (Irlanda) con VPC privada, grupos de seguridad estrictos y acceso SSH restringido a IPs autorizadas.'],
          ['fa-database','#6366f1','Backups y recuperación','Copias de seguridad automáticas diarias con retención de 30 días. Plan de recuperación ante desastres con RTO &lt; 4h y RPO &lt; 1h.'],
          ['fa-shield-alt','#f59e0b','Protección de aplicaciones web','Mitigación de OWASP Top 10 (XSS, CSRF, SQL Injection), WAF, rate limiting y detección de bots.'],
          ['fa-user-shield','var(--teal)','Control de acceso','Autenticación con 2FA opcional, OAuth 2.0 (Google), roles y permisos granulares, y cierre de sesión automático por inactividad.'],
          ['fa-search','#22c55e','Auditoría y monitoreo','Logs inmutables de acciones críticas, alertas automáticas ante anomalías y revisiones de seguridad periódicas.'],
        ] as [$icon,$color,$name,$desc])
        <div class="card">
          <div class="card-icon" style="color:{{ $color }}; background:rgba(0,0,0,0.04);"><i class="fas {{ $icon }}"></i></div>
          <div class="card-title">{{ $name }}</div>
          <div class="card-text">{!! $desc !!}</div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Responsible disclosure -->
    <div class="reveal" style="background:var(--navy); border-radius:20px; padding:2.5rem; display:flex; align-items:center; gap:2.5rem; flex-wrap:wrap;">
      <div style="flex:1; min-width:150px;">
        <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#5dcfe0;display:block;margin-bottom:0.75rem;">Divulgación responsable</span>
        <h3 style="font-family:'Inter',sans-serif; font-size:1.15rem; font-weight:800; color:white; margin-bottom:0.6rem; letter-spacing:-0.02em;">¿Encontraste una vulnerabilidad?</h3>
        <p style="font-size:0.87rem; color:rgba(255,255,255,0.55); line-height:1.7; margin:0;">Reporta cualquier problema de seguridad a <a href="mailto:hola@viantryp.com" style="color:#5dcfe0;">hola@viantryp.com</a> antes de divulgarlo públicamente. Respondemos en menos de 24 h y reconocemos los reportes válidos en nuestro Hall of Fame.</p>
      </div>
      <a href="mailto:hola@viantryp.com" class="btn-primary btn-security-mobile" style="flex-shrink:0; white-space:nowrap;">
        <i class="fas fa-bug"></i> Reportar vulnerabilidad
      </a>
    </div>

  </div>
</section>

@endsection
