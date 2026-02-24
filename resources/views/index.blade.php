@include('/layout/header')
<style>
    :root {
        --bg: #111827;
        --card-bg: #1f2937;
        --border: #374151;
        --text: #f9fafb;
        --sub-text: #9ca3af;
        --btn: #3b82f6;
        --btn-hover: #2563eb;
    }

    body {
        background: var(--bg);
        color: var(--text);
        font-family: Arial, sans-serif;
    }

    main {
        padding: 20px;
    }

    /* Section Titles */
    h3 {
        margin-top: 30px;
        margin-bottom: 15px;
        color: var(--sub-text);
        font-weight: 500;
    }

    /* Grid Layout */
    .drive-list {
        list-style: none;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }

    /* Card Style */
    .card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 15px;
        transition: 0.2s ease;
        display: flex;
        justify-content: space-between;
    }

    .card:hover {
        transform: translateY(-4px);
        border-color: var(--btn);
    }

    /* Folder/File Name */
    .card span {
        font-size: 15px;
        margin-bottom: 10px;
        word-break: break-word;
    }

    /* Button */
    .card button {
        background: var(--btn);
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        color: white;
        cursor: pointer;
        font-size: 13px;
    }

    .card button:hover {
        background: var(--btn-hover);
    }

    #backBtn {
        background: #374151;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        margin-bottom: 15px;
    }

    #backBtn:hover {
        background: #4b5563;
    }

    /* Animation base */
    .fade-slide-enter {
        opacity: 0;
        transform: translateY(15px);
    }

    .fade-slide-enter-active {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    /* Card hover smooth */
    .card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
    }
</style>
<main>
    <button id="backBtn" onclick="goBack()"> Back</button>
    <div id="breadcrumb"></div>

    <h3>Folders</h3>
    <ul id="folders-container" class="drive-list"></ul>

    <h3>Files</h3>
    <ul id="files-container" class="drive-list"></ul>
</main>
@include('/layout/footer')