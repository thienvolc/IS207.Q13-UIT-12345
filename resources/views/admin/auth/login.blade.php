<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập Admin - PinkCapy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #714B67;
            --primary-light: #875A7B;
            --primary-dark: #5A3D52;
            --gray-100: #F4F6F8;
            --gray-200: #E9ECEF;
            --gray-600: #6C757D;
            --dark: #21313C;
            --danger: #DC3545;
            --radius-md: 4px;
            --radius-lg: 6px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            padding: 32px 32px 24px;
            text-align: center;
            border-bottom: 1px solid var(--gray-200);
        }

        .login-logo {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .login-logo i {
            font-size: 28px;
            color: white;
        }

        .login-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--gray-600);
        }

        .login-body {
            padding: 24px 32px 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(113, 75, 103, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .form-check-label {
            font-size: 14px;
            color: var(--gray-600);
        }

        .btn-primary {
            width: 100%;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            color: white;
            background: var(--primary);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            background: var(--gray-100);
            border-top: 1px solid var(--gray-200);
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .alert-danger {
            background: #fde8e8;
            border: 1px solid #f5c6cb;
            color: var(--danger);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
        }

        .input-group .form-control {
            padding-left: 42px;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fa fa-bolt"></i>
                </div>
                <h1 class="login-title">PinkCapy Admin</h1>
                <p class="login-subtitle">Đăng nhập để tiếp tục</p>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <i class="fa fa-envelope input-icon"></i>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <i class="fa fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Ghi nhớ đăng nhập</label>
                            </div>
                            <a href="{{ route('admin.password.request') }}" class="forgot-link">Quên mật khẩu?</a>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fa fa-sign-in-alt me-2"></i> Đăng nhập
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <a href="{{ url('/') }}"><i class="fa fa-arrow-left"></i> Quay về trang chủ</a>
            </div>
        </div>
    </div>
</body>
</html>
