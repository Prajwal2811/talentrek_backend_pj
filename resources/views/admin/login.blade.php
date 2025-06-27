<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from mooli.puffintheme.com/laravel/public/authentication/login by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 11 Jun 2025 07:47:59 GMT -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="YR6w5VkfoYphBTdckpHa5YTinQBI3nsoJxIT3Gwm">
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon"> <!-- Favicon-->
    <title>Talentrek | Admin</title>
    <meta name="description" content="Laravel">
    <meta name="author" content="Laravel">
    
    <link rel="stylesheet" href="{{ asset('asset/backend/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/backend/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/backend/vendor/animate-css/vivify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/backend/css/mooli.min.css') }}">
</head>

<body class="theme-cyan">
<div class="auth-main">
    <div class="auth_div vivify fadeIn">
    <div class="auth_brand">
        <a class="navbar-brand" href="#">
        <img src="{{ asset('asset/backend/images/icon.svg') }}" width="50" class="d-inline-block align-top mr-2" alt="">Talentrek</a>                                                
    </div>
    <div class="card">
        <div class="header">
            <p class="lead">Superadmin/Admin Login</p>
        </div>
        <div class="body">
            <form class="form-auth-small" action="{{ route('admin.auth') }}" method="POST">
                @csrf
                <div class="form-group c_form_group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address">
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group c_form_group position-relative">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Enter your password">
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                    
                    <span class="toggle-password" onclick="togglePassword()" style="position: absolute; top: 38px; right: 15px; cursor: pointer;">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                
                <button class="btn btn-dark btn-lg btn-block" type="submit">LOGIN</button>
                <div class="bottom">
                    <span class="helper-text m-b-10"><i class="fa fa-lock"></i> 
                        <a href="forgotpassword.html">Forgot password?</a>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("passwordInput");
            const icon = document.getElementById("eyeIcon");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

        <div class="animate_lines">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </div>
    
</div>

<!-- Scripts -->
<script src="{{ asset('asset/backend/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('asset/backend/bundles/vendorscripts.bundle.js') }}"></script>

    
</body>

<!-- Mirrored from mooli.puffintheme.com/laravel/public/authentication/login by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 11 Jun 2025 07:47:59 GMT -->
</html>
