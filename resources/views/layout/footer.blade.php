<!-- Modal Overlay -->
<div id="modalOverlay" class="modal-overlay">

    <div class="modal-box">

        <h3 id="modalTitle">Title</h3>

        <div id="modalBody"></div>

        <div class="modal-actions">
            <button onclick="closeModal()">Cancel</button>
        </div>

    </div>

</div>

<div class="fab-container" id="fabContainer">
    <div class="fab-options">
        <button onclick="createFolder()">Create Folder</button>
        <button onclick="uploadFile()">Upload File</button>
    </div>
    <button class="fab-btn" onclick="toggleFab()">+</button>
</div>
@include('/layout/js')
</body>

</html>