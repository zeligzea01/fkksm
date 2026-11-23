<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Zelig</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 40px 20px; background: #f1f5f9; color: #1e293b; font: 16px Arial, sans-serif; }
        main { max-width: 760px; margin: auto; }
        section, footer { background: white; padding: 24px; margin-bottom: 18px; border-radius: 12px; }
        h1 { color: #2563eb; margin: 12px 0; }
        .project { background: #eff6ff; padding: 16px; border-radius: 8px; margin-top: 14px; }
        a { color: #2563eb; }
        @media (max-width: 600px) { body { padding: 20px 12px; } section, footer { padding: 18px; } }
    </style>
</head>
<body>
<main>
    <section>
        <small>PORTOFOLIO</small>
        <h1>Halo, nama saya Zelig</h1>
        <p>Saya sedang belajar membuat website profile dengan Laravel.</p>
        <a href="mailto:zelig@example.com">Hubungi saya</a>
    </section>

    <section>
        <h2>Tentang saya</h2>
        <p>Saya lahir di Kota Panjang, Bandar Lampung, dan sedang belajar di Politeknik Mitra Industri. Saya tertarik pada teknologi, welding, dan manufaktur.</p>
    </section>

    <section>
        <h2>Project saya</h2>
        @foreach ($projects as $project)
            <article class="project">
                <h3>{{ $project['name'] }}</h3>
                <p>{{ $project['description'] }}</p>
            </article>
        @endforeach
    </section>

    <footer><p>© 2026 Zelig</p></footer>
</main>
</body>
</html>