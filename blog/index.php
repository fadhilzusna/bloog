<!DOCTYPE html>
<html>
<head>
    <title>Blog CMS</title>

<style>
body {
    font-family: Arial;
    margin: 0;
}

.header {
    background: #2196F3;
    color: white;
    padding: 15px;
    font-size: 20px;
}

.container {
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 200px;
    background: #f1f1f1;
    padding: 10px;
}

.sidebar button {
    width: 100%;
    padding: 10px;
    margin-bottom: 5px;
    border: none;
    background: white;
    cursor: pointer;
}

.sidebar button:hover {
    background: #ddd;
}

.content {
    flex: 1;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table, th, td {
    border: 1px solid #ccc;
}

th {
    background: #2196F3;
    color: white;
}

td, th {
    padding: 10px;
}

.btn {
    padding: 6px 10px;
    border: none;
    cursor: pointer;
}

.btn-tambah { background: #4CAF50; color: white; }
.btn-edit { background: orange; color: white; }
.btn-hapus { background: red; color: white; }

.modal {
    display: none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.5);
}

.modal-box {
    background:white;
    width:400px;
    margin:100px auto;
    padding:20px;
    border-radius:5px;
}
</style>

</head>

<body>

<div class="header">Blog CMS</div>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <button onclick="showMenu('penulis')">Kelola Penulis</button>
        <button onclick="showMenu('artikel')">Kelola Artikel</button>
        <button onclick="showMenu('kategori')">Kelola Kategori</button>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- PENULIS -->
        <div id="menu-penulis">
            <h2>Data Penulis</h2>
            <button class="btn btn-tambah" onclick="tambahPenulis()">Tambah Penulis</button>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-penulis"></tbody>
            </table>
        </div>

        <!-- ARTIKEL -->
        <div id="menu-artikel" style="display:none;">
            <h2>Data Artikel</h2>
            <button class="btn btn-tambah" onclick="tambahArtikel()">Tambah Artikel</button>
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-artikel"></tbody>
            </table>
        </div>

        <!-- KATEGORI -->
        <div id="menu-kategori" style="display:none;">
            <h2>Data Kategori</h2>
            <button class="btn btn-tambah" onclick="tambahKategori()">Tambah Kategori</button>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-kategori"></tbody>
            </table>
        </div>

    </div>
</div>

<!-- MODAL -->
<div id="modal" class="modal">
    <div class="modal-box">
        <h3 id="modal-title"></h3>
        <form id="modal-form"></form>
        <br>
        <button onclick="closeModal()">Tutup</button>
    </div>
</div>

<script>

// ================= MENU =================
function showMenu(menu){
    document.getElementById('menu-penulis').style.display='none';
    document.getElementById('menu-artikel').style.display='none';
    document.getElementById('menu-kategori').style.display='none';

    document.getElementById('menu-'+menu).style.display='block';
}
showMenu('penulis');

function editPenulis(id){
    fetch('ambil_penulis.php')
    .then(res=>res.json())
    .then(data=>{
        const p = data.find(x => x.id == id);

        const html = `
        <input type="hidden" name="id" value="${p.id}">

        <label>Nama Depan</label><br>
        <input type="text" name="nama_depan" value="${p.nama_depan}"><br><br>

        <label>Nama Belakang</label><br>
        <input type="text" name="nama_belakang" value="${p.nama_belakang}"><br><br>

        <label>Username</label><br>
        <input type="text" name="user_name" value="${p.user_name}"><br><br>

        <label>Password</label><br>
        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"><br><br>

        <button class="btn btn-edit">Update</button>
        `;

        openModal("Edit Penulis", html);

        document.getElementById('modal-form').onsubmit=function(e){
            e.preventDefault();
            fetch('update_penulis.php',{method:'POST',body:new FormData(this)})
            .then(res=>res.json())
            .then(()=>{
                closeModal();
                location.reload();
            });
        }
    });
}
function editArtikel(id){
    fetch('ambil_artikel.php')
    .then(res=>res.json())
    .then(data=>{
        const a = data.find(x => x.id == id);

        const html = `
        <input type="hidden" name="id" value="${a.id}">

        <label>Judul</label><br>
        <input type="text" name="judul" value="${a.judul}"><br><br>

        <label>Isi</label><br>
        <textarea name="isi">${a.isi}</textarea><br><br>

        <button class="btn btn-edit">Update</button>
        `;

        openModal("Edit Artikel", html);

        document.getElementById('modal-form').onsubmit=function(e){
            e.preventDefault();
            fetch('update_artikel.php',{method:'POST',body:new FormData(this)})
            .then(res=>res.json())
            .then(()=>{
                closeModal();
                location.reload();
            });
        }
    });
}
function editKategori(id){
    fetch('ambil_kategori.php')
    .then(res=>res.json())
    .then(data=>{
        const k = data.find(x => x.id == id);

        if(!k){
            alert("Data tidak ditemukan");
            return;
        }

        const html = `
        <input type="hidden" name="id" value="${k.id}">

        <label>Nama</label><br>
        <input type="text" name="nama_kategori" value="${k.nama_kategori}"><br><br>

        <label>Keterangan</label><br>
        <input type="text" name="keterangan" value="${k.keterangan ?? ''}"><br><br>

        <button class="btn btn-edit">Update</button>
        `;

        openModal("Edit Kategori", html);

        document.getElementById('modal-form').onsubmit=function(e){
            e.preventDefault();

            fetch('update_kategori.php',{
                method:'POST',
                body:new FormData(this)
            })
            .then(res=>res.json())
            .then(res=>{
                alert(res.status);
                closeModal();
                location.reload();
            });
        }
    });
}
function editKategori(id){
    fetch('ambil_kategori.php')
    .then(res=>res.json())
    .then(data=>{
        console.log("DATA:", data);

        const k = data.find(x => x.id == id);

        if(!k){
            alert("Data tidak ditemukan");
            return;
        }

        const html = `
        <input type="hidden" name="id" value="${k.id}">

        <input type="text" name="nama_kategori" value="${k.nama_kategori}">
        <input type="text" name="keterangan" value="${k.keterangan ?? ''}">

        <button type="submit">Update</button>
        `;

        openModal("Edit Kategori", html);

        document.getElementById('modal-form').onsubmit = function(e){
            e.preventDefault();

            const formData = new FormData(this);
            console.log("FORM DATA:", [...formData]);

            fetch('update_kategori.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                alert(res.status);
                closeModal();
                location.reload();
            });
        }
    });
}
// ================= MODAL =================
function openModal(title, html){
    document.getElementById('modal-title').innerText = title;
    document.getElementById('modal-form').innerHTML = html;
    document.getElementById('modal').style.display='block';
}
function closeModal(){
    document.getElementById('modal').style.display='none';
}

// ================= FETCH DATA =================

// PENULIS
fetch('ambil_penulis.php')
.then(res=>res.json())
.then(data=>{
    let html='';
    data.forEach(p=>{
        html+=`
        <tr>
            <td><img src="uploads_penulis/${p.foto}" width="50"></td>
            <td>${p.nama_depan} ${p.nama_belakang}</td>
            <td>${p.user_name}</td>
            <td>${p.password}</td>
            <td>
                <button class="btn btn-edit" onclick="editPenulis(${p.id})">Edit</button>
                <button class="btn btn-hapus" onclick="hapusPenulis(${p.id}, '${p.foto}')">Hapus</button>
            </td>
        </tr>`;
    });
    document.getElementById('data-penulis').innerHTML = html;
});

// KATEGORI
fetch('ambil_kategori.php')
.then(res=>res.json())
.then(data=>{
    let html='';
    data.forEach(k=>{
        html+=`
        <tr>
            <td>${k.nama_kategori}</td>
            <td>${k.keterangan ?? ''}</td>
            <td>
                <button class="btn btn-edit" onclick="editKategori(${k.id})">Edit</button>
                <button class="btn btn-hapus" onclick="hapusKategori(${k.id})">Hapus</button>
            </td>
        </tr>`;
    });
    document.getElementById('data-kategori').innerHTML = html;
});

// ARTIKEL
fetch('ambil_artikel.php')
.then(res=>res.json())
.then(data=>{
    let html='';
    data.forEach(a=>{
        html+=`
        <tr>
            <td><img src="uploads_artikel/${a.gambar}" width="60"></td>
            <td>${a.judul}</td>
            <td>${a.nama_kategori}</td>
            <td>${a.nama_depan} ${a.nama_belakang}</td>
            <td>${a.hari_tanggal}</td>
            <td>
                <button class="btn btn-edit" onclick="editArtikel(${a.id})">Edit</button>
                <button class="btn btn-hapus" onclick="hapusArtikel(${a.id}, '${a.gambar}')">Hapus</button>
            </td>
        </tr>`;
    });
    document.getElementById('data-artikel').innerHTML = html;
});

// ================= TAMBAH =================

function tambahPenulis(){
    const html=`
    <label>Nama Depan</label><br>
    <input type="text" name="nama_depan"><br><br>

    <label>Nama Belakang</label><br>
    <input type="text" name="nama_belakang"><br><br>

    <label>Username</label><br>
    <input type="text" name="user_name"><br><br>

    <label>Password</label><br>
    <input type="password" name="password"><br><br>

    <label>Foto</label><br>
    <input type="file" name="foto"><br><br>

    <button class="btn btn-tambah">Simpan</button>
    `;
    openModal("Tambah Penulis", html);

    document.getElementById('modal-form').onsubmit=function(e){
        e.preventDefault();
        fetch('simpan_penulis.php',{method:'POST',body:new FormData(this)})
        .then(res=>res.json())
        .then(()=>location.reload());
    }
}

function tambahKategori(){
    const html=`
    <label>Nama</label><br>
    <input type="text" name="nama_kategori"><br><br>

    <label>Keterangan</label><br>
    <input type="text" name="keterangan"><br><br>

    <button class="btn btn-tambah">Simpan</button>
    `;
    openModal("Tambah Kategori", html);

    document.getElementById('modal-form').onsubmit=function(e){
        e.preventDefault();
        fetch('simpan_kategori.php',{method:'POST',body:new FormData(this)})
        .then(res=>res.json())
        .then(()=>location.reload());
    }
}

function tambahArtikel(){
    const html = `
    <label>Judul</label><br>
    <input type="text" name="judul"><br><br>

    <label>Isi</label><br>
    <textarea name="isi"></textarea><br><br>

    <label>Penulis</label><br>
    <select name="id_penulis" id="modal-penulis"></select><br><br>

    <label>Kategori</label><br>
    <select name="id_kategori" id="modal-kategori"></select><br><br>

    <label>Gambar</label><br>
    <input type="file" name="gambar"><br><br>

    <button type="submit">Simpan</button>
    `;

    openModal("Tambah Artikel", html);

    // 🔥 ISI DROPDOWN PENULIS
    fetch('ambil_penulis.php')
    .then(res => res.json())
    .then(data => {
        let opt = '';
        data.forEach(p => {
            opt += `<option value="${p.id}">${p.nama_depan} ${p.nama_belakang}</option>`;
        });
        document.getElementById('modal-penulis').innerHTML = opt;
    });

    // 🔥 ISI DROPDOWN KATEGORI
    fetch('ambil_kategori.php')
    .then(res => res.json())
    .then(data => {
        let opt = '';
        data.forEach(k => {
            opt += `<option value="${k.id}">${k.nama_kategori}</option>`;
        });
        document.getElementById('modal-kategori').innerHTML = opt;
    });

    // 🔥 SUBMIT
    document.getElementById('modal-form').onsubmit = function(e){
        e.preventDefault();

        fetch('simpan_artikel.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(res => res.json())
        .then(res => {
            alert(res.status);
            closeModal();
            location.reload();
        });
    }
}
// ================= HAPUS =================

function hapusPenulis(id,foto){
    if(confirm("Apakah Anda yakin ingin menghapus data ini?")){
        let f=new FormData();
        f.append('id',id);
        f.append('foto',foto);
        fetch('hapus_penulis.php',{method:'POST',body:f})
        .then(()=>location.reload());
    }
}

function hapusKategori(id){
    if(confirm("Apakah Anda yakin ingin menghapus data ini?")){
        let f=new FormData();
        f.append('id',id);
        fetch('hapus_kategori.php',{method:'POST',body:f})
        .then(()=>location.reload());
    }
}

function hapusArtikel(id,gambar){
    if(confirm("Apakah Anda yakin ingin menghapus data ini?")){
        let f=new FormData();
        f.append('id',id);
        f.append('gambar',gambar);
        fetch('hapus_artikel.php',{method:'POST',body:f})
        .then(()=>location.reload());
    }
}

</script>

</body>
</html>
