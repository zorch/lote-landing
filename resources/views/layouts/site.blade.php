<!doctype html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F6F4EF">

    <title>@yield('title', config('landing.name').' — '.config('landing.tagline'))</title>
    <meta name="description" content="@yield('description', config('landing.description'))">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('landing.name') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('landing.name').' — '.config('landing.tagline'))">
    <meta property="og:description" content="@yield('description', config('landing.description'))">
    <meta property="og:image" content="{{ asset('img/icono.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/icono.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Figtree:wght@400;500;600;700;800&family=Poppins:wght@900&display=swap" rel="stylesheet">
    {{-- Versioned by modification time so a deploy never serves stale styles. --}}
    <link rel="stylesheet" href="{{ asset('css/lote.css') }}?v={{ filemtime(public_path('css/lote.css')) }}">
</head>
<body>
    <header class="top">
        <div class="wrap">
            <a class="brand" href="{{ route('home') }}"><img src="{{ asset('img/icono.png') }}" alt=""><span>Lote</span></a>
            <nav>
                @section('nav')
                    <a href="{{ route('home') }}">Inicio</a>
                    <a href="{{ route('support') }}">Soporte</a>
                    <a href="{{ route('privacy') }}">Privacidad</a>
                    <a href="{{ route('terms') }}">Términos</a>
                @show
            </nav>
        </div>
    </header>

    @yield('content')

    <footer>
        <div class="wrap">
            <span>© {{ date('Y') }} Lote</span>
            <nav>
                <a href="{{ route('support') }}">Soporte</a>
                <a href="{{ route('privacy') }}">Privacidad</a>
                <a href="{{ route('terms') }}">Términos</a>
                <a href="mailto:{{ $email }}">{{ $email }}</a>
            </nav>
            <p class="legal">Instagram, TikTok, YouTube, Metricool, Google Drive, OpenAI y Apple son marcas de sus respectivos dueños. Lote no está afiliado ni patrocinado por ninguno de ellos.</p>
        </div>
    </footer>

    <script>
        // Header border once the page scrolls.
        const header = document.querySelector('.top');
        addEventListener('scroll', () => header.classList.toggle('scrolled', scrollY > 8), { passive: true });

        // Fade sections in as they appear.
        const observer = new IntersectionObserver(entries => {
            for (const entry of entries) if (entry.isIntersecting) { entry.target.classList.add('in'); observer.unobserve(entry.target); }
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
    @stack('scripts')
</body>
</html>
