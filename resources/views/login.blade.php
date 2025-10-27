<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Ashbab</title>
    <style>
        /* Reset and base */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #6e6e6e;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #666;
            user-select: none;
        }

        /* Container for the form */
        .login-container {
            background: #eee;
            width: 320px;
            padding: 40px 30px 30px;
            border-radius: 5px;
            box-shadow: 0 4px 14px rgb(0 0 0 / 0.25);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Avatar circle with icon */
        .avatar-circle {
            width: 90px;
            height: 90px;
            background-color: #67b6af;
            border-radius: 50%;
            position: absolute;
            top: -35px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.2);
        }

        /* User icon inside avatar circle (SVG) */
        .avatar-circle svg {
            width: 36px;
            height: 36px;
            fill: white;
        }

        /* Title */
        .login-title {
            margin: 40px 0 20px;
            font-size: 18px;
            font-weight: 600;
            color: #777;
        }

        /* Inputs */
        .login-input {
            width: 100%;
            height: 36px;
            margin-bottom: 15px;
            padding: 0 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 2px;
            outline-offset: 1px;
            transition: border-color 0.2s ease-in-out;
        }

        .login-input:focus {
            border-color: #67b6af;
            outline: none;
            box-shadow: 0 0 6px #67b6afaa;
        }

        /* Sign in button */
        .btn-signin {
            width: 100%;
            height: 38px;
            background-color: #fece00;
            border: none;
            border-radius: 2px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            margin-bottom: 15px;
            transition: background-color 0.25s ease;
        }

        .btn-signin:hover {
            background-color: #4b938c;
        }

        .btn-signin:active {
            background-color: #3c7170;
        }

        /* Flex container for remember me and forgot password */
        .login-footer {
            width: 100%;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            color: #777;
            user-select: text;
        }

        .login-footer label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .login-footer input[type="checkbox"] {
            cursor: pointer;
        }

        .forgot-link {
            text-decoration: none;
            color: #67b6af;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #4b938c;
        }

        .forgot-link:focus {
            outline: 2px solid #67b6af;
            outline-offset: 2px;
        }

        /* Below container for sign up link */
        .signup-container {
            margin-top: 16px;
            font-size: 14px;
            color: #eee;
            user-select: text;
        }

        .signup-container a {
            color: #fece00;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .signup-container a:hover {
            color: #fece00;
            text-decoration: underline;
        }

        .signup-container a:focus {
            outline: 2px solid #fece00;
            outline-offset: 2px;
        }

        .avatar-circle {
        width: 100px;          /* atur sesuai keinginan */
        height: 100px;
        border-radius: 50%;    /* bikin lingkaran */
        overflow: hidden;      /* potong gambar biar bulat */
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f0f0;   /* warna latar fallback */
        }

        .avatar-circle img {
        width: 100%;
        height: 125%;
        object-fit: cover;     /* gambar memenuhi lingkaran */
        display: block;
        }

    </style>
</head>

<body>
    <div class="login-container" role="main" aria-label="Member login form">
        <div class="avatar-circle" aria-hidden="true">
             <img src="storage/logo/logo.png" alt="User Avatar">
        </div>
        <h2 class="login-title">Admin Ashbab Coffee</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input class="login-input" type="text" name="username" placeholder="Username" aria-label="Username" required />
            <input class="login-input" type="password" name="password" placeholder="Password" aria-label="Password" required />
            <button type="submit" class="btn-signin" aria-label="Sign in to your account">Sign in</button>

            @if(session('error'))
            <p style="color: red; font-size: 14px; margin-top: 10px;">
                {{ session('error') }}
            </p>
            @endif
        </form>
    </div>

    <!-- <div class="signup-container">
    Don’t have an account? <a href="#" tabindex="0">Sign up here!</a>
  </div> -->
</body>

</html>