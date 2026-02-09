<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            min-height: 100vh;

        }

        .login-box {
            width: 350px;
            margin: 100px auto;
            padding: 25px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
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

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
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
            margin-top: 10px;
            text-align: center;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .input-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>Login</h2>
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" class="input" id="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" class="input" id="password" placeholder="Password" required>
        </div>

        <button onclick="login()">Login</button>

        <div id="msg" class="msg"></div>

        <div class="register-link">
            Don't have an account? <a href="/register">Register here</a>
        </div>
    </div>

    <script>
        async function login() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                showMessage('Please fill all fields', 'error');
                return;
            }

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    showMessage(data.message || 'Login failed', 'error');
                    return;
                }

                // Store JWT
                localStorage.setItem('jwt_token', data.token);

                showMessage('Login successful!', 'success');

                // Redirect to welcome page
                setTimeout(() => {
                    window.location.href = '/welcome';
                }, 1000);

            } catch (err) {
                showMessage('Server error', 'error');
            }
        }

        function showMessage(text, type) {
            const msg = document.getElementById('msg');
            msg.innerText = text;
            msg.className = 'msg ' + type;
        }
    </script>

</body>

</html>