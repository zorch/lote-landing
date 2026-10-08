@extends('layouts.site')

@section('title', 'Aviso de privacidad — Lote')
@section('description', 'Qué datos usa Lote, para qué, y qué pasa con tus videos, tu Google Drive y tu cuenta de Metricool.')

@section('content')
<main class="doc">
    <div class="wrap">
      <span class="pill green">Privacidad</span>
      <h1>Aviso de privacidad</h1>
      <p class="updated">Última actualización: {{ config('landing.legal_updated') }}</p>

      <div class="summary card">
        <p><strong>En corto:</strong></p>
        <ul>
          <li>No recibimos ni guardamos tus videos, tu audio, tus cuentas ni tus datos de pago.</li>
          <li>El recorte, la transcripción y los subtítulos se hacen en tu iPhone, iPad o Mac.</li>
          <li>Tres funciones opcionales pasan por nuestro servidor (applote.com): la IA de Lote, los videos de apoyo y la biblioteca de música. No guardan nada tuyo; abajo decimos exactamente qué envían.</li>
          <li>Lote Pro se paga a través de App Store o Google Play. Nunca vemos tu tarjeta.</li>
          <li>En Google Drive, Lote solo puede ver y manejar los archivos que él mismo sube.</li>
          <li>Tus conexiones se guardan en el llavero (Keychain) de tu dispositivo.</li>
          <li>No vendemos datos, no mostramos anuncios y no usamos rastreadores.</li>
        </ul>
      </div>

      <h2>1. Quiénes somos</h2>
      <p>Lote es una app para iPhone, iPad y Mac que prepara y programa videos cortos en redes sociales. En este aviso, "Lote", "nosotros" y "la app" se refieren a esa app y a quien la desarrolla. Para cualquier duda sobre tus datos, escríbenos a <a href="mailto:{{ $email }}">{{ $email }}</a>.</p>

      <h2>2. Lo que se queda en tu dispositivo</h2>
      <p>Para hacer su trabajo, Lote guarda en tu iPhone, iPad o Mac, y solo ahí:</p>
      <ul>
        <li><strong>Tus videos:</strong> los que eliges, sus versiones con subtítulos y los clips que cortas. En la Mac, tus originales nunca se modifican; Lote trabaja con copias en su propia carpeta.</li>
        <li><strong>La transcripción</strong> de cada video, sus títulos, descripciones y categorías, y el plan de publicación.</li>
        <li><strong>Tus ajustes:</strong> estilo de subtítulos, horarios, reglas, correcciones de palabras y demás preferencias.</li>
        <li><strong>Tus conexiones</strong> con Metricool, Google y, si la usas, tu clave de OpenAI, guardadas en el llavero seguro de Apple (Keychain).</li>
        <li><strong>Tu suscripción:</strong> el comprobante firmado de la tienda que indica si tienes Lote Pro, y la cuenta de videos gratis que ya usaste, también en el llavero.</li>
      </ul>
      <p>La transcripción usa un modelo de voz que corre en tu dispositivo. La primera vez, Lote descarga ese modelo de internet; la descarga no incluye ni envía ningún dato tuyo.</p>
      <p>Si eliges la IA de Apple para escribir los textos, esto también pasa en tu dispositivo con Apple Intelligence.</p>
      <p>Cuando un video ya se publicó, Lote puede borrar sus copias del dispositivo para liberar espacio. Puedes apagar esa opción en Ajustes → Almacenamiento.</p>

      <h2>3. Lo que sale de tu dispositivo, y solo si tú lo usas</h2>
      <p>Lote solo se conecta con los servicios que tú conectas, y solo para lo que se describe aquí.</p>

      <p><strong>Google Drive.</strong> Metricool necesita un enlace público para descargar cada video, así que Lote lo sube a tu propio Google Drive:</p>
      <ul>
        <li>Usa únicamente el permiso <code>drive.file</code>: Lote solo puede ver, crear y borrar los archivos que él mismo subió. No puede ver ni tocar el resto de tu Drive.</li>
        <li>Sube el video terminado y lo comparte como "cualquier persona con el enlace" para que Metricool lo pueda descargar.</li>
        <li>Lee el correo de tu cuenta de Google solo para mostrarte cuál conectaste.</li>
        <li>Si tienes activada la limpieza, Lote borra el video de tu Drive después de que se publicó.</li>
      </ul>

      <p><strong>Metricool.</strong> Te conectas con tu cuenta de Metricool (con los permisos de leer y programar publicaciones) para que Lote:</p>
      <ul>
        <li>Lea tus marcas y su zona horaria.</li>
        <li>Lea lo que ya tienes programado, para planear alrededor de eso y no repetir un video que ya está.</li>
        <li>Cree las publicaciones que tú confirmas: el título, la descripción, el enlace al video, la fecha y las redes elegidas.</li>
      </ul>
      <p>Nada se programa sin que tú lo confirmes en la app. Lo que envías a Metricool queda sujeto a su propio aviso de privacidad.</p>

      <p><strong>OpenAI (opcional).</strong> Si eliges OpenAI en lugar de la IA de Apple, Lote envía a OpenAI, con tu propia clave, el texto de la transcripción y tus instrucciones para escribir el título y la descripción, o para proponer clips. No envía el video ni el audio. Ese uso queda sujeto a las políticas de OpenAI.</p>

      <p><strong>IA de Lote (parte de Lote Pro).</strong> Si eliges la IA de Lote, la app envía a nuestro servidor (applote.com) el texto de la transcripción y las instrucciones para escribir el título y la descripción, proponer clips, elegir el ánimo de la música o describir escenas para los videos de apoyo. El servidor lo reenvía a OpenAI con nuestra propia clave y te regresa la respuesta. Junto con la petición va el comprobante firmado de tu suscripción, que es lo que demuestra que tienes Lote Pro; ese comprobante lo emite Apple o Google y no contiene tu nombre, tu correo ni tu tarjeta. Nuestro servidor no guarda ni la transcripción, ni la respuesta, ni el comprobante: solo conserva durante una hora el resultado de verificar el comprobante, para no verificarlo en cada petición. No envía el video ni el audio. Lo que llega a OpenAI queda sujeto a sus políticas.</p>

      <p><strong>Videos de apoyo (Pixabay).</strong> Si activas los videos de apoyo, la app envía a nuestro servidor las palabras de búsqueda de cada escena (por ejemplo, "playa al atardecer") y el idioma, y el servidor busca los clips en Pixabay. Las búsquedas se guardan en caché 24 horas sin ligarlas a ti, como piden las condiciones de Pixabay. Los clips se descargan de Pixabay y se guardan en tu dispositivo.</p>

      <p><strong>Música y sonidos.</strong> Las pistas y efectos que Lote agrega a tus videos se descargan de applote.com la primera vez que se usan y se guardan en tu dispositivo. Esa descarga no lleva ningún dato tuyo.</p>

      <p>Como cualquier sitio web, nuestro servidor registra de forma temporal la dirección IP de cada petición para seguridad y para limitar el número de peticiones por dispositivo. No la ligamos a tu identidad ni la usamos para otra cosa.</p>

      <p><strong>Fotos.</strong> Lote solo abre los videos que eliges. Si le das "Guardar en Fotos", pide permiso únicamente para agregar videos a tu galería, no para ver la que ya tienes.</p>

      <h2>4. Uso de los datos de Google</h2>
      <p>El uso que Lote hace de la información recibida de las APIs de Google, y su transferencia a cualquier otra app, se apega a la <a href="https://developers.google.com/terms/api-services-user-data-policy" target="_blank" rel="noopener">Política de datos de usuario de los servicios de API de Google</a>, incluidos los requisitos de uso limitado.</p>
      <p>En particular, Lote solo usa el acceso a Google Drive para subir, compartir y borrar los videos que tú le pides publicar. Nunca usa esos datos para anuncios, no los vende y no los usa para entrenar modelos de IA. Nadie los ve, salvo que tú nos lo pidas para ayudarte con un problema o que la ley lo exija.</p>

      <h2>5. Compras y suscripción (Lote Pro)</h2>
      <ul>
        <li>Lote Pro se compra y se cobra a través de la tienda de tu dispositivo: App Store (Apple) o Google Play (Google). Ellos procesan el pago bajo sus propios avisos de privacidad. Nosotros no recibimos tu nombre, tu dirección ni los datos de tu tarjeta.</li>
        <li>La tienda le da a la app un comprobante firmado de la suscripción. Lote lo guarda en tu dispositivo para saber si tienes Pro y lo envía a nuestro servidor únicamente cuando usas la IA de Lote, como se describe arriba. Ese comprobante no te identifica personalmente.</li>
        <li>Apple y Google nos dan reportes de ventas y de suscripciones en conjunto, sin datos personales de los compradores.</li>
        <li>La cuenta de videos gratis que ya usaste se guarda en el llavero de tu dispositivo, para que no se reinicie al borrar y volver a instalar la app. No se envía a ningún lado.</li>
      </ul>

      <h2>6. Lo que no hacemos</h2>
      <ul>
        <li>Nuestros servidores nunca reciben tus videos, tu audio, tus cuentas ni tus datos de pago. Lo único que pasa por ellos es lo descrito en la sección 3, y no se guarda.</li>
        <li>No vendemos ni compartimos tus datos con fines de publicidad.</li>
        <li>No usamos herramientas de analítica ni de rastreo dentro de la app.</li>
      </ul>
      <p>Si pruebas la beta con TestFlight, Apple puede compartirnos los reportes de fallas y los comentarios que tú decidas enviar, según las condiciones de TestFlight.</p>

      <h2>7. Cómo borrar tus datos o desconectar</h2>
      <ul>
        <li><strong>Desconectar un servicio:</strong> en la app, en Ajustes → Conexiones. Eso borra su acceso de tu dispositivo.</li>
        <li><strong>Quitar el acceso de Google:</strong> también puedes quitarlo en <a href="https://myaccount.google.com/permissions" target="_blank" rel="noopener">myaccount.google.com/permissions</a>.</li>
        <li><strong>Borrar todo:</strong> al borrar la app, se borran sus datos del dispositivo. En la Mac, la carpeta Películas → Lote se queda hasta que la borres tú.</li>
        <li><strong>Lo que ya está en Metricool o en tus redes</strong> se maneja desde esos servicios.</li>
        <li><strong>Cancelar Lote Pro:</strong> desde la tienda, en Ajustes → tu nombre → Suscripciones (Apple) o en Google Play → Suscripciones. Borrar la app no cancela la suscripción.</li>
      </ul>

      <h2>8. Menores de edad</h2>
      <p>Lote no está dirigida a menores de 13 años y no recopila a sabiendas datos de ellos.</p>

      <h2>9. Cambios a este aviso</h2>
      <p>Si cambia lo que hace Lote con tus datos, actualizaremos este aviso y la fecha de arriba. Si el cambio es importante, te lo diremos en la app.</p>

      <h2>10. Contacto</h2>
      <p>Para cualquier pregunta, o para ejercer tus derechos de acceso, rectificación, cancelación u oposición (derechos ARCO), escríbenos a <a href="mailto:{{ $email }}">{{ $email }}</a>.</p>
    </div>
  </main>
@endsection
