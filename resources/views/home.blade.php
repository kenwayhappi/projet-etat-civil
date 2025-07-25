@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(44, 90, 160, 0.9), rgba(102, 126, 234, 0.8)),
                    url('{{ asset("images/b.jpeg") }}') center/cover;
        border-radius: 25px;
        color: white;
        padding: 80px 0;
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        background: linear-gradient(45deg, #fff, #e3f2fd);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: glow 2s ease-in-out infinite alternate;
    }

    @keyframes glow {
        from { filter: brightness(1); }
        to { filter: brightness(1.1); }
    }

    .hero-subtitle {
        font-size: 1.3rem;
        margin-bottom: 40px;
        opacity: 0.95;
        font-weight: 300;
        line-height: 1.6;
    }

    .btn-hero {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        padding: 15px 40px;
        font-size: 1.2rem;
        font-weight: 600;
        border-radius: 50px;
        color: white;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .btn-hero:hover::before {
        left: 100%;
    }

    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
        color: white;
        text-decoration: none;
    }

    .features-section {
        margin-top: 60px;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        background: rgba(255, 255, 255, 0.95);
    }

    .feature-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: block;
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1);
        color: var(--accent-color);
    }

    .feature-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--text-dark);
    }

    .feature-description {
        color: #666;
        line-height: 1.6;
        font-size: 1rem;
    }

    .stats-section {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        padding: 50px 0;
        margin: 60px 0;
        color: white;
        text-align: center;
    }

    .stat-item {
        margin-bottom: 30px;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        display: block;
        margin-bottom: 10px;
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 300;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .btn-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
        }

        .feature-card {
            padding: 30px 20px;
        }

        .stat-number {
            font-size: 2.5rem;
        }
    }

    /* Decorative elements */
    .floating-element {
        position: absolute;
        opacity: 0.1;
        animation: float-decoration 8s ease-in-out infinite;
    }

    .floating-element:nth-child(1) {
        top: 10%;
        left: 10%;
        font-size: 2rem;
        animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
        top: 20%;
        right: 15%;
        font-size: 1.5rem;
        animation-delay: 2s;
    }

    .floating-element:nth-child(3) {
        bottom: 20%;
        left: 20%;
        font-size: 1.8rem;
        animation-delay: 4s;
    }

    @keyframes float-decoration {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(10px, -10px) rotate(90deg); }
        50% { transform: translate(-5px, -20px) rotate(180deg); }
        75% { transform: translate(-15px, -10px) rotate(270deg); }
    }
</style>

<div class="hero-section">
    <div class="floating-element"><i class="fas fa-certificate"></i></div>
    <div class="floating-element"><i class="fas fa-stamp"></i></div>
    <div class="floating-element"><i class="fas fa-scroll"></i></div>

    <div class="hero-content">
        <h1 class="hero-title">
            <i class="fas fa-university me-3"></i>
            Bureau National de l'État Civil
        </h1>
        <p class="hero-subtitle">
            Gérez efficacement tous les actes d'état civil avec notre système moderne et sécurisé.<br>
            Simplicité, rapidité et fiabilité au service des citoyens.
        </p>
        <a href="{{ route('login') }}" class="btn-hero">
            <i class="fas fa-sign-in-alt me-2"></i>
            Accéder au système
        </a>
    </div>
</div>

<div class="features-section">
    <div class="row">
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-file-alt feature-icon"></i>
                <h3 class="feature-title">Gestion des Actes</h3>
                <p class="feature-description">
                    Création, modification et consultation des actes de naissance, mariage et décès en toute simplicité.
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-shield-alt feature-icon"></i>
                <h3 class="feature-title">Sécurité Maximale</h3>
                <p class="feature-description">
                    Protection avancée des données personnelles avec chiffrement et accès contrôlé par authentification.
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-chart-line feature-icon"></i>
                <h3 class="feature-title">Rapports & Statistiques</h3>
                <p class="feature-description">
                    Générez des rapports détaillés et consultez les statistiques pour un suivi optimal des activités.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="stats-section">
    <div class="container">
        <h2 class="mb-5" style="font-weight: 700; font-size: 2.5rem;">Nos Performances</h2>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="12500">0</span>
                    <span class="stat-label">Actes traités</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="98">0</span>
                    <span class="stat-label">% de satisfaction</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="24">0</span>
                    <span class="stat-label">Heures d'activité</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="15">0</span>
                    <span class="stat-label">Années d'expérience</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter animation
    const counters = document.querySelectorAll('.stat-number');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 100;
                let current = 0;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };

                updateCounter();
                observer.unobserve(counter);
            }
        });
    });

    counters.forEach(counter => observer.observe(counter));

    // Add parallax effect to hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.style.transform = `translateY(${scrolled * 0.2}px)`;
        }
    });
});
</script>
@endsection
