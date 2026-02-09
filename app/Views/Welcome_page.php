<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Simple Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-container {
            width: 500px;
            padding: 50px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .welcome-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: wave 1s ease-in-out;
        }

        @keyframes wave {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(20deg);
            }

            75% {
                transform: rotate(-20deg);
            }
        }

        h1 {
            color: #333;
            font-size: 36px;
            /* margin-bottom: 10px; */
            font-weight: 700;
        }

        .username {
            color: #667eea;
            font-weight: 700;
            /* font-size: 36px; */
            /* margin: 20px 0; */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            text-transform: capitalize;
        }

        .subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            color: #777;
        }

        .button-group {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        button {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-profile {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-logout {
            background: #f1f3f5;
            color: #495057;
        }

        .btn-logout:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .loading {
            display: none;
            text-align: center;
            color: #666;
            padding: 20px;
        }

        .error-msg {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            display: none;
        }

        .welcome-text {
            display: flex;
            /* flex-direction: column; */
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>

<body>

    <div class="welcome-container">
        <div class="loading" id="loading">
            <p>Loading...</p>
        </div>

        <div id="content" style="display: none;">
            <!-- <div class="welcome-icon">👋</div> -->
            <div class="welcome-text">
                <h1>Welcome Back</h1>
                <h1 class="username" id="username">User</h1>
            </div>
            <p class="subtitle">You have successfully logged in</p>

            <div class="user-info">
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value" id="userEmail">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">User ID:</span>
                    <span class="info-value" id="userId">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Login Time:</span>
                    <span class="info-value" id="loginTime">-</span>
                </div>
            </div>

            <div class="button-group">
                <button class="btn-profile" onclick="viewProfile()">View Profile</button>
                <button class="btn-logout" onclick="logout()">Logout</button>
            </div>
        </div>

        <div class="error-msg" id="errorMsg">
            Session expired or invalid. <a href="/login" style="color: #667eea;">Please login again</a>
        </div>
    </div>

    <script>
        // Decode JWT token (simple base64 decode - for display purposes only)
        function parseJWT(token) {
            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(atob(base64).split('').map(function (c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
                return JSON.parse(jsonPayload);
            } catch (e) {
                return null;
            }
        }

        function loadUserData() {
            const token = localStorage.getItem('jwt_token');

            if (!token) {
                showError();
                return;
            }

            const payload = parseJWT(token);

            if (!payload) {
                showError();
                return;
            }

            // Check if token is expired
            const currentTime = Math.floor(Date.now() / 1000);
            if (payload.exp && payload.exp < currentTime) {
                showError();
                return;
            }

            // Display user information
            document.getElementById('username').innerText = payload.name || payload.email || 'User';
            document.getElementById('userEmail').innerText = payload.email || '-';
            document.getElementById('userId').innerText = payload.uid || '-';
            document.getElementById('loginTime').innerText = new Date().toLocaleString();

            // Show content
            document.getElementById('loading').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        }

        function showError() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('errorMsg').style.display = 'block';

            // Clear invalid token
            localStorage.removeItem('jwt_token');

            // Redirect to login after 3 seconds
            setTimeout(() => {
                window.location.href = '/login';
            }, 3000);
        }

        async function viewProfile() {
            const token = localStorage.getItem('jwt_token');

            if (!token) {
                alert('Please login first');
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/api/profile', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Profile Data:\n' + JSON.stringify(data, null, 2));
                } else {
                    alert('Failed to fetch profile: ' + (data.message || 'Unknown error'));
                }
            } catch (err) {
                console.error(err);
                alert('Error fetching profile');
            }
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('jwt_token');
                window.location.href = '/login';
            }
        }

        // Load user data on page load
        window.addEventListener('DOMContentLoaded', loadUserData);
    </script>

</body>

</html>