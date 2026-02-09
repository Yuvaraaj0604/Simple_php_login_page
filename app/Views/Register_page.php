<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Simple Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-box {
            width: 400px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 28px;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .msg {
            margin-top: 15px;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            font-size: 14px;
            display: none;
        }

        .error {
            display: block;
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .success {
            display: block;
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="register-box">
        <h2>Create Account</h2>

        <div class="input-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" placeholder="Enter your full name" required>
        </div>

        <div class="input-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" placeholder="Enter your email" required>
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" placeholder="Create a password" required>
        </div>

        <div class="input-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" placeholder="Confirm your password" required>
        </div>

        <button onclick="register()">Register</button>

        <div id="msg" class="msg"></div>

        <div class="login-link">
            Already have an account? <a href="/login">Login here</a>
        </div>
    </div>

    <script>
        async function register() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validation
            if (!name || !email || !password || !confirmPassword) {
                showMessage('Please fill all fields', 'error');
                return;
            }

            if (password !== confirmPassword) {
                showMessage('Passwords do not match', 'error');
                return;
            }

            if (password.length < 6) {
                showMessage('Password must be at least 6 characters', 'error');
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showMessage('Please enter a valid email address', 'error');
                return;
            }

            try {
                const response = await fetch('/users', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    showMessage(data.message || data.error || 'Registration failed', 'error');
                    return;
                }

                showMessage('Registration successful! Redirecting to login...', 'success');

                // Redirect to login page after 2 seconds
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);

            } catch (err) {
                console.error(err);
                showMessage('Server error. Please try again later.', 'error');
            }
        }

        function showMessage(text, type) {
            const msg = document.getElementById('msg');
            msg.innerText = text;
            msg.className = 'msg ' + type;
        }

        // Allow Enter key to submit
        document.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                register();
            }
        });
    </script>

</body>

</html>