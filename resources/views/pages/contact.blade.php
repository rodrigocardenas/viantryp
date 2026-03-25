@extends('pages._layout')

@section('title', 'Contacto')
@section('meta_description', 'Ponte en contacto con el equipo de Viantryp por email. Respondemos en menos de 24 horas.')

@section('content')

<!-- HERO -->
<div class="page-hero" style="padding:4.5rem 2rem 4rem;">
  <div class="page-hero-dots"></div>
  <span class="page-hero-label">Empresa · Contacto</span>
  <h1 class="page-hero-title">Hablemos sobre<br><em>tu proyecto</em></h1>
  <p class="page-hero-sub">Nuestro equipo está listo para ayudarte. Cuéntanos qué necesitas y te respondemos en menos de 24 horas.</p>
</div>

<!-- CONTACT GRID -->
<section class="section">
  <div class="container">
    <div class="contact-split">

      <!-- INFO LEFT -->
      <div class="reveal">
        <span class="section-label">Información de contacto</span>
        <h2 class="section-title" style="margin-bottom:1.5rem;">Siempre disponibles<br>para ti</h2>

        <!-- Email card -->
        <div style="display:flex; align-items:flex-start; gap:1rem; margin-bottom:2.5rem;">
          <div style="width:48px; height:48px; border-radius:14px; background:var(--teal-light); display:flex; align-items:center; justify-content:center; color:var(--teal); flex-shrink:0; font-size:1.15rem;">
            <i class="fas fa-envelope"></i>
          </div>
          <div>
            <div style="font-weight:700; color:var(--navy); margin-bottom:0.25rem;">Correo electrónico</div>
            <a href="mailto:hola@viantryp.com" style="color:var(--teal); font-size:0.97rem; font-weight:600;">hola@viantryp.com</a>
            <div style="font-size:0.82rem; color:var(--text-muted); margin-top:0.2rem;">Respondemos en menos de 24 horas hábiles</div>
          </div>
        </div>

        <!-- What can we help -->
        <div style="background:var(--off-white); border-radius:16px; padding:1.5rem; border:1.5px solid var(--mid-gray);">
          <div style="font-weight:700; color:var(--navy); margin-bottom:1rem; font-size:0.9rem;"><i class="fas fa-question-circle" style="color:var(--teal); margin-right:0.5rem;"></i>¿Para qué puedo escribirles?</div>
          <ul class="icon-list">
            <li><i class="fas fa-check"></i>Solicitar una demo personalizada</li>
            <li><i class="fas fa-check"></i>Consultas sobre precios y planes</li>
            <li><i class="fas fa-check"></i>Soporte técnico avanzado</li>
            <li><i class="fas fa-check"></i>Propuestas de partners</li>
            <li><i class="fas fa-check"></i>Consultas de prensa y medios</li>
          </ul>
        </div>

        <!-- Schedule note -->
        <div style="margin-top:1.5rem; display:flex; align-items:center; gap:0.75rem; font-size:0.85rem; color:var(--text-muted);">
          <i class="fas fa-clock" style="color:var(--teal);"></i>
          Horario de atención: Lun–Vie · 9:00 – 18:00 (GMT-5)
        </div>
      </div>

      <!-- FORM RIGHT -->
      <div class="reveal d2">
        <div style="background:var(--white); border-radius:24px; padding:2.5rem; border:1.5px solid var(--mid-gray); box-shadow:0 20px 50px rgba(0,0,0,0.04);">
          <h3 style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; color:var(--navy); margin-bottom:0.4rem; letter-spacing:-0.02em;">Envíanos un mensaje</h3>
          <p style="font-size:0.88rem; color:var(--text-soft); margin-bottom:2rem;">Completa el formulario y te contactaremos lo antes posible.</p>

          <form action="mailto:hola@viantryp.com" method="GET" style="display:flex; flex-direction:column; gap:1.2rem;">
            <div class="form-row">
              <div>
                <label style="font-size:0.82rem; font-weight:600; color:var(--navy); display:block; margin-bottom:0.4rem;">Nombre *</label>
                <input type="text" placeholder="Tu nombre" style="width:100%; padding:0.75rem 1rem; border:1.5px solid var(--mid-gray); border-radius:10px; font-size:0.9rem; font-family:'Inter',sans-serif; outline:none; transition:border-color 0.2s; color:var(--text);" onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--mid-gray)'">
              </div>
              <div>
                <label style="font-size:0.82rem; font-weight:600; color:var(--navy); display:block; margin-bottom:0.4rem;">Agencia / Empresa</label>
                <input type="text" placeholder="Nombre de tu agencia" style="width:100%; padding:0.75rem 1rem; border:1.5px solid var(--mid-gray); border-radius:10px; font-size:0.9rem; font-family:'Inter',sans-serif; outline:none; transition:border-color 0.2s; color:var(--text);" onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--mid-gray)'">
              </div>
            </div>
            <div>
              <label style="font-size:0.82rem; font-weight:600; color:var(--navy); display:block; margin-bottom:0.4rem;">Email *</label>
              <input type="email" placeholder="tu@agencia.com" style="width:100%; padding:0.75rem 1rem; border:1.5px solid var(--mid-gray); border-radius:10px; font-size:0.9rem; font-family:'Inter',sans-serif; outline:none; transition:border-color 0.2s; color:var(--text);" onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--mid-gray)'">
            </div>
            <div>
              <label style="font-size:0.82rem; font-weight:600; color:var(--navy); display:block; margin-bottom:0.4rem;">Motivo de contacto</label>
              <select style="width:100%; padding:0.75rem 1rem; border:1.5px solid var(--mid-gray); border-radius:10px; font-size:0.9rem; font-family:'Inter',sans-serif; outline:none; color:var(--text); background:white; transition:border-color 0.2s;" onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--mid-gray)'">
                <option value="">Selecciona un motivo</option>
                <option>Demo personalizada</option>
                <option>Consulta sobre precios</option>
                <option>Soporte técnico</option>
                <option>Partners</option>
                <option>Prensa y medios</option>
                <option>Otro</option>
              </select>
            </div>
            <div>
              <label style="font-size:0.82rem; font-weight:600; color:var(--navy); display:block; margin-bottom:0.4rem;">Mensaje *</label>
              <textarea placeholder="Cuéntanos cómo podemos ayudarte..." rows="5" style="width:100%; padding:0.75rem 1rem; border:1.5px solid var(--mid-gray); border-radius:10px; font-size:0.9rem; font-family:'Inter',sans-serif; outline:none; resize:vertical; transition:border-color 0.2s; color:var(--text);" onfocus="this.style.borderColor='var(--teal)'" onblur="this.style.borderColor='var(--mid-gray)'"></textarea>
            </div>
            <button type="submit" class="btn-primary" style="justify-content:center; margin-top:0.4rem; cursor:pointer; border:none;">
              Enviar mensaje
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
