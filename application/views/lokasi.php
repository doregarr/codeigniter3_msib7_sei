<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .modal-footer-custom {
            display: flex;
            justify-content: flex-end;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#exampleModal">
            <i class="bi bi-plus-circle-fill"></i> Tambah Lokasi
        </button>

        <!-- Modal Tambah / Edit -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Lokasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="lokasiForm">
                            <input type="hidden" id="lokasiId">
                            <div class="form-group">
                                <label for="namaLokasi">Nama Lokasi</label>
                                <input type="text" class="form-control" id="namaLokasi" required>
                            </div>
                            <div class="form-group">
                                <label for="negara">Negara</label>
                                <input type="text" class="form-control" id="negara" required>
                            </div>
                            <div class="form-group">
                                <label for="provinsi">Provinsi</label>
                                <input type="text" class="form-control" id="provinsi" required>
                            </div>
                            <div class="form-group">
                                <label for="kota">Kota</label>
                                <input type="text" class="form-control" id="kota" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveLokasiBtn" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table mt-4" id="lokasiTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Lokasi</th>
                    <th scope="col">Negara</th>
                    <th scope="col">Provinsi</th>
                    <th scope="col">Kota</th>
                    <th scope="col" colspan="2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Konfigurasi Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "1500"
        };

        function fetchLokasiData() {
            fetch("http://localhost:8080/api/lokasi")
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector("#lokasiTable tbody");
                    tableBody.innerHTML = ""; 
                    data.forEach(lokasi => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <th scope="row">${lokasi.id}</th>
                            <td>${lokasi.namaLokasi}</td>
                            <td>${lokasi.negara}</td>
                            <td>${lokasi.provinsi}</td>
                            <td>${lokasi.kota}</td>
                            <td>
                                <button type="button" class="btn btn-outline-warning edit-btn" data-id="${lokasi.id}" data-toggle="modal" data-target="#exampleModal">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-delete" data-id="${lokasi.id}">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function clearForm() {
            document.getElementById("lokasiForm").reset();
            document.getElementById("lokasiId").value = "";
            document.getElementById("exampleModalLabel").textContent = "Tambah Lokasi";
            document.getElementById("saveLokasiBtn").textContent = "Save changes";
        }

        function populateForm(lokasi) {
            document.getElementById("namaLokasi").value = lokasi.namaLokasi;
            document.getElementById("negara").value = lokasi.negara;
            document.getElementById("provinsi").value = lokasi.provinsi;
            document.getElementById("kota").value = lokasi.kota;
            document.getElementById("lokasiId").value = lokasi.id;
            document.getElementById("exampleModalLabel").textContent = "Edit Lokasi";
            document.getElementById("saveLokasiBtn").textContent = "Update changes";
        }

        document.getElementById("saveLokasiBtn").addEventListener("click", function() {
            const lokasiData = {
                namaLokasi: document.getElementById("namaLokasi").value,
                negara: document.getElementById("negara").value,
                provinsi: document.getElementById("provinsi").value,
                kota: document.getElementById("kota").value
            };
            const lokasiId = document.getElementById("lokasiId").value;
            const method = lokasiId ? "PUT" : "POST";
            const url = lokasiId ? `http://localhost:8080/api/lokasi/${lokasiId}` : "http://localhost:8080/api/lokasi";

            fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(lokasiData)
            })
            .then(response => response.json())
            .then(data => {
                console.log("Success:", data);
                toastr.success('Data berhasil ' + (lokasiId ? 'diupdate' : 'ditambahkan') + '!', 'Sukses');
                $('#exampleModal').modal('hide');
                fetchLokasiData(); // Refresh tabel setelah data ditambahkan atau diperbarui
            })
            .catch((error) => {
                console.error("Error:", error);
                toastr.error('Gagal ' + (lokasiId ? 'mengupdate' : 'menambahkan') + ' data!', 'Error');
            });
        });

        document.addEventListener("click", function(event) {
            if (event.target.matches(".btn-delete")) { 
                const id = event.target.getAttribute("data-id");
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    fetch(`http://localhost:8080/api/lokasi/${id}`, {
                        method: "DELETE"
                    })
                    .then(response => {
                        if (response.ok) {
                            toastr.success('Data berhasil dihapus!', 'Sukses');
                            fetchLokasiData(); // Refresh tabel setelah data dihapus
                        } else {
                            return response.text().then(text => { 
                                throw new Error(text);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error('Gagal menghapus data!', 'Error');
                    });
                }
            }
        });

        document.addEventListener("click", function(event) {
            if (event.target.matches(".edit-btn")) { 
                const id = event.target.getAttribute("data-id");
                fetch(`http://localhost:8080/api/lokasi/${id}`)
                    .then(response => response.json())
                    .then(lokasi => {
                        populateForm(lokasi);
                    })
                    .catch(error => console.error("Error fetching data:", error));
            }
        });

        document.addEventListener("DOMContentLoaded", fetchLokasiData);
        $('#exampleModal').on('hidden.bs.modal', clearForm);
    </script>
</body>
</html>
