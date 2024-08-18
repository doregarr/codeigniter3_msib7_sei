<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Management</title>
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
            <i class="bi bi-plus-circle-fill"></i> Tambah Proyek
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Proyek</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="proyekForm">
                            <input type="hidden" id="proyekId"> 
                            <!-- Form fields -->
                            <div class="form-group">
                                <label for="namaProyek">Nama Proyek</label>
                                <input type="text" class="form-control" id="namaProyek" required>
                            </div>
                            <div class="form-group">
                                <label for="client">Client</label>
                                <input type="text" class="form-control" id="client" required>
                            </div>
                            <div class="form-group">
                                <label for="tglMulai">Tanggal Mulai</label>
                                <input type="datetime-local" class="form-control" id="tglMulai" required>
                            </div>
                            <div class="form-group">
                                <label for="tglSelesai">Tanggal Selesai</label>
                                <input type="datetime-local" class="form-control" id="tglSelesai">
                            </div>
                            <div class="form-group">
                                <label for="pimpinanProyek">Pimpinan Proyek</label>
                                <input type="text" class="form-control" id="pimpinanProyek" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveProyekBtn" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table mt-4" id="proyekTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Proyek</th>
                    <th scope="col">Client</th>
                    <th scope="col">Tanggal Mulai</th>
                    <th scope="col">Tanggal Selesai</th>
                    <th scope="col">Pimpinan Proyek</th>
                    <th scope="col">Keterangan</th>
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

        function fetchProyekData() {
            fetch("http://localhost:8080/api/proyek")
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector("#proyekTable tbody");
                    tableBody.innerHTML = ""; 
                    data.forEach(proyek => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <th scope="row">${proyek.id}</th>
                            <td>${proyek.namaProyek}</td>
                            <td>${proyek.client}</td>
                            <td>${new Date(proyek.tglMulai).toLocaleString()}</td>
                            <td>${proyek.tglSelesai ? new Date(proyek.tglSelesai).toLocaleString() : ''}</td>
                            <td>${proyek.pimpinanProyek}</td>
                            <td>${proyek.keterangan}</td>
                            <td><button type="button" class="btn btn-outline-warning edit-btn" data-id="${proyek.id}"><i class="bi bi-pencil-square"></i></button></td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-delete" data-id="${proyek.id}">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function showModalForEdit(id) {
            fetch(`http://localhost:8080/api/proyek/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("proyekId").value = data.id;
                    document.getElementById("namaProyek").value = data.namaProyek;
                    document.getElementById("client").value = data.client;
                    document.getElementById("tglMulai").value = new Date(data.tglMulai).toISOString().slice(0, 16);
                    document.getElementById("tglSelesai").value = data.tglSelesai ? new Date(data.tglSelesai).toISOString().slice(0, 16) : '';
                    document.getElementById("pimpinanProyek").value = data.pimpinanProyek;
                    document.getElementById("keterangan").value = data.keterangan;
                    document.querySelector("#exampleModal .modal-title").textContent = "Edit Proyek"; 
                    $('#exampleModal').modal('show');
                })
                .catch(error => console.error("Error fetching proyek data:", error));
        }

        document.getElementById("saveProyekBtn").addEventListener("click", function() {
            const proyekId = document.getElementById("proyekId").value;
            const proyekData = {
                namaProyek: document.getElementById("namaProyek").value,
                client: document.getElementById("client").value,
                tglMulai: document.getElementById("tglMulai").value,
                tglSelesai: document.getElementById("tglSelesai").value,
                pimpinanProyek: document.getElementById("pimpinanProyek").value,
                keterangan: document.getElementById("keterangan").value
            };

            const method = proyekId ? "PUT" : "POST";
            const url = proyekId ? `http://localhost:8080/api/proyek/${proyekId}` : "http://localhost:8080/api/proyek";

            fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(proyekData)
            })
            .then(response => response.json())
            .then(data => {
                console.log("Success:", data);
                toastr.success('Data berhasil disimpan!', 'Sukses');
                $('#exampleModal').modal('hide');
                fetchProyekData(); // Refresh tabel setelah data disimpan
            })
            .catch((error) => {
                console.error("Error:", error);
                toastr.error('Gagal menyimpan data!', 'Error');
            });
        });

        // Event listener untuk tombol hapus
        document.addEventListener("click", function(event) {
            if (event.target.matches(".btn-delete")) {
                const id = event.target.getAttribute("data-id");
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    fetch(`http://localhost:8080/api/proyek/${id}`, {
                        method: "DELETE"
                    })
                    .then(response => {
                        if (response.ok) {
                            toastr.success('Data berhasil dihapus!', 'Sukses');
                            fetchProyekData(); // Refresh tabel setelah data dihapus
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
            } else if (event.target.matches(".edit-btn")) {
                const id = event.target.getAttribute("data-id");
                showModalForEdit(id);
            }
        });

        // Fetch initial data when page loads
        document.addEventListener("DOMContentLoaded", fetchProyekData);

        // Refresh tabel setelah modal ditutup
        $('#exampleModal').on('hidden.bs.modal', function () {
            fetchProyekData();
        });
    </script>
</body>
</html>
