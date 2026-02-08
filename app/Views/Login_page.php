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
            font-family: Arial, sans-serif;
            background: #f4f6f8;
           
        }
        .login-box {
            width: 350px;
            margin: 100px auto;
            padding: 25px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
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
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="password" placeholder="Password" required>

    <button onclick="login()">Login</button>

    <div id="msg" class="msg"></div>
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

        // OPTIONAL: redirect after login
        // window.location.href = '/dashboard';

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
