<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden - Talentrek</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CDN for icons (optional) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS CDN (optional if you use Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            margin: 0;
            padding: 0;
        }

        .error-container {
            padding: 80px 20px;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-code {
            font-size: 120px;
            font-weight: 900;
            color: #dc3545;
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
            background-color: #dc3545;
            color: #fff;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-message">Access Denied</div>
        <div class="error-subtext">You don’t have permission to access this page on <strong>Talentrek</strong>.</div>
        <p class="error-subtext">Please contact your administrator or return to a page you have access to.</p>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger home-btn">
            <i class="fas fa-home me-2"></i>Back to Dashboard
        </a>
    </div>
</body>
</html>
