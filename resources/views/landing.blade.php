@extends('layouts.site')

@section('nav')
    <a href="#como-funciona">Cómo funciona</a>
    <a href="#privacidad">Privacidad</a>
    <a href="#preguntas">Preguntas</a>
    <a class="button dark small" href="mailto:{{ $email }}?subject=Quiero%20probar%20Lote">Pedir acceso</a>
@endsection

@section('content')
<main>
    <section class="hero">
      <div class="wrap">
        <div>
          <span class="pill">Beta privada · iPhone y Mac</span>
          <h1>Graba tus videos. <em>Lote hace lo demás.</em></h1>
          <p class="lead">Subtítulos, título, descripción y la fecha de cada publicación. Tú eliges los videos; Lote los deja programados en Instagram, TikTok y YouTube.</p>
          <div class="actions">
            <a class="button primary" href="mailto:{{ $email }}?subject=Quiero%20probar%20Lote">Quiero probar Lote</a>
            <a class="button secondary" href="#como-funciona">Ver cómo funciona</a>
          </div>
          <p class="note">Los videos se procesan en tu dispositivo. Sin servidores de por medio.</p>
        </div>

        <div class="phone-stage" style="position:relative">
          <div class="phone" aria-hidden="true">
            <div class="screen">
              <div class="island"></div>
              <div class="badges"><span>Reel</span><span>0:42</span></div>
              <div class="caption" id="caption"></div>
            </div>
          </div>
          <div class="float one"><b>Silencio del inicio</b>quitado · 1.8 s</div>
          <div class="float two"><span class="dot"></span><b>Programado</b>Jueves 7:35 p. m.</div>
        </div>
      </div>
    </section>

    <section id="como-funciona">
      <div class="wrap">
        <div class="section-head reveal">
          <div class="kicker">Cómo funciona</div>
          <h2>De la galería a tu calendario, en un solo lote.</h2>
          <p>Eliges de golpe los videos que grabaste. Lote hace cada paso y tú solo revisas.</p>
        </div>
        <div class="steps">
          <div class="step card reveal"><h3>Quita el silencio</h3><p>Corta los segundos muertos del inicio, o todas las pausas largas si lo prefieres.</p></div>
          <div class="step card reveal"><h3>Transcribe en español</h3><p>Entiende lo que dices palabra por palabra, con su tiempo exacto.</p></div>
          <div class="step card reveal"><h3>Pone subtítulos</h3><p>Letras grandes que resaltan la palabra que estás diciendo. O sin subtítulos, si así lo quieres.</p></div>
          <div class="step card reveal"><h3>Escribe los textos</h3><p>Título, descripción y categoría de cada video. Tú editas lo que quieras y lo tuyo manda.</p></div>
          <div class="step card reveal"><h3>Arma el plan</h3><p>Reparte los videos en los mejores huecos, respetando lo que ya tenías programado.</p></div>
          <div class="step card reveal"><h3>Programa en Metricool</h3><p>Instagram, TikTok y YouTube Shorts, cada uno a su hora. Primero lo puedes simular.</p></div>
        </div>
      </div>
    </section>

    <section>
      <div class="wrap">
        <div class="section-head reveal">
          <div class="kicker">Hecho a tu manera</div>
          <h2>Cada paso lo eliges tú.</h2>
          <p>Lote no te obliga a nada: prende y apaga lo que necesites.</p>
        </div>
        <div class="features">
          <div class="feature card wide reveal">
            <div>
              <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg></div>
              <h3>Un plan que respeta tu calendario</h3>
              <p>Cuántos posts al día, a qué horas, con cuánto espacio entre uno y otro, y días con reglas especiales. Lote lee lo que ya tienes en Metricool y nunca programa dos veces el mismo video.</p>
            </div>
            <div class="week" aria-hidden="true">
              <div class="day"><b>L</b><div class="slot old">9:00</div><div class="slot new">13:05</div><div class="slot new">19:40</div></div>
              <div class="day"><b>M</b><div class="slot new">10:15</div><div class="slot old">14:00</div><div class="slot new">20:10</div></div>
              <div class="day"><b>M</b><div class="slot new">8:50</div><div class="slot new">12:30</div><div class="slot new">18:25</div></div>
              <div class="day"><b>J</b><div class="slot old">11:00</div><div class="slot new">19:35</div></div>
              <div class="day"><b>V</b><div class="slot new">9:40</div><div class="slot new">15:20</div><div class="slot faith">19:00</div></div>
              <div class="day"><b>S</b><div class="slot faith">10:30</div><div class="slot new">20:15</div></div>
              <div class="day"><b>D</b><div class="slot new">11:45</div><div class="slot new">18:05</div></div>
            </div>
          </div>
          <div class="feature card reveal">
            <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4 8.1 15.9M14.5 14.5 20 20M8.1 8.1 12 12"/></svg></div>
            <h3>Clips de tus videos largos</h3>
            <p>Una plática, un podcast o un en vivo: la IA propone los mejores momentos, tú eliges cuáles entran. Si el video es horizontal, lo pasa a vertical siguiendo tu cara.</p>
          </div>
          <div class="feature card reveal">
            <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg></div>
            <h3>Estilos de subtítulos</h3>
            <p>Resaltado, clásico, en caja o minimal. Corrige las palabras que se transcriben mal una vez y Lote se acuerda.</p>
            <div class="chips"><span class="on">Resaltado</span><span>Clásico</span><span>Caja</span><span>Minimal</span><span>Sin subtítulos</span></div>
          </div>
          <div class="feature card reveal">
            <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 4.6L18.5 9l-4.6 1.9L12 15.5l-1.9-4.6L5.5 9l4.6-1.4z"/><path d="M19 15l.8 1.9 1.9.8-1.9.8L19 20.5l-.8-2-1.9-.8 1.9-.8z"/></svg></div>
            <h3>Textos con la IA que prefieras</h3>
            <p>La IA de Apple en tu propio dispositivo, OpenAI con tu propia clave, o escribirlos tú. Todo lo puedes revisar y editar antes de programar.</p>
          </div>
          <div class="feature card reveal">
            <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 4v5h-5"/></svg></div>
            <h3>Trial Reel o Reel normal</h3>
            <p>Prueba tus videos con gente que no te sigue, o publícalos directo en tu perfil. En TikTok y YouTube Shorts, al mismo tiempo.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="privacidad">
      <div class="wrap">
        <div class="band reveal">
          <div class="section-head">
            <div class="kicker">Privacidad</div>
            <h2>Tus videos no salen de tu dispositivo hasta que tú lo decides.</h2>
            <p>Lote no tiene servidores propios. Todo el trabajo pesado pasa en tu iPhone o tu Mac.</p>
          </div>
          <div class="privacy-list">
            <div><h3>Procesado en el dispositivo</h3><p>Recorte, transcripción y subtítulos corren en tu equipo, con su propio chip.</p></div>
            <div><h3>Solo sus propios archivos</h3><p>En Google Drive, Lote solo ve los archivos que él mismo sube, y puede borrarlos cuando ya se publicaron.</p></div>
            <div><h3>Tus llaves, en tu llavero</h3><p>Las conexiones con Metricool y Google se guardan en el llavero seguro de Apple, nunca en un servidor.</p></div>
          </div>
        </div>
      </div>
    </section>

    <section id="preguntas">
      <div class="wrap">
        <div class="section-head reveal">
          <div class="kicker">Preguntas</div>
          <h2>Lo que más nos preguntan.</h2>
        </div>
        <div class="faq reveal">
          <details><summary>¿Qué necesito para usar Lote?</summary><p>Un iPhone o una Mac con la versión más reciente del sistema, una cuenta de Metricool (funciona incluso con el plan gratuito) con tus redes conectadas, y una cuenta de Google Drive para que Metricool pueda descargar cada video.</p></details>
          <details><summary>¿Tengo que usar Metricool?</summary><p>No. Con el modo "Solo procesar", Lote te deja los videos listos, con su título y descripción, para que los publiques como quieras.</p></details>
          <details><summary>¿Publica algo sin que yo lo vea?</summary><p>No. Primero ves el plan completo, lo puedes simular sin mandar nada, y solo programa cuando tú lo confirmas.</p></details>
          <details><summary>¿La IA de Apple cuesta?</summary><p>No. Corre en tu propio dispositivo, sin costo extra. Si prefieres OpenAI, usas tu propia clave y pagas directo lo que uses.</p></details>
          <details><summary>¿Cómo pruebo la beta?</summary><p>Escríbenos a <a href="mailto:{{ $email }}?subject=Quiero%20probar%20Lote">{{ $email }}</a> y te mandamos la invitación de TestFlight.</p></details>
        </div>
      </div>
    </section>

    <section>
      <div class="wrap">
        <div class="cta card reveal">
          <img src="{{ asset('img/icono.png') }}" alt="">
          <h2>Tu próximo lote, listo en una tarde.</h2>
          <p>Graba cuando tengas ganas. Programa todo de una vez.</p>
          <a class="button primary" href="mailto:{{ $email }}?subject=Quiero%20probar%20Lote">Pedir acceso a la beta</a>
        </div>
      </div>
    </section>
  </main>
@endsection

@push('scripts')
<script>
    // The phone's subtitles, highlighting the word being said, like in the app.
    const blocks = [["GRABA", "UNA", "VEZ"], ["Y", "PUBLICA"], ["TODA", "LA", "SEMANA"]];
    const caption = document.getElementById('caption');
    const still = matchMedia('(prefers-reduced-motion: reduce)').matches;
    let block = 0, word = 0;
    function render() {
      caption.innerHTML = blocks[block].map((w, i) => `<span class="word${i === word ? ' on' : ''}">${w}</span>`).join(' ');
    }
    render();
    if (!still) setInterval(() => {
      word += 1;
      if (word >= blocks[block].length) { word = 0; block = (block + 1) % blocks.length; }
      render();
    }, 420);
</script>
@endpush
