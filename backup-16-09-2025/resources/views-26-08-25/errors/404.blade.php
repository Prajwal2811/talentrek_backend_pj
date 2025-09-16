<style>
    body{
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    .error-container {
        padding: 80px 20px;
       
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .error-code {
        font-size: 120px;
        font-weight: 900;
        color: #0d6efd;
        animation: bounce 1.5s infinite;
    }

    .error-message {
        font-size: 28px;
        color: #495057;
        margin-top: 20px;
        font-weight: 600;
    }

    .error-subtext {
        font-size: 18px;
        color: #6c757d;
        margin-top: 10px;
    }

    .home-btn {
        margin-top: 30px;
        padding: 12px 30px;
        font-size: 18px;
        border-radius: 50px;
        transition: all 0.3s ease-in-out;
    }

    .home-btn:hover {
        background-color: #0d6efd;
        color: #fff;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
</style>

<div class="error-container">
    <div class="error-code">404</div>
    <div class="error-message">Oops! Page Not Found</div>
    <div class="error-subtext">We couldn’t find the page you were looking for on <strong>Talentrek</strong>.</div>
    <p class="error-subtext">It might have been moved, deleted, or never existed.</p>
    <a href="{{ url('/') }}" class="btn btn-outline-primary home-btn">
        <i class="fas fa-home me-2"></i>Return to Homepage
    </a>
</div>
