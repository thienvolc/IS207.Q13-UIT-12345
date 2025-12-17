<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt lại mật khẩu - PinkCapy Admin</title>
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
            --success: #28A745;
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

        .container {
            width: 100%;
            max-width: 400px;
        }

        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .card-header {
            padding: 32px 32px 24px;
            text-align: center;
            border-bottom: 1px solid var(--gray-200);
        }

        .logo {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .logo i {
            font-size: 28px;
            color: white;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--gray-600);
        }

        .card-body {
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
            padding: 12px 16px 12px 42px;
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

        .card-footer {
            text-align: center;
            padding: 20px;
            background: var(--gray-100);
            border-top: 1px solid var(--gray-200);
        }

        .card-footer a {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fde8e8;
            border: 1px solid #f5c6cb;
            color: var(--danger);
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: var(--success);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="logo">
                    <i class="fa fa-lock"></i>
                </div>
                <h1 class="title">Đặt lại mật khẩu</h1>
                <p class="subtitle">Nhập mật khẩu mới cho tài khoản của bạn</p>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token ?? request('token') }}">
                    <input type="hidden" name="email" value="{{ $email ?? request('email') }}">

                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <i class="fa fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới"
                                required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <div class="input-group">
                            <i class="fa fa-lock input-icon"></i>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fa fa-check me-2"></i> Đặt lại mật khẩu
                    </button>
                </form>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.login') }}"><i class="fa fa-arrow-left"></i> Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</body>

</html>