<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Planner - Stay Focused</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --indigo: #6366f1;
            --violet: #8b5cf6;
            --indigo-soft: #eef2ff;
            --text-dark: #1e1b4b;
            --text-soft: #6b7280;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; background: #f8f7ff; overflow-x: hidden; }

        .mesh {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(99,102,241,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(139,92,246,0.1) 0%, transparent 55%),
                #f8f7ff;
            animation: meshShift 12s ease-in-out infinite alternate;
        }
        @keyframes meshShift { 0% { filter: hue-rotate(0deg); } 100% { filter: hue-rotate(20deg); } }

        .orb { position: fixed; border-radius: 50%; filter: blur(80px); opacity: 0.3; animation: float 8s ease-in-out infinite; pointer-events: none; z-index: 0; }
        .orb-1 { width: 500px; height: 500px; background: #a5b4fc; top: -100px; left: -100px; }
        .orb-2 { width: 400px; height: 400px; background: #c4b5fd; bottom: 10%; right: -80px; animation-delay: -3s; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-30px); } }

        .page { position: relative; z-index: 1; }

        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 2rem;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.75);
            border-bottom: 1px solid rgba(99,102,241,0.12);
            position: sticky; top: 0; z-index: 100;
        }
        .nav-brand { display: flex; align-items: center; gap: 0.6rem; }
        .nav-icon { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, var(--indigo), var(--violet)); display: flex; align-items: center; justify-content: center; }
        .nav-icon svg { width: 18px; height: 18px; stroke: white; }
        .brand-text { font-size: 1.2rem; font-weight: 800; color: var(--text-dark); }
        .nav-links { display: flex; gap: 0.75rem; }
        .btn-outline { padding: 0.5rem 1.25rem; border-radius: 10px; border: 1.5px solid rgba(99,102,241,0.3); color: var(--indigo); font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: all 0.2s; }
        .btn-outline:hover { background: var(--indigo-soft); }
        .btn-primary { padding: 0.5rem 1.25rem; border-radius: 10px; background: linear-gradient(135deg, var(--indigo), var(--violet)); color: white; font-weight: 600; font-size: 0.875rem; text-decoration: none; box-shadow: 0 4px 14px rgba(99,102,241,0.35); transition: all 0.2s; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

        .hero { max-width: 860px; margin: 0 auto; padding: 6rem 2rem 4rem; text-align: center; }
        .badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; border-radius: 999px; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.25); color: var(--indigo); font-size: 0.875rem; font-weight: 600; margin-bottom: 2rem; animation: fadeDown 0.6s ease both; }
        .hero h1 { font-size: clamp(2.8rem, 6vw, 4.5rem); font-weight: 800; color: var(--text-dark); line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 1.5rem; animation: fadeUp 0.7s ease 0.1s both; }
        .gradient-text { background: linear-gradient(135deg, var(--indigo), var(--violet)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 1.2rem; color: var(--text-soft); max-width: 560px; margin: 0 auto 2.5rem; line-height: 1.7; animation: fadeUp 0.7s ease 0.2s both; }
        .hero-cta { animation: fadeUp 0.7s ease 0.3s both; }
        .btn-hero { display: inline-block; padding: 1rem 2.5rem; border-radius: 14px; background: linear-gradient(135deg, var(--indigo), var(--violet)); color: white; font-weight: 700; font-size: 1.1rem; text-decoration: none; box-shadow: 0 8px 30px rgba(99,102,241,0.4); transition: all 0.25s; }
        .btn-hero:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(99,102,241,0.45); }

        .stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; max-width: 700px; margin: 0 auto 5rem; padding: 0 2rem; animation: fadeUp 0.7s ease 0.4s both; }
        .stat-card { background: rgba(255,255,255,0.8); border: 1px solid rgba(99,102,241,0.15); border-radius: 16px; padding: 1.5rem 1rem; text-align: center; backdrop-filter: blur(10px); transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(99,102,241,0.12); }
        .stat-icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .stat-title { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; margin-bottom: 0.25rem; }
        .stat-sub { font-size: 0.8rem; color: var(--text-soft); }

        .section { padding: 5rem 2rem; background: white; }
        .section-inner { max-width: 900px; margin: 0 auto; }
        .section-title { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); text-align: center; letter-spacing: -0.02em; margin-bottom: 0.75rem; }
        .section-sub { text-align: center; color: var(--text-soft); margin-bottom: 3rem; }
        .tips-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 1.25rem; }
        @media(max-width:640px) { .tips-grid,.stats { grid-template-columns: 1fr; } }
        .tip-card { padding: 1.75rem; border-radius: 18px; background: var(--indigo-soft); border: 1px solid rgba(99,102,241,0.12); transition: all 0.25s; position: relative; overflow: hidden; }
        .tip-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: linear-gradient(to bottom, var(--indigo), var(--violet)); opacity: 0; transition: opacity 0.25s; }
        .tip-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(99,102,241,0.12); }
        .tip-card:hover::before { opacity: 1; }
        .tip-emoji { font-size: 2rem; margin-bottom: 0.75rem; display: block; }
        .tip-title { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem; }
        .tip-text { font-size: 0.9rem; color: var(--text-soft); line-height: 1.6; }

        .youtube-section { padding: 5rem 2rem; background: var(--indigo-soft); }
        .yt-card { max-width: 700px; margin: 0 auto; background: white; border-radius: 20px; border: 1px solid rgba(99,102,241,0.15); padding: 2rem; display: flex; gap: 1.5rem; align-items: center; box-shadow: 0 10px 40px rgba(99,102,241,0.08); }
        .yt-emoji { font-size: 3.5rem; flex-shrink: 0; }
        .yt-title { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); margin-bottom: 0.25rem; }
        .yt-tag { color: var(--indigo); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.75rem; }
        .yt-text { font-size: 0.9rem; color: var(--text-soft); line-height: 1.6; margin-bottom: 1rem; }
        .btn-yt { display: inline-block; padding: 0.6rem 1.25rem; border-radius: 10px; background: #ef4444; color: white; font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: all 0.2s; }
        .btn-yt:hover { opacity: 0.9; transform: translateY(-1px); }

        .cta-section { padding: 5rem 2rem; text-align: center; }
        .cta-box { max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 24px; padding: 3.5rem 2rem; box-shadow: 0 20px 60px rgba(99,102,241,0.35); position: relative; overflow: hidden; }
        .cta-box::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 30% 30%, rgba(255,255,255,0.15), transparent 60%); }
        .cta-title { font-size: 2rem; font-weight: 800; color: white; margin-bottom: 0.75rem; position: relative; }
        .cta-sub { color: rgba(255,255,255,0.75); margin-bottom: 2rem; position: relative; }
        .cta-btns { display: flex; justify-content: center; gap: 1rem; position: relative; }
        .btn-cta-white { padding: 0.85rem 2rem; border-radius: 12px; background: white; color: var(--indigo); font-weight: 700; text-decoration: none; transition: all 0.2s; }
        .btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .btn-cta-outline { padding: 0.85rem 2rem; border-radius: 12px; border: 1.5px solid rgba(255,255,255,0.5); color: white; font-weight: 700; text-decoration: none; transition: all 0.2s; }
        .btn-cta-outline:hover { background: rgba(255,255,255,0.1); transform: translateY(-2px); }

        footer { padding: 1.5rem 2rem; text-align: center; border-top: 1px solid rgba(99,102,241,0.1); color: #9ca3af; font-size: 0.85rem; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<div class="mesh"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="page">

    <nav>
        <div class="nav-brand">
            <div class="nav-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="brand-text">Task Planner</span>
        </div>
        <div class="nav-links">
            @auth
                <a href="{{ route('tasks.index') }}" class="btn-primary">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-outline">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Register</a>
                @endif
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="badge">✨ Simple. Focused. Effective.</div>
        <h1>One task at a time.<br><span class="gradient-text">That's the Secret.</span></h1>
        <p>In a world full of distractions, Task Planner helps you slow down, focus on what matters, and achieve your goals — step by step.</p>
        @guest
        <div class="hero-cta">
            <a href="{{ route('register') }}" class="btn-hero">Get Started — It's Free</a>
        </div>
        @endguest
    </section>

    <div class="stats reveal">
        <div class="stat-card"><div class="stat-icon">🎯</div><div class="stat-title">Stay Focused</div><div class="stat-sub">One task at a time</div></div>
        <div class="stat-card"><div class="stat-icon">📅</div><div class="stat-title">Track Deadlines</div><div class="stat-sub">Never miss a date</div></div>
        <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-title">Feel Accomplished</div><div class="stat-sub">Mark tasks done</div></div>
    </div>

    <section class="section">
        <div class="section-inner">
            <h2 class="section-title reveal">How to actually get things done</h2>
            <p class="section-sub reveal">Big tasks feel overwhelming. Here's how to break them down.</p>
            <div class="tips-grid">
                <div class="tip-card reveal"><span class="tip-emoji">🎯</span><div class="tip-title">Break big tasks into small steps</div><div class="tip-text">Instead of "Build a website", write "Create homepage layout". Small steps feel achievable and keep you moving forward.</div></div>
                <div class="tip-card reveal"><span class="tip-emoji">⏱️</span><div class="tip-title">Focus on one task at a time</div><div class="tip-text">Multitasking is a myth. Research shows switching tasks reduces productivity by 40%. Pick one task, finish it, move on.</div></div>
                <div class="tip-card reveal"><span class="tip-emoji">✅</span><div class="tip-title">Mark tasks as completed</div><div class="tip-text">Every completed task releases dopamine — the reward chemical. Use this to build momentum throughout your day.</div></div>
                <div class="tip-card reveal"><span class="tip-emoji">📅</span><div class="tip-title">Set a due date for everything</div><div class="tip-text">Tasks without deadlines stay on your list forever. A soft deadline creates urgency that helps you actually start.</div></div>
            </div>
        </div>
    </section>

    <section class="youtube-section">
        <div class="section-inner">
            <h2 class="section-title reveal">Want to go deeper?</h2>
            <p class="section-sub reveal">Watch this creator for inspiration on focus and building better habits.</p>
            <div class="yt-card reveal">
                <div class="yt-emoji">🎬</div>
                <div>
                    <div class="yt-title">Matt D'Avella</div>
                    <div class="yt-tag">Minimalism · Focus · Habits · Productivity</div>
                    <div class="yt-text">Matt creates cinematic films about minimalism and slow living. His videos will make you rethink how you work and where you focus your energy.</div>
                    <a href="https://www.youtube.com/@mattdavella" target="_blank" class="btn-yt">▶ Visit Channel on YouTube</a>
                </div>
            </div>
        </div>
    </section>

    @guest
    <section class="cta-section">
        <div class="cta-box reveal">
            <div class="cta-title">Ready to Focus?</div>
            <div class="cta-sub">Create your free account and start organising your tasks today.</div>
            <div class="cta-btns">
                <a href="{{ route('register') }}" class="btn-cta-white">Create Account</a>
                <a href="{{ route('login') }}" class="btn-cta-outline">Log In</a>
            </div>
        </div>
    </section>
    @endguest

    <footer>© {{ date('Y') }} Task Planner · DkIT Server-Side Development CA2 · Maryna Hordiienko & Aleksy Cieslak</footer>

</div>

<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 80);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>