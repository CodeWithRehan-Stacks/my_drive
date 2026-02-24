<!DOCTYPE html>
<html lang="en" id="html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY DRIVE APP - Register</title>

    <style>
        :root {
            --bg-color: #111827;
            --card-bg: #1f2937;
            --text-color: #f9fafb;
            --sub-text: #9ca3af;
            --border-color: #374151;
            --btn-bg: #3b82f6;
            --btn-text: #ffffff;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Franklin Gothic Medium', 'Arial Narrow';
            transition: all 0.3s ease;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 30px;
            padding: 40px 20px;
            text-align: center;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(16, 185, 129, 0.08));
        }

        .card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            width: 300px;
        }

        .card p {
            color: var(--sub-text);
            margin-top: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-color);
        }

        button {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: none;
            background: var(--btn-bg);
            color: var(--btn-text);
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            opacity: 0.9;
        }

        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-color);
        }

        a {
            color: var(--btn-bg);
            text-decoration: none;
        }

        .route {
            top: 20px;
            right: 20px;
            padding: 6px 12px;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <h1>Login your Account</h1>

        <div class="card">
           <form id="formData" onsubmit="login(event)">
                <input type="email" id="email" name="email" placeholder="Email">
                <input type="password" id="password" name="password" placeholder="Password">
                <button type="submit">login</button>
            </form>
            <div class="route">
                <p>Create New <a href="{{ route('register') }}">account?</a></p>
            </div>
        </div>
    </div>
<script>
    const loginForm = document.getElementById('formData');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    const login = (e) => {
        e.preventDefault();

        const loginData = {
            email: emailInput.value,
            password: passwordInput.value
        };

        fetch('/api/logIn', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json' // Good practice for APIs
            },
            body: JSON.stringify(loginData)
        })
        .then(response => {
            if (!response.ok) throw response; // Catch errors like 401 Unauthorized
            return response.json();
        })
        .then(data => {
            // 1. Use 'data', not 'response'
            localStorage.setItem('access_token', data.access_token);
            
            // 2. Correct alert syntax
            alert("Success: " + data.message);
            
           window.location.href = "{{ route('deshbored') }}";
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Login failed. Please check your credentials.");
            
        });
    };

    loginForm.addEventListener('submit', login);
</script>
</body>

</html>