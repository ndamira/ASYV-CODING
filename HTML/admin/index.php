<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        :root {
            --base-clr: #11121a;
            --line-clr: #42434a;
            --hover-clr: #222533;
            --text-clr: #e6e6ef;
            --accent-clr: rgb(75, 139, 102);
            --secondary-text-clr: #b0b3b1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--base-clr);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-clr);
            line-height: 1.6;
        }

        .login-container {
            background-color: var(--hover-clr);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            font-size: 2rem;
            color: var(--text-clr);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--secondary-text-clr);
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-clr);
        }

        .form-group input {
            padding: 0.75rem;
            background-color: var(--base-clr);
            border: 1px solid var(--line-clr);
            border-radius: 6px;
            color: var(--text-clr);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-clr);
            box-shadow: 0 0 0 3px rgba(75, 139, 102, 0.2);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            appearance: none;
            width: 1rem;
            height: 1rem;
            border: 1px solid var(--line-clr);
            border-radius: 4px;
            background-color: var(--base-clr);
            cursor: pointer;
            position: relative;
        }

        .remember-me input[type="checkbox"]:checked {
            background-color: var(--accent-clr);
            border-color: var(--accent-clr);
        }

        .remember-me input[type="checkbox"]:checked::after {
            content: '✔';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 0.7rem;
        }

        .remember-me label {
            color: var(--secondary-text-clr);
            font-size: 0.875rem;
        }

        .forgot-password a {
            color: var(--secondary-text-clr);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--text-clr);
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--accent-clr);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: rgb(62, 116, 85);
        }

        @media (max-width: 480px) {
            .login-container {
                width: 90%;
                padding: 1.5rem;
            }
        }
        .error-message {
            background-color: rgba(255, 77, 77, 0.1);
            color: var(--error-clr);
            border: 1px solid var(--error-clr);
            padding: 0.75rem;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 1rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-container">
    <?php
        // Display error message if exists
        if (isset($_GET['message'])) {
            echo '<div class="error-message show">' . htmlspecialchars($_GET['message']) . '</div>';
        }
        ?>
        <div class="login-header">
            <h2>Admin Login</h2>
            <p>Enter your credentials to access the dashboard</p>
        </div>
        <form action='../backend/createLogin.php' method='POST' class="login-form">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email"
                    name='email'
                    placeholder="Enter your email" 
                    required
                >
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password"
                    name='password'
                    placeholder="Enter your password" 
                    required
                >
            </div>
            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>
            </div>
            <button type="submit" name='adminLogin' class="submit-btn">Sign In</button>
        </form>
    </div>
</body>
</html>