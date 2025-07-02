{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录 - 房屋出租管理系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4ff, #e2ecff);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 1rem;
            box-shadow: 0 0 40px rgba(0,0,0,0.08);
            background-color: #ffffff;
        }
        .login-card .card-header {
            background-color: #325dff;
            color: #fff;
            border-radius: 1rem 1rem 0 0;
            text-align: center;
            padding: 1.5rem;
        }
        .login-card .form-control:focus {
            box-shadow: none;
            border-color: #325dff;
        }
        .login-card .btn-primary {
            background-color: #325dff;
            border: none;
        }
        .login-card .btn-primary:hover {
            background-color: #1c45d6;
        }
        .form-error {
            color: #d63384;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
<div class="card login-card">
    <div class="card-header">
        <h3><i class="bi bi-house-door-fill me-2"></i>房屋出租管理系统</h3>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">邮箱</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">密码</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="form-check-label">记住我</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">登录</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
