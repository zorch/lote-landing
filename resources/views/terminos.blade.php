@extends('layouts.site')

@section('title', 'Términos de uso — Lote')
@section('description', 'Las condiciones para usar Lote.')

@section('content')
<main class="doc">
    <div class="wrap">
      <span class="pill">Términos</span>
      <h1>Términos de uso</h1>
      <p class="updated">Última actualización: {{ config('landing.legal_updated') }}</p>

      <p>Al usar Lote aceptas estas condiciones. Si no estás de acuerdo con ellas, no uses la app.</p>

      <h2>1. Qué es Lote</h2>
      <p>Lote es una app para iPhone y Mac que prepara tus videos (recorte, transcripción, subtítulos y textos) y, si tú lo decides, los programa en tus redes a través de tu cuenta de Metricool. El trabajo se hace en tu dispositivo, y Lote se conecta solo con los servicios que tú conectas.</p>

      <h2>2. Beta</h2>
      <p>Mientras Lote esté en beta, puede tener errores, cambiar o dejar de estar disponible. Revisa siempre el plan y los textos antes de programar, y usa la simulación cuando tengas dudas.</p>

      <h2>3. Tu contenido y tus cuentas</h2>
      <ul>
        <li>Tus videos y textos son tuyos. Lote no reclama ningún derecho sobre ellos.</li>
        <li>Eres responsable de lo que publicas y de tener los derechos sobre ello, incluida la música y las personas que aparecen.</li>
        <li>Eres responsable de tus cuentas de Metricool, Google, OpenAI y de tus redes sociales, y de cumplir sus términos y límites.</li>
        <li>Los títulos, descripciones y clips que propone la IA pueden tener errores. Tú decides qué se publica.</li>
      </ul>

      <h2>4. Servicios de terceros</h2>
      <p>Lote funciona con servicios de otras empresas: Metricool, Google Drive, OpenAI, Instagram, TikTok, YouTube y Apple. Lote no está afiliado a ninguno de ellos ni es responsable de su funcionamiento, de sus cambios o de sus decisiones sobre tus publicaciones. Su uso se rige por sus propios términos.</p>

      <h2>5. Uso aceptable</h2>
      <p>No uses Lote para publicar contenido ilegal, para hacer spam o para intentar romper la app o los servicios con los que se conecta.</p>

      <h2>6. Sin garantías</h2>
      <p>Lote se ofrece "tal cual". Hacemos lo posible para que funcione bien, incluidas protecciones para no duplicar publicaciones, pero no garantizamos que esté libre de errores ni que cada publicación salga a la hora exacta, ya que eso depende también de Metricool y de cada red.</p>

      <h2>7. Responsabilidad</h2>
      <p>En la medida en que la ley lo permita, no somos responsables de daños indirectos, de pérdidas de datos o de ingresos, ni de publicaciones hechas, no hechas o hechas a otra hora por el uso de la app.</p>

      <h2>8. Cambios</h2>
      <p>Podemos actualizar estos términos. Cuando lo hagamos, cambiaremos la fecha de arriba; si el cambio es importante, te avisaremos en la app.</p>

      <h2>9. Ley aplicable</h2>
      <p>Estos términos se rigen por las leyes de los Estados Unidos Mexicanos.</p>

      <h2>10. Contacto</h2>
      <p>Escríbenos a <a href="mailto:{{ $email }}">{{ $email }}</a>.</p>
    </div>
  </main>
@endsection
