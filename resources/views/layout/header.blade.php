<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Drive Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Code:wght@600&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<style>
    :root {
        --bg-color: #111827;
        --card-bg: #1f2937;
        --text-color: #f9fafb;
        --sub-text: #9ca3af;
        --border-color: #374151;
        --btn-bg: #3b82f6;
        --btn-hover: #2563eb;
        --overlay-bg: rgba(0, 0, 0, 0.6);
        --danger-bg: #ef4444;
        --folder-icon: #fbbf24;
    }

    /* Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-color);
        color: var(--text-color);
        font-family: 'Roboto', sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* HEADER */
    .header {
        height: 65px;
        background: var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 25px;
        border-bottom: 1px solid var(--border-color);
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .logo {
        font-size: 1.6rem;
        font-weight: 600;
        font-family: "Google Sans Code", monospace;
        color: var(--btn-bg);
        letter-spacing: -1px;
    }

    .profile-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--btn-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: bold;
        color: white;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .profile-icon:hover {
        transform: scale(1.08);
    }

    /* SIDEBAR */
    .sidebar {
        position: fixed;
        top: 0;
        left: -350px;
        width: 320px;
        height: 100%;
        background: var(--card-bg);
        border-right: 1px solid var(--border-color);
        padding: 40px 25px;
        display: flex;
        flex-direction: column;
        transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
        z-index: 100;
    }

    .sidebar.active {
        left: 0;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        color: var(--sub-text);
        font-size: 32px;
        cursor: pointer;
    }

    /* Profile Info */
    .profile-info {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
    }

    .avatar-large {
        width: 90px;
        height: 90px;
        background: var(--btn-bg);
        border-radius: 50%;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    /* FOLDERS AREA */
    .folder-card {
        background: var(--card-bg);
        padding: 15px 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 200px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .folder-card:hover {
        background: #2d3748;
        border-color: var(--btn-bg);
        transform: translateY(-3px);
    }

    .folder-icon-img {
        color: var(--folder-icon);
        font-size: 20px;
    }

    /* FAB */
    .fab-container {
        position: fixed;
        bottom: 35px;
        right: 35px;
        z-index: 90;
    }

    .fab-btn {
        width: 75px;
        height: 10px;
        border-radius: 50%;
        background: var(--btn-bg);
        color: white;
        font-size: 35px;
        border: none;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s ease, background 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fab-container.active .fab-btn {
        transform: rotate(45deg);
        background: var(--danger-bg);
    }

    .fab-options {
        position: absolute;
        bottom: 80px;
        right: 0;
        display: none;
        flex-direction: column;
        gap: 12px;
    }

    .fab-container.active .fab-options {
        display: flex;
        animation: fadeSlideIn 0.25s ease forwards;
    }

    .fab-options button {
        background: var(--card-bg);
        color: white;
        border: 1px solid var(--border-color);
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 50;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.25s ease;
    }

    .fab-options button:hover {
        background: var(--btn-bg);
        border-color: var(--btn-bg);
    }

    /* FAB Animation Keyframes */
    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* MODALS */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: var(--overlay-bg);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        pointer-events: none;
        backdrop-filter: blur(5px);
        transition: opacity 0.35s ease;
        z-index: 200;
    }

    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-box {
        background: var(--card-bg);
        padding: 25px;
        width: 350px;
        border-radius: 15px;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        transform: scale(0.9);
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .modal-overlay.active .modal-box {
        transform: scale(1);
    }

    /* Modal Content */
    .modal-box h3 {
        margin-bottom: 15px;
        color: white;
    }

    .modal-box input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-color);
        color: white;
        transition: border 0.25s ease, background 0.25s ease;
        outline: none;
    }

    .modal-box input:focus {
        border-color: var(--btn-bg);
    }

    /* Modal Actions */
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .modal-actions button {
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid var(--sub-text);
        color: var(--sub-text);
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .btn-confirm {
        background: var(--btn-bg);
        color: white;
    }

    .btn-confirm:hover {
        background: var(--btn-hover);
    }

    /* LOGOUT BUTTON */
    .logout-btn {
        width: 100%;
        padding: 14px;
        background: transparent;
        border: 2px solid var(--danger-bg);
        color: var(--danger-bg);
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        margin-top: auto;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .logout-btn:hover {
        background: var(--danger-bg);
        color: white;
    }

    :root {
        --bg-color: #111827;
        --card-bg: #1f2937;
        --text-color: #f9fafb;
        --sub-text: #9ca3af;
        --border-color: #374151;
        --btn-bg: #3b82f6;
        --btn-hover: #2563eb;
        --overlay-bg: rgba(0, 0, 0, 0.6);
        --danger-bg: #ef4444;
        --folder-icon: #fbbf24;
    }

    /* Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-color);
        color: var(--text-color);
        font-family: 'Roboto', sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* HEADER */
    .header {
        height: 65px;
        background: var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 25px;
        border-bottom: 1px solid var(--border-color);
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .logo {
        font-size: 1.6rem;
        font-weight: 600;
        font-family: "Google Sans Code", monospace;
        color: var(--btn-bg);
        letter-spacing: -1px;
    }

    .profile-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--btn-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: bold;
        color: white;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .profile-icon:hover {
        transform: scale(1.08);
    }

    /* SIDEBAR */
    .sidebar {
        position: fixed;
        top: 0;
        left: -350px;
        width: 320px;
        height: 100%;
        background: var(--card-bg);
        border-right: 1px solid var(--border-color);
        padding: 40px 25px;
        display: flex;
        flex-direction: column;
        transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
        z-index: 100;
    }

    .sidebar.active {
        left: 0;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        color: var(--sub-text);
        font-size: 32px;
        cursor: pointer;
    }

    /* Profile Info */
    .profile-info {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
    }

    .avatar-large {
        width: 90px;
        height: 90px;
        background: var(--btn-bg);
        border-radius: 50%;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    /* FOLDERS AREA */
    .folder-card {
        background: var(--card-bg);
        padding: 15px 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 200px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .folder-card:hover {
        background: #2d3748;
        border-color: var(--btn-bg);
        transform: translateY(-3px);
    }

    .folder-icon-img {
        color: var(--folder-icon);
        font-size: 20px;
    }

    /* =============================== */
    /* FAB (Floating Action Button) */
    /* =============================== */
    .fab-container {
        position: fixed;
        bottom: 35px;
        right: 35px;
        z-index: 90;
    }

    .fab-btn {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: var(--btn-bg);
        color: white;
        font-size: 35px;
        border: none;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), background 0.35s ease, box-shadow 0.35s ease;
    }

    .fab-btn:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
    }

    .fab-container.active .fab-btn {
        transform: rotate(45deg);
        background: var(--danger-bg);
    }

    /* FAB Options */
    .fab-options {
        position: absolute;
        bottom: 80px;
        right: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        opacity: 0;
        transform: translateY(15px);
        pointer-events: none;
        transition: all 0.35s ease;
    }

    .fab-container.active .fab-options {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    /* FAB Option Buttons */
    .fab-options button {
        background: var(--card-bg);
        color: white;
        border: 1px solid var(--border-color);
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.25s ease;
    }

    .fab-options button:hover {
        background: var(--btn-bg);
        border-color: var(--btn-bg);
    }

    /* Stagger Animation (Optional) */
    .fab-container.active .fab-options button:nth-child(1) {
        transition-delay: 0s;
    }

    .fab-container.active .fab-options button:nth-child(2) {
        transition-delay: 0.05s;
    }

    .fab-container.active .fab-options button:nth-child(3) {
        transition-delay: 0.1s;
    }

    /* =============================== */
    /* MODAL / POPUP */
    /* =============================== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: var(--overlay-bg);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        pointer-events: none;
        backdrop-filter: blur(5px);
        transition: opacity 0.35s ease;
        z-index: 200;
    }

    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    /* Modal Box */
    .modal-box {
        background: var(--card-bg);
        padding: 25px;
        width: 350px;
        border-radius: 15px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.45);
        transform: scale(0.9) translateY(-15px);
        opacity: 0;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease;
    }

    .modal-overlay.active .modal-box {
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    /* Modal Content */
    .modal-box h3 {
        margin-bottom: 15px;
        color: white;
    }

    .modal-box input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-color);
        color: white;
        transition: border 0.25s ease, background 0.25s ease, transform 0.25s ease;
    }

    .modal-box input:focus {
        border-color: var(--btn-bg);
        transform: scale(1.02);
    }

    /* Modal Actions */
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .modal-actions button {
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid var(--sub-text);
        color: var(--sub-text);
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .btn-confirm {
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--btn-bg);
        color: white;
    }

    .btn-confirm:hover {
        background: var(--btn-hover);
    }

    /* LOGOUT BUTTON */
    .logout-btn {
        width: 100%;
        padding: 14px;
        background: transparent;
        border: 2px solid var(--danger-bg);
        color: var(--danger-bg);
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        margin-top: auto;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .logout-btn:hover {
        background: var(--danger-bg);
        color: white;
    }
</style>

<body>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="header">
        <div class="logo" style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 34px;">☁️</span> My Drive
        </div>
        <div class="profile-icon" onclick="toggleSidebar()" id="header-avatar">?</div>
    </div>

    <div class="sidebar" id="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">&times;</button>

        <div class="profile-info">
            <div class="avatar-large" id="sidebar-avatar">?</div>
            <h3 id="user-name">Loading...</h3>
            <p id="user-email" style="color: var(--sub-text)">...</p>
        </div>

        <button class="logout-btn" onclick="handleLogout()">Logout</button>
    </div>