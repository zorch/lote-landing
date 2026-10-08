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
      <p>Lote es una app para iPhone, iPad y Mac que prepara tus videos (recorte, transcripción, subtítulos y textos) y, si tú lo decides, los programa en tus redes a través de tu cuenta de Metricool. El trabajo se hace en tu dispositivo, y Lote se conecta solo con los servicios que tú conectas.</p>

      <h2>2. Errores y cambios</h2>
      <p>Lote se actualiza seguido y puede tener errores, cambiar o dejar de estar disponible. Revisa siempre el plan y los textos antes de programar, y usa la simulación cuando tengas dudas.</p>

      <h2>3. Videos gratis y Lote Pro</h2>
      <ul>
        <li><strong>Gratis:</strong> los primeros 10 videos se procesan sin costo y sin suscripción. Podemos cambiar cuántos videos incluye la parte gratuita en versiones futuras; los que ya procesaste no se pierden.</li>
        <li><strong>Lote Pro</strong> es una suscripción que quita el límite de videos y de clips e incluye la IA de Lote. Se ofrece en plan mensual y anual. El precio y la duración de cada plan se muestran en la app antes de comprar, en la moneda de tu tienda.</li>
        <li><strong>Dónde se compra y se cobra:</strong> la compra se hace a través de la tienda de tu dispositivo (App Store de Apple o Google Play), que es quien procesa el pago. Nosotros no recibimos ni guardamos tus datos de pago.</li>
        <li><strong>Renovación automática:</strong> la suscripción se renueva sola al mismo precio al final de cada periodo (mes o año) hasta que la canceles. El cobro de la renovación se hace a tu cuenta de la tienda en las 24 horas anteriores al fin del periodo.</li>
        <li><strong>Prueba gratis:</strong> si tu plan incluye un periodo de prueba, al terminar se cobra el primer periodo, salvo que canceles antes de que termine la prueba. La prueba se ofrece una vez por cuenta.</li>
        <li><strong>Cancelar:</strong> cuando quieras, desde la tienda: en iPhone, iPad o Mac en Ajustes → tu nombre → Suscripciones; en Android en Google Play → Suscripciones. Borrar la app no cancela la suscripción. Al cancelar, Lote Pro sigue activo hasta el final del periodo que ya pagaste y no se vuelve a cobrar.</li>
        <li><strong>Reembolsos:</strong> los maneja la tienda según sus propias políticas (Apple en <a href="https://reportaproblem.apple.com" target="_blank" rel="noopener">reportaproblem.apple.com</a>, Google en Google Play). No hacemos reembolsos parciales por periodos sin usar.</li>
        <li><strong>En todos tus dispositivos:</strong> una suscripción comprada con un Apple ID funciona en iPhone, iPad y Mac con ese mismo Apple ID; usa "Restaurar compras" en la app si no aparece. Una compra en Google Play funciona en tus dispositivos Android con esa cuenta de Google. Las compras no se transfieren entre tiendas.</li>
        <li><strong>Cambios de precio:</strong> si cambia el precio de Lote Pro, la tienda te avisará con anticipación y, cuando la ley o la tienda lo requieran, te pedirá aceptarlo antes de cobrarlo.</li>
        <li><strong>Uso razonable:</strong> la IA de Lote es para preparar tus propios videos. Podemos limitar temporalmente el uso que sea claramente automatizado o abusivo, para que el servicio siga disponible para todos.</li>
      </ul>

      <h2>4. Tu contenido y tus cuentas</h2>
      <ul>
        <li>Tus videos y textos son tuyos. Lote no reclama ningún derecho sobre ellos.</li>
        <li>Eres responsable de lo que publicas y de tener los derechos sobre ello, incluida la música y las personas que aparecen.</li>
        <li>Eres responsable de tus cuentas de Metricool, Google, OpenAI, de tu tienda de apps y de tus redes sociales, y de cumplir sus términos y límites.</li>
        <li>Los títulos, descripciones y clips que propone la IA pueden tener errores. Tú decides qué se publica.</li>
      </ul>

      <h2>5. Servicios de terceros</h2>
      <p>Lote funciona con servicios de otras empresas: Metricool, Google Drive, OpenAI, Pixabay, Instagram, TikTok, YouTube, Apple y Google. Lote no está afiliado a ninguno de ellos ni es responsable de su funcionamiento, de sus cambios o de sus decisiones sobre tus publicaciones. Su uso se rige por sus propios términos.</p>

      <h2>6. Uso aceptable</h2>
      <p>No uses Lote para publicar contenido ilegal, para hacer spam o para intentar romper la app o los servicios con los que se conecta.</p>

      <h2>7. Sin garantías</h2>
      <p>Lote se ofrece "tal cual". Hacemos lo posible para que funcione bien, incluidas protecciones para no duplicar publicaciones, pero no garantizamos que esté libre de errores ni que cada publicación salga a la hora exacta, ya que eso depende también de Metricool y de cada red.</p>

      <h2>8. Responsabilidad</h2>
      <p>En la medida en que la ley lo permita, no somos responsables de daños indirectos, de pérdidas de datos o de ingresos, ni de publicaciones hechas, no hechas o hechas a otra hora por el uso de la app.</p>

      <h2>9. Cambios</h2>
      <p>Podemos actualizar estos términos. Cuando lo hagamos, cambiaremos la fecha de arriba; si el cambio es importante, te avisaremos en la app.</p>

      <h2>10. Ley aplicable</h2>
      <p>Estos términos se rigen por las leyes de los Estados Unidos Mexicanos.</p>

      <h2>11. Contacto</h2>
      <p>Escríbenos a <a href="mailto:{{ $email }}">{{ $email }}</a>.</p>
    </div>
  </main>
@endsection
