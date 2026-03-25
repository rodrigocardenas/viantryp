@extends('pages._layout')

@section('title', 'Sobre Viantryp')
@section('meta_description', 'Viantryp es la plataforma que convierte cualquier viaje en una experiencia visual, organizada y memorable — para viajeros, grupos, familias y equipos.')

@section('content')

<!-- HERO -->
<div class="page-hero">
  <div class="page-hero-dots"></div>
  <span class="page-hero-label">Sobre Viantryp</span>
  <h1 class="page-hero-title">Una plataforma para<br><em>cualquier viaje</em></h1>
  <p class="page-hero-sub">Ya seas un viajero solo, organices una escapada en grupo o gestiones itinerarios para clientes, Viantryp es tu lienzo digital para tus viajes.</p>
</div>

<!-- MISSION -->
<section class="section">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center;">
      <div class="reveal">
        <span class="section-label">Qué hacemos</span>
        <h2 class="section-title">Viajes bien organizados,<br>sin el caos</h2>
        <p class="section-text">Creemos que planear un viaje debería ser tan emocionante como vivirlo. Por eso creamos Viantryp: una herramienta visual e intuitiva donde cualquier persona puede construir itinerarios hermosos y compartirlos con un solo enlace.</p>
        <p class="section-text">Sin PDFs. Sin hojas de cálculo. Sin grupos de WhatsApp infinitos. Solo un espacio limpio donde tus planes cobran vida.</p>
        <div class="btn-actions" style="margin-top:2rem;">
          <a href="{{ route('home') }}#como-funciona" class="btn-primary">Cómo funciona (Demo) <i class="fas fa-arrow-right"></i></a>
          <a href="{{ route('contact') }}" class="btn-secondary">Contáctanos</a>
        </div>
      </div>
      <div class="reveal d2">
        <div style="background:linear-gradient(135deg,var(--teal-light),var(--lime-bg)); border-radius:24px; padding:2.5rem; border:1.5px solid var(--mid-gray);">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem;">
            <div style="text-align:center;">
              <span style="font-family:'Inter',sans-serif; font-size:2.2rem; font-weight:800; color:var(--navy); display:block; letter-spacing:-0.03em;">-80%</span>
              <span style="font-size:0.82rem; color:var(--text-soft);">Tiempo de construcción de itinerarios</span>
            </div>
            <div style="text-align:center;">
              <span style="font-family:'Inter',sans-serif; font-size:2.2rem; font-weight:800; color:var(--navy); display:block; letter-spacing:-0.03em;">Horas</span>
              <span style="font-size:0.82rem; color:var(--text-soft);">en minutos, gracias al editor visual</span>
            </div>
            <div style="text-align:center;">
              <span style="font-family:'Inter',sans-serif; font-size:2.2rem; font-weight:800; color:var(--navy); display:block; letter-spacing:-0.03em;">40+</span>
              <span style="font-size:0.82rem; color:var(--text-soft);">Correos que dejas de enviar por viaje</span>
            </div>
            <div style="text-align:center;">
              <span style="font-family:'Inter',sans-serif; font-size:2.2rem; font-weight:800; color:var(--teal); display:block; letter-spacing:-0.03em;">Gratis</span>
              <span style="font-size:0.82rem; color:var(--text-soft);">Para empezar, sin tarjeta de crédito</span>
            </div>
          </div>
          <div style="border-top:1px solid var(--mid-gray); padding-top:1.5rem; text-align:center;">
            <span style="font-family:'Inter',sans-serif; font-size:0.88rem; font-weight:700; color:var(--teal);">"Un solo enlace, siempre actualizado. Tu grupo ve los cambios al instante."</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOR EVERYONE -->
<section class="section section-bg">
  <div class="container">
    <div class="reveal" style="text-align:center; max-width:620px; margin:0 auto 3rem;">
      <span class="section-label">Para quién es Viantryp</span>
      <h2 class="section-title">Hecho para todos los<br>que viajan</h2>
      <p class="section-text" style="margin:0;">No importa si viajas solo, en grupo o gestionas viajes para otros. Viantryp se adapta a ti.</p>
    </div>
    <div class="card-grid-3" style="margin-bottom:1.5rem;">
      <div class="card reveal d1">
        <div class="card-icon"><i class="fas fa-user"></i></div>
        <div class="card-title">Viajero independiente</div>
        <div class="card-text">Organiza tu próxima aventura día a día, adjunta reservas, vuelos y notas en un solo lugar. Accede desde cualquier dispositivo sin instalar nada.</div>
      </div>
      <div class="card reveal d2">
        <div class="card-icon" style="background:var(--lime-bg); color:var(--lime);"><i class="fas fa-users"></i></div>
        <div class="card-title">Grupos y familias</div>
        <div class="card-text">Invita a tu grupo a colaborar. Decidan juntos las paradas, compartan el itinerario en un link y mantengan a todos sincronizados sin complicaciones.</div>
      </div>
      <div class="card reveal d3">
        <div class="card-icon" style="background:#f0f4ff; color:#6366f1;"><i class="fas fa-briefcase"></i></div>
        <div class="card-title">Profesionales del turismo</div>
        <div class="card-text">Crea propuestas visuales que conquistan a tus clientes, personaliza la marca y gestiona múltiples itinerarios desde un mismo panel profesional.</div>
      </div>
    </div>
    <div class="card-grid-2" style="max-width:760px; margin:0 auto;">
      <div class="card reveal d1">
        <div class="card-icon" style="background:#fff4e8; color:#f59e0b;"><i class="fas fa-heart"></i></div>
        <div class="card-title">Parejas y viajes de novios</div>
        <div class="card-text">Diseña el itinerario perfecto con todos los detalles, desde el vuelo hasta el restaurante romántico de la última noche.</div>
      </div>
      <div class="card reveal d2">
        <div class="card-icon" style="background:#f0fff4; color:#22c55e;"><i class="fas fa-globe"></i></div>
        <div class="card-title">Nómadas y viajeros frecuentes</div>
        <div class="card-text">Mantén todos tus viajes organizados en un solo workspace. Duplica itinerarios anteriores y adapta los nuevos en minutos.</div>
      </div>
    </div>
  </div>
</section>

<!-- PRINCIPLES -->
<section class="section">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:start;">
      <div class="reveal">
        <span class="section-label">Nuestros principios</span>
        <h2 class="section-title">Por qué Viantryp<br>es diferente</h2>
        <p class="section-text">Construimos Viantryp con la misma filosofía que las mejores herramientas de productividad del mundo: simple por fuera, potente por dentro.</p>
      </div>
      <div class="reveal d2">
        <ul class="icon-list" style="gap:1.4rem;">
          <li><i class="fas fa-rocket"></i><div><strong style="color:var(--navy); display:block; margin-bottom:0.2rem;">Simplicidad radical</strong>Si no cabe en un solo enlace, lo simplificamos. Nada de procesos complejos ni curvas de aprendizaje largas.</div></li>
          <li><i class="fas fa-paint-brush"></i><div><strong style="color:var(--navy); display:block; margin-bottom:0.2rem;">Diseño que importa</strong>Los itinerarios feos no se usan. Los nuestros se comparten, se muestran y se recuerdan.</div></li>
          <li><i class="fas fa-bolt"></i><div><strong style="color:var(--navy); display:block; margin-bottom:0.2rem;">Velocidad de entrega</strong>De la idea al itinerario listo en minutos. Para el que tiene un vuelo mañana o el que planea con 6 meses de anticipo.</div></li>
          <li><i class="fas fa-shield-alt"></i><div><strong style="color:var(--navy); display:block; margin-bottom:0.2rem;">Privacidad por defecto</strong>Tus viajes son tuyos. Controlas quién ve qué, cuándo y cómo. Sin anuncios, sin venta de datos.</div></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- PARTNERS BANNER -->
<section class="section section-bg" id="partners">
  <div class="container">
    <div class="reveal" style="text-align:center; max-width:620px; margin:0 auto 3rem;">
      <span class="section-label">Partners</span>
      <h2 class="section-title">Organizaciones que<br>confían en Viantryp</h2>
      <p class="section-text" style="margin:0;">Trabajamos junto a agencias, empresas tecnológicas y comunidades de viajeros que comparten nuestra visión.</p>
    </div>
    <div class="reveal" style="display:grid; grid-template-columns:repeat(5,1fr); gap:1.2rem; align-items:center; margin-bottom:2.5rem;">
      @foreach([
        ['fa-suitcase-rolling','TravelPro'],
        ['fa-plane','AirElite'],
        ['fa-hotel','LuxStay'],
        ['fa-map-marked-alt','RouteXpert'],
        ['fa-globe-americas','MundoTrips'],
        ['fa-building','CorpTravel'],
        ['fa-star','PremierVoyages'],
        ['fa-compass','Exploradores'],
        ['fa-handshake','TechTrip'],
        ['fa-laptop-code','NextDest'],
      ] as [$icon,$name])
      <div style="border:1.5px solid var(--mid-gray); border-radius:14px; padding:1.4rem 0.8rem; text-align:center; transition:all 0.3s; background:var(--white);" onmouseover="this.style.borderColor='var(--teal)';this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(26,122,138,0.08)'" onmouseout="this.style.borderColor='var(--mid-gray)';this.style.transform='none';this.style.boxShadow='none'">
        <div style="width:42px; height:42px; border-radius:10px; background:var(--teal-light); display:flex; align-items:center; justify-content:center; margin:0 auto 0.65rem; color:var(--teal); font-size:1.1rem;">
          <i class="fas {{ $icon }}"></i>
        </div>
        <div style="font-size:0.75rem; font-weight:700; color:var(--navy);">{{ $name }}</div>
      </div>
      @endforeach
    </div>
    <div class="reveal" style="text-align:center;">
      <p style="font-size:0.87rem; color:var(--text-muted); margin-bottom:1.25rem;"><i class="fas fa-info-circle" style="margin-right:0.4rem; color:var(--teal);"></i>¿Quieres aparecer aquí? Escríbenos para ser partner oficial.</p>
      <a href="{{ route('contact') }}" class="btn-secondary">Convertirse en partner <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section">
  <div class="container">
    <div class="reveal" style="background:linear-gradient(135deg,var(--navy),#1a3a50); border-radius:24px; padding:3.5rem; text-align:center;">
      <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#5dcfe0;display:block;margin-bottom:1rem;">Empieza hoy, gratis</span>
      <h2 style="font-family:'Inter',sans-serif; font-size:clamp(1.6rem,3vw,2.2rem); font-weight:800; color:white; margin-bottom:1rem; letter-spacing:-0.03em;">Tu próximo viaje merece<br>una mejor herramienta</h2>
      <p style="color:rgba(255,255,255,0.55); margin-bottom:2rem; font-size:0.97rem;">Sin tarjeta de crédito. Sin instalaciones. Listo en menos de un minuto.</p>
      <div class="btn-actions" style="justify-content:center;">
        <a href="{{ route('register') }}" class="btn-primary">Crear cuenta gratis <i class="fas fa-arrow-right"></i></a>
        <a href="{{ route('contact') }}" class="btn-secondary" style="color:rgba(255,255,255,0.7); border-color:rgba(255,255,255,0.2);">Hablar con el equipo</a>
      </div>
    </div>
  </div>
</section>

@endsection
