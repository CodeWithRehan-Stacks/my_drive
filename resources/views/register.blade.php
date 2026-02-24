<!DOCTYPE html>
<html lang="en" id="html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY DRIVE APP - Register</title>
</head>
<style>
    /* ===== Theme Variables ===== */
    :root {
        --bg-color: #111827;
        --card-bg: #1f2937;
        --text-color: #f9fafb;
        --sub-text: #9ca3af;
        --border-color: #374151;
        --btn-bg: #3b82f6;
        --btn-text: #ffffff;
    }

    /* ===== Global Styles ===== */
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

    input,
    select {
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

    option {
        background-color: var(--bg-color);
        font-family: Arial, sans-serif;

    }
</style>

<body>

   <div class="wrapper">
    <h1>Create an Account</h1>
    <div class="card">
        <form id="registrationForm">
            <input type="text" id="name" name="name" placeholder="Full Name" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <select id="plan" name="plan" required>
                <option value="">-- Choose Plan --</option>
                <option value="1">Free</option>
                <option value="2">Standard</option>
                <option value="3">Premium</option>
            </select>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="route">
            <p>Already have an <a href="{{ route('home') }}">account?</a></p>
        </div>
    </div>
</div>
<script>
    const registrationForm = document.getElementById('registrationForm');

    const signUp = async (e) => {
        // 1. Stop the default form submission (prevents the ?email=... in URL)
        e.preventDefault(); 

        // 2. Collect the data
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        // 3. Get the CSRF token (Laravel requirement)
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const response = await fetch('/api/signUp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token // Laravel looks for this!
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
             
            if (response.ok) {
                 localStorage.setItem('access_token', data.access_token);
                alert("Account created! Message: " + result.message);
               window.location.href = "{{ route('deshbored') }}"; // Redirect on success
            } else {
                // Handle validation errors from Laravel
                console.error("Validation failed:", result.errors);
                alert("Error: " + (result.message || "Check your input"));
            }

        } catch (error) {
            console.error("Critical Error:", error);
        }
    };

    // 4. Attach the listener
    if (registrationForm) {
        registrationForm.addEventListener('submit', signUp);
    }
</script>
</body>

</html>