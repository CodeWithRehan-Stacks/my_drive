 <script>
     let files = [];
     let folders = [];
     let folderpath = null;
     let folderHistory = [];
     const token = localStorage.getItem('access_token');
     let user;

     async function fetchDriveData() {

         if (!token) {
             console.error('No authorization token found. Please log in.');
             return;
         }

         try {
             const response = await fetch('/api/drive', {
                 method: 'GET',
                 headers: {
                     'Authorization': `Bearer ${token}`,
                     'Content-Type': 'application/json'
                 }
             });

             if (!response.ok) {
                 throw new Error(`Error: ${response.status}`);
             }

             const data = await response.json();

             folders = data.folders;
             files = data.files;

             renderFolders();

         } catch (error) {
             console.error('Failed to fetch drive data:', error.message);
         }
     }
     fetchDriveData();

     function renderFolders() {
         let showFolder = document.querySelector('#folders-container');
         let showFile = document.querySelector('#files-container');
         let backBtn = document.querySelector('#backBtn');

         // Animate reset
         showFolder.classList.remove("fade-slide-enter-active");
         showFile.classList.remove("fade-slide-enter-active");

         showFolder.innerHTML = "";
         showFile.innerHTML = "";

         backBtn.style.display = folderHistory.length > 0 ? "inline-block" : "none";

         let folderHTML = "";
         let fileHTML = "";

       folders.forEach(folder => {
    if (folder.parent_id === folderpath) {
        folderHTML += `
        <li class="card">
            <div class="folder-left">
                📁 ${folder.name}
            </div>

            <div class="folder-right">
                <button onclick="openFolder(${folder.id})">
                    Open
                </button>

                <button class="delete-btn"
                        onclick="deleteFolder(${folder.id})">
                    🗑
                </button>
            </div>
        </li>
        `;
    }
});

         files.forEach(file => {
             if (file.folder_id === folderpath) {
                 fileHTML += `
        <li class="card">
            <div class="file-left">
                📄 ${file.name}
            </div>

            <div class="file-right">
                <span>${(file.size / 1024).toFixed(1)} KB</span>
                <button class="delete-btn" onclick="deleteFile(${file.id})">
                    🗑
                </button>
            </div>
        </li>
        `;
             }
         });

         showFolder.innerHTML = folderHTML;
         showFile.innerHTML = fileHTML;

         // Trigger animation
         requestAnimationFrame(() => {
             showFolder.classList.add("fade-slide-enter");
             showFile.classList.add("fade-slide-enter");

             setTimeout(() => {
                 showFolder.classList.add("fade-slide-enter-active");
                 showFile.classList.add("fade-slide-enter-active");
             }, 10);
         });
     }

     function goBack() {
         if (folderHistory.length > 0) {
             folderpath = folderHistory.pop();
             renderFolders();
         }
     }

     function openFolder(id) {
         folderHistory.push(folderpath);
         folderpath = id;
         renderFolders();
     }
     const loadUserData = async () => {
         const token = localStorage.getItem('access_token');
         if (!token) {
             window.location.href = "{{ route('home') }}";
             return;
         }

         try {
             const response = await fetch('/api/user', {
                 method: 'GET',
                 headers: {
                     'Authorization': `Bearer ${token}`,
                     'Accept': 'application/json'
                 }
             });

             if (response.status === 401) {
                 localStorage.removeItem('access_token');
                 window.location.href = "{{ route('home') }}";
                 return;
             }

             const result = await response.json();
             user = result.data;

             const initial = user.name.charAt(0).toUpperCase();
             document.getElementById('header-avatar').innerText = initial;
             document.getElementById('sidebar-avatar').innerText = initial;
             document.getElementById('user-name').innerText = user.name;
             document.getElementById('user-email').innerText = user.email;

             if (user.storage_percent !== undefined) {
                 document.getElementById('user-plan').innerText = user.plan_name;
                 document.getElementById('plan-progress').style.width = user.storage_percent + '%';

                 if (user.storage_percent > 90) {
                     document.getElementById('plan-progress').style.background = 'var(--danger-bg)';
                 }

                 document.getElementById('plan-details').innerText = `${user.storage_used} GB used of ${user.storage_total} GB`;
             }
         } catch (error) {
             console.error('Error fetching profile:', error);
         }
     };

     const handleLogout = async () => {
         if (!confirm("Are you sure you want to logout?")) return;

         const token = localStorage.getItem('access_token');
         try {
             await fetch('/api/logOut', {
                 method: 'POST',
                 headers: {
                     'Authorization': `Bearer ${token}`,
                     'Accept': 'application/json'
                 }
             });
         } catch (e) {
             console.error("Logout error", e);
         } finally {
             localStorage.removeItem('access_token');
             window.location.href = "{{ route('home') }}";
         }
     };

     function toggleSidebar() {
         document.getElementById('sidebar').classList.toggle('active');
         document.getElementById('overlay').classList.toggle('active');
     }

     document.addEventListener('DOMContentLoaded', () => {
         loadUserData();
     });

     document.addEventListener('keydown', (e) => {
         if (e.key === 'Escape') {
             document.getElementById('sidebar')?.classList.remove('active');
             document.getElementById('overlay')?.classList.remove('active');
             document.getElementById('fabContainer')?.classList.remove('active');
             closeModals();
         }
     });



     function toggleFab() {
         const fab = document.getElementById('fabContainer');
         if (fab) fab.classList.toggle('active');
     }

     function openModal(title, contentHTML) {
         document.getElementById("modalTitle").innerText = title;
         document.getElementById("modalBody").innerHTML = contentHTML;

         document.getElementById("modalOverlay").classList.add("active");
     }

     function closeModal() {
         document.getElementById("modalOverlay").classList.remove("active");
     }

     function createFolder() {
         openModal("Create Folder", `
    <input type="text" id="folderName" placeholder="Folder Name">
    <button class='btn-confirm' onclick="submitFolder()">Create</button>
    `);
     }

     function uploadFile() {
         openModal("Upload File", `
    <input type="file" id="fileInput">
    <input type="text" id="fileNameInput">
    <button class='btn-confirm' onclick="submitFile()">Upload</button>
    `);
     }

     async function submitFolder() {
         const token = localStorage.getItem("access_token");
         if (!token) {
             alert("You are not logged in!");
             return;
         }

         let name = document.getElementById("folderName").value;
         if (!name) {
             alert("Folder name cannot be empty!");
             return;
         }

         try {
             const response = await fetch('/api/create', {
                 method: 'POST',
                 headers: {
                     'Authorization': `Bearer ${token}`,
                     'Content-Type': 'application/json'
                 },
                 body: JSON.stringify({
                     name: name,
                     parent_id: folderpath,
                     user_id: user.id,
                     path: folderHistory
                 })
             });

             const data = await response.json();
             if (!response.ok) {
                 throw new Error(data.message || "Folder creation failed");
             }

             console.log("Folder created:", data);
             alert("Folder created successfully!");
             fetchDriveData();

         } catch (error) {
             console.error('Failed to create folder:', error.message);
             alert("Failed to create folder: " + error.message);
         }

         closeModal();
     }


     async function submitFile() {
         const token = localStorage.getItem("access_token");
         if (!token) return alert("You are not logged in!");

         const fileInput = document.getElementById("fileInput");
         const file = fileInput.files[0];
         if (!file) return alert("Select a file first");

         const customName = document.getElementById("fileNameInput").value;


         const formData = new FormData();
         formData.append("file", file);
         if (customName) formData.append("name", customName);
         formData.append("folder_id", folderpath ?? "");
         formData.append("user_id", user.id);

         try {
             const response = await fetch("/api/upload", {
                 method: "POST",
                 headers: {
                     "Authorization": `Bearer ${token}`,
                 },
                 body: formData
             });

             let data;
             try {
                 data = await response.json();
             } catch (e) {
                 throw new Error("Server returned invalid JSON. Check Laravel route and token.");
             }

             if (!response.ok) {
                 throw new Error(data.error || "Upload failed");
             }

             console.log("File uploaded:", data.file);
             alert("File uploaded successfully!");



             fileInput.value = "";
             document.getElementById("fileNameInput").value = "";

             closeModal();

             fetchDriveData();

         } catch (error) {
             console.error("Upload error:", error);
             alert("Upload failed: " + error.message);
         }

     }


     async function deleteFile(fileId) {
         const token = localStorage.getItem("access_token");

         if (!confirm("Are you sure you want to delete this file?")) return;

         try {
             const response = await fetch('/api/file/delete', {
                 method: 'DELETE',
                 headers: {
                     'Authorization': `Bearer ${token}`,
                     'Content-Type': 'application/json'
                 },
                 body: JSON.stringify({
                     file_id: fileId
                 })
             });

             const data = await response.json();

             if (!response.ok) {
                 throw new Error(data.error || "Delete failed");
             }

             alert("File deleted successfully!");
             fetchDriveData(); // Refresh list

         } catch (error) {
             console.error("Delete error:", error);
             alert("Delete failed: " + error.message);
         }
     }

     async function deleteFolder(folderId) {
    const token = localStorage.getItem("access_token");

    if (!confirm("Are you sure you want to delete this folder?")) return;

    try {
        const response = await fetch('/api/folder/delete', {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                folder_id: folderId
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || "Delete failed");
        }

        alert("Folder deleted successfully!");
        fetchDriveData();

    } catch (error) {
        console.error("Delete error:", error);
        alert("Delete failed: " + error.message);
    }
}
 </script>