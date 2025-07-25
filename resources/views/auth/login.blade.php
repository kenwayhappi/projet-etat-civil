@extends('layouts.app')

@section('content')
<style>
    .login-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 3rem;
        width: 100%;
        max-width: 450px;
        position: relative;
        overflow: hidden;
        animation: slideInUp 0.8s ease-out;
        transition: all 0.3s ease;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(44, 90, 160, 0.1), transparent);
        transition: left 0.5s;
    }

    .login-card:hover::before {
        left: 100%;
    }

    .login-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: none !important;
        border: none !important;
        text-align: center;
        padding-bottom: 2rem;
        position: relative;
    }

    .card-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        animation: fadeInDown 1s ease-out 0.3s both;
    }

    .card-subtitle {
        color: #6c757d;
        font-size: 1rem;
        font-weight: 400;
        animation: fadeInDown 1s ease-out 0.5s both;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1.5rem;
        animation: fadeInLeft 0.8s ease-out both;
    }

    .form-floating:nth-child(2) {
        animation-delay: 0.4s;
    }

    .form-floating:nth-child(3) {
        animation-delay: 0.6s;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 1rem 1.2rem;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(44, 90, 160, 0.25), 0 8px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.95);
    }

    .form-label {
        color: var(--primary-color);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: var(--primary-color);
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .btn-login {
        background: linear-gradient(135deg, var(--primary-color), #4a90e2);
        border: none;
        border-radius: 15px;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        width: 100%;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(44, 90, 160, 0.3);
        animation: fadeInUp 0.8s ease-out 0.8s both;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(44, 90, 160, 0.4);
        background: linear-gradient(135deg, #4a90e2, var(--primary-color));
    }

    .btn-login:active {
        transform: translateY(-1px);
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        padding: 0.5rem;
        background: rgba(220, 53, 69, 0.1);
        border-radius: 8px;
        border-left: 4px solid #dc3545;
        animation: shake 0.5s ease-in-out;
    }

    .login-icon {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), #4a90e2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 10px 30px rgba(44, 90, 160, 0.3);
        animation: bounceIn 1s ease-out;
        border: 4px solid white;
    }

    .floating-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
    }

    .floating-element {
        position: absolute;
        background: rgba(44, 90, 160, 0.1);
        border-radius: 50%;
        animation: floatAround 8s infinite linear;
    }

    .floating-element:nth-child(1) {
        width: 60px;
        height: 60px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
        width: 40px;
        height: 40px;
        top: 20%;
        right: 15%;
        animation-delay: 2s;
    }

    .floating-element:nth-child(3) {
        width: 80px;
        height: 80px;
        bottom: 15%;
        left: 20%;
        animation-delay: 4s;
    }

    .floating-element:nth-child(4) {
        width: 50px;
        height: 50px;
        bottom: 30%;
        right: 10%;
        animation-delay: 6s;
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            transform: translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes fadeInDown {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes fadeInLeft {
        from {
            transform: translateX(-50px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes bounceIn {
        0% {
            transform: translateX(-50%) scale(0);
            opacity: 0;
        }
        50% {
            transform: translateX(-50%) scale(1.1);
            opacity: 1;
        }
        100% {
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    @keyframes floatAround {
        0% {
            transform: translateY(0px) rotate(0deg);
            opacity: 0.7;
        }
        33% {
            transform: translateY(-30px) rotate(120deg);
            opacity: 1;
        }
        66% {
            transform: translateY(30px) rotate(240deg);
            opacity: 0.7;
        }
        100% {
            transform: translateY(0px) rotate(360deg);
            opacity: 0.7;
        }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .btn-login:focus {
        animation: pulse 0.5s ease-in-out;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .login-card {
            margin: 1rem;
            padding: 2rem;
        }

        .card-title {
            font-size: 1.8rem;
        }

        .login-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            top: -25px;
        }
    }

    /* Loading state */
    .btn-login.loading {
        position: relative;
        color: transparent;
    }

    .btn-login.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="login-container">
    <!-- Floating decorative elements -->
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <div class="login-card">
        <!-- Login icon -->
        <div class="login-icon">
            <i class="fas fa-user-shield"></i>
        </div>

        <div class="card-header">
            <h2 class="card-title">Connexion</h2>
            <p class="card-subtitle">Bureau National de l'État Civil</p>
        </div>

        <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
            @csrf

            <div class="form-floating">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Votre adresse email"
                    value="{{ old('email') }}"
                    required
                >
                <label for="email">
                    <i class="fas fa-envelope me-2"></i>Adresse email
                </label>
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-floating">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Votre mot de passe"
                    required
                >
                <label for="password">
                    <i class="fas fa-lock me-2"></i>Mot de passe
                </label>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt me-2"></i>
                Se connecter
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const inputs = document.querySelectorAll('.form-control');

    // Add loading state on form submit
    loginForm.addEventListener('submit', function() {
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;
    });

    // Enhanced input animations
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });

        // Add typing animation effect
        input.addEventListener('input', function() {
            this.style.borderColor = '#28a745';
            setTimeout(() => {
                this.style.borderColor = '';
            }, 200);
        });
    });

    // Add particle effect on button hover
    const loginCard = document.querySelector('.login-card');
    loginBtn.addEventListener('mouseenter', function() {
        createParticles();
    });

    function createParticles() {
        for (let i = 0; i < 5; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                particle.style.position = 'absolute';
                particle.style.width = '4px';
                particle.style.height = '4px';
                particle.style.backgroundColor = '#4a90e2';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animation = 'particleFade 1s ease-out forwards';

                loginCard.appendChild(particle);

                setTimeout(() => {
                    particle.remove();
                }, 1000);
            }, i * 100);
        }
    }

    // Add CSS for particle animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes particleFade {
            0% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            100% {
                opacity: 0;
                transform: scale(0) translateY(-20px);
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
