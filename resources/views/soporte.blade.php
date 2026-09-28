@extends('layouts.site')

@section('title', 'Soporte — Lote')
@section('description', 'Ayuda con Lote: cómo conectar Metricool y Google Drive, qué hacer si algo falla y cómo contactarnos.')

@section('content')
<main class="doc">
    <div class="wrap">
      <span class="pill">Soporte</span>
      <h1>¿Cómo te ayudamos?</h1>
      <p class="updated">Te respondemos en menos de un día hábil.</p>

      <div class="summary card">
        <p><strong>Escríbenos:</strong></p>
        <ul>
          <li>Por WhatsApp: <a href="{{ $whatsapp }}" target="_blank" rel="noopener">abrir chat</a> (lo más rápido).</li>
          <li>Por correo: <a href="mailto:{{ $email }}?subject=Soporte%20Lote">{{ $email }}</a></li>
        </ul>
        <p>Si algo falló, cuéntanos qué estabas haciendo y, si puedes, manda una captura de pantalla. Si usas la beta, también puedes enviar comentarios desde TestFlight.</p>
      </div>

      <h2>Preguntas frecuentes</h2>

      <div class="faq">
        <details><summary>¿Qué necesito para usar Lote?</summary><p>Un iPhone o una Mac con la versión más reciente del sistema. Para programar publicaciones necesitas una cuenta de Metricool (funciona con el plan gratuito) con tus redes conectadas, y una cuenta de Google Drive. Si solo quieres los videos listos, usa el modo "Solo procesar" y no necesitas ninguna de las dos.</p></details>

        <details><summary>¿Cómo conecto Metricool?</summary><p>En Ajustes → Metricool, toca "Conectar con Metricool", inicia sesión y autoriza a Lote. Después elige tu marca. Lote usa la zona horaria de esa marca para programar.</p></details>

        <details><summary>¿Cómo conecto Google Drive y para qué sirve?</summary><p>Metricool necesita un enlace para descargar cada video, así que Lote lo sube a tu Drive. Ve a Ajustes → Conexiones y toca "Conectar Google Drive". Lote solo puede ver los archivos que él mismo sube, y los puede borrar cuando ya se publicaron. Usa la misma cuenta de Google que tienes vinculada en Metricool.</p></details>

        <details><summary>Un video dice "Falta texto con IA"</summary><p>La IA de Apple a veces tarda en estar lista (se descarga la primera vez) o se niega a escribir sobre ciertos temas. Lote lo vuelve a intentar solo. Mientras, puedes abrir el video en Revisar y escribir tú el título y la descripción, o cambiar a OpenAI en Ajustes → Textos y horarios.</p></details>

        <details><summary>Mi video no se procesa: dice que es muy largo</summary><p>Instagram, TikTok y YouTube Shorts tienen un límite de duración, así que Lote no procesa videos de más de 3 minutos en el lote. Toca el video y elige "Sacar clips de este video": la IA te propone los mejores momentos para convertirlos en videos cortos.</p></details>

        <details><summary>Se cortó el internet mientras programaba</summary><p>Lote nunca manda dos veces el mismo video. Si no pudo confirmar que Metricool lo guardó, toca "Reconfirmar en Metricool": si ya está allá, lo marca como programado; si no llegó, lo deja listo para volver a programarlo.</p></details>

        <details><summary>¿Puedo ver el plan antes de programar?</summary><p>Sí. En Calendario ves el plan completo por semana o por mes, puedes sortear otros horarios y simularlo sin mandar nada. Solo se programa cuando confirmas "Programar de verdad".</p></details>

        <details><summary>¿Cómo borro mis datos o desconecto una cuenta?</summary><p>En Ajustes → Conexiones puedes desconectar Metricool y Google Drive. También puedes quitar el acceso de Google en <a href="https://myaccount.google.com/permissions" target="_blank" rel="noopener">myaccount.google.com/permissions</a>. Al borrar la app se borran sus datos del dispositivo. Más detalles en el <a href="{{ route('privacy') }}">aviso de privacidad</a>.</p></details>

        <details><summary>¿Cómo pruebo la beta?</summary><p>Escríbenos por <a href="{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp</a> y te mandamos la invitación de TestFlight.</p></details>
      </div>
    </div>
</main>
@endsection
