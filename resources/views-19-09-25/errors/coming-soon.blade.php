<style>
    body {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    .coming-soon-container {
        padding: 80px 20px;
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .coming-title {
        font-size: 60px;
        font-weight: 800;
        color: #0d6efd;
        animation: pulse 2s infinite;
    }

    .coming-subtext {
        font-size: 24px;
        color: #495057;
        margin-top: 20px;
    }

    .info-text {
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

    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.05); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>

<div class="coming-soon-container">
    <div class="coming-title">Coming Soon</div>
    <div class="coming-subtext">This feature is under development on <strong>Talentrek</strong>.</div>
    <p class="info-text">We're working hard to bring it to you. Stay tuned for updates!</p>
</div>
