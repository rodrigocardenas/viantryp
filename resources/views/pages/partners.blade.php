@extends('pages._layout')

@section('title', 'Partners')
@section('meta_description', 'Únete al ecosistema de partners de Viantryp y haz crecer tu negocio junto a nosotros.')

@section('content')

<!-- HERO -->
<div class="page-hero">
  <div class="page-hero-dots"></div>
  <span class="page-hero-label">Empresa · Partners</span>
  <h1 class="page-hero-title">Crece con<br><em>Viantryp</em></h1>
  <p class="page-hero-sub">Únete a nuestro ecosistema de partners y accede a beneficios exclusivos, comisiones atractivas y soporte dedicado.</p>
</div>

<!-- PARTNER TYPES -->
<section class="section">
  <div class="container">
    <div class="reveal" style="text-align:center; max-width:620px; margin:0 auto 3.5rem;">
      <span class="section-label">Programas de colaboración</span>
      <h2 class="section-title">Elige el programa que<br>mejor se adapta a ti</h2>
    </div>
    <div class="card-grid-3">
      <div class="card reveal d1" style="border-top:3px solid var(--teal);">
        <div class="card-icon"><i class="fas fa-handshake"></i></div>
        <div class="card-title">Partner Referidor</div>
        <div class="card-text" style="margin-bottom:1.2rem;">Recomienda Viantryp y gana una comisión del 20% durante 12 meses por cada cliente que se suscriba a través de tu enlace.</div>
        <ul class="icon-list" style="margin-bottom:1.5rem;">
          <li><i class="fas fa-check"></i>Comisión del 20% por referido</li>
          <li><i class="fas fa-check"></i>Panel de seguimiento en tiempo real</li>
          <li><i class="fas fa-check"></i>Materiales de marketing listos para usar</li>
        </ul>
        <a href="{{ route('contact') }}" class="btn-primary" style="font-size:0.85rem; padding:0.7rem 1.4rem;">Aplicar ahora</a>
      </div>
      <div class="card reveal d2" style="border-top:3px solid var(--lime); position:relative;">
        <span class="badge badge-lime" style="position:absolute;top:1.2rem;right:1.2rem;">Más popular</span>
        <div class="card-icon" style="background:var(--lime-bg); color:var(--lime);"><i class="fas fa-store"></i></div>
        <div class="card-title">Partner Revendedor</div>
        <div class="card-text" style="margin-bottom:1.2rem;">Vende Viantryp a tus propios clientes con descuentos mayoristas y soporte técnico dedicado. Ideal para consultores y agencias de tecnología.</div>
        <ul class="icon-list" style="margin-bottom:1.5rem;">
          <li><i class="fas fa-check"></i>Hasta 35% de descuento mayorista</li>
          <li><i class="fas fa-check"></i>White-label para tus clientes</li>
          <li><i class="fas fa-check"></i>Soporte prioritario por email y chat</li>
        </ul>
        <a href="{{ route('contact') }}" class="btn-primary" style="font-size:0.85rem; padding:0.7rem 1.4rem; background:var(--lime);">Aplicar ahora</a>
      </div>
      <div class="card reveal d3" style="border-top:3px solid #6366f1;">
        <div class="card-icon" style="background:#f0f4ff; color:#6366f1;"><i class="fas fa-code"></i></div>
        <div class="card-title">Partner Tecnológico</div>
        <div class="card-text" style="margin-bottom:1.2rem;">Integra Viantryp con tu plataforma o GDS. Acceso anticipado a la API, co-marketing y presencia en nuestro ecosistema oficial.</div>
        <ul class="icon-list" style="margin-bottom:1.5rem;">
          <li><i class="fas fa-check"></i>Acceso a API enterprise</li>
          <li><i class="fas fa-check"></i>Badge de partner certificado</li>
          <li><i class="fas fa-check"></i>Co-marketing y casos de éxito</li>
        </ul>
        <a href="{{ route('contact') }}" class="btn-primary" style="font-size:0.85rem; padding:0.7rem 1.4rem; background:#6366f1;">Aplicar ahora</a>
      </div>
    </div>
  </div>
</section>

<!-- BENEFITS -->
<section class="section section-bg">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center;">
      <div class="reveal">
        <span class="section-label">Por qué ser partner</span>
        <h2 class="section-title">Beneficios que<br>marcan la diferencia</h2>
        <ul class="icon-list" style="margin-top:1.5rem;">
          <li><i class="fas fa-chart-line"></i><div><strong style="color:var(--navy);">Ingresos recurrentes</strong><br>Gana comisiones mensuales mientras tus referidos sigan activos. Sin límite de referidos, sin fecha de vencimiento.</div></li>
          <li><i class="fas fa-headset"></i><div><strong style="color:var(--navy);">Soporte dedicado</strong><br>Acceso a un Account Manager exclusivo que te ayuda a crecer y a resolver dudas de tus clientes.</div></li>
          <li><i class="fas fa-graduation-cap"></i><div><strong style="color:var(--navy);">Formación y certificación</strong><br>Acceso a nuestra Academia Viantryp con cursos, webinars y materiales de capacitación exclusivos.</div></li>
          <li><i class="fas fa-bullhorn"></i><div><strong style="color:var(--navy);">Co-marketing</strong><br>Te incluimos en nuestra web, newsletters y campañas digitales como partner oficial certificado.</div></li>
        </ul>
      </div>
      <div class="reveal d2">
        <div style="background:var(--navy); border-radius:24px; padding:2.5rem; color:white;">
          <div style="font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#5dcfe0;margin-bottom:1.5rem;">Nuestros números</div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
            <div>
              <span style="font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;color:white;display:block;">120+</span>
              <span style="font-size:0.82rem;color:rgba(255,255,255,0.5);">Partners activos</span>
            </div>
            <div>
              <span style="font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;color:white;display:block;">€85K</span>
              <span style="font-size:0.82rem;color:rgba(255,255,255,0.5);">Pagados en comisiones</span>
            </div>
            <div>
              <span style="font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;color:white;display:block;">20%</span>
              <span style="font-size:0.82rem;color:rgba(255,255,255,0.5);">Comisión base</span>
            </div>
            <div>
              <span style="font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;color:white;display:block;">48h</span>
              <span style="font-size:0.82rem;color:rgba(255,255,255,0.5);">Tiempo de respuesta</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section-sm">
  <div class="container">
    <div class="reveal" style="background:linear-gradient(135deg,var(--teal-light),var(--lime-bg)); border-radius:24px; padding:3.5rem; text-align:center; border:1.5px solid var(--mid-gray);">
      <span class="section-label">¿Listo para ser partner?</span>
      <h2 class="section-title">Aplica hoy y empieza<br>a generar ingresos</h2>
      <p class="section-text" style="max-width:500px; margin:0 auto 2rem;">Nuestro equipo te responderá en menos de 48 horas con toda la información del programa más adecuado para ti.</p>
      <a href="{{ route('contact') }}" class="btn-primary">Contactar con el equipo <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</section>

@endsection
