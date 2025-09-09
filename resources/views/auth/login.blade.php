<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Color Palette */
        :root {
            --primary: #388087;
            --secondary: #6FB3B8;
            --light-blue: #BADFE7;
            --mint: #C2EDCE;
            --off-white: #F6F6F2;
        }

        /* General Body Styling */
        body {
            background-color: var(--light-blue);
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Login Container Styling */
        .login-container {
            display: flex;
            width: 900px;
            background: var(--off-white);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .login-image {
            width: 50%;
            background: var(--secondary) url('assets/images/login.png') no-repeat center center;
            background-size: cover;
        }

        .login-form {
            width: 50%;
            padding: 50px;
        }

        .login-form h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .btn-custom {
            background-color: var(--primary);
            color: var(--off-white);
            font-weight: bold;
            border: none;
            padding: 10px;
        }

        .btn-custom:hover {
            background-color: var(--secondary);
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .form-footer a {
            text-decoration: none;
            font-size: 14px;
            color: var(--primary);
        }

        .form-footer a:hover {
            color: var(--secondary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Image Section -->
        <div class="login-image"></div>

        <!-- Right Form Section -->
        <div class="login-form">
            <h2 class="text-center">Sign In</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Sign In</button>
                <div class="form-footer mt-3">
                    <div>
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember"> Remember Me</label>
                    </div>

                </div>
    
            </form>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
