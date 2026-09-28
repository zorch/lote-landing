<?php

return [
    'name' => 'Lote',
    'tagline' => 'Graba tus videos. Lote hace lo demás.',
    'description' => 'Lote toma los videos verticales que grabaste, les quita el silencio, les pone subtítulos, escribe su título y descripción, y los programa en Instagram, TikTok y YouTube con Metricool. Todo en tu iPhone o tu Mac.',

    // Shown on every page and used for "Pedir acceso".
    'email' => env('LANDING_EMAIL', 'contacto@applote.com'),

    // "Quiero probar Lote" / "Pedir acceso" open a WhatsApp chat with this
    // number (country code included, digits only) and a message ready to send.
    'whatsapp' => env('LANDING_WHATSAPP', '528117425048'),
    'whatsapp_message' => 'Hola, quiero probar Lote.',

    // Date shown on the privacy notice and the terms.
    'legal_updated' => '28 de septiembre de 2026',
];
