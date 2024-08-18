<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek dan Lokasi</title>
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
        <h2>Daftar Proyek dan Lokasi</h2>
        <button type="button" class="btn btn-outline-success mb-3" data-toggle="modal" data-target="#addModal">
            <i class="bi bi-plus-circle-fill"></i> Tambah Data
        </button>
        <table class="table mt-4" id="proyekLokasiTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID Proyek</th>
                    <th scope="col">ID Lokasi</th>
                    <th scope="col">Nama Proyek</th>
                    <th scope="col">Nama Lokasi</th>
                    <th scope="col">Detail</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan dimuat di sini -->
            </tbody>
        </table>
    </div>

    <!-- Modal for detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Proyek dan Lokasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Proyek</h4>
                    <p><strong>Nama Proyek:</strong> <span id="modalNamaProyek"></span></p>
                    <p><strong>Client:</strong> <span id="modalClient"></span></p>
                    <p><strong>Tanggal Mulai:</strong> <span id="modalTglMulai"></span></p>
                    <p><strong>Tanggal Selesai:</strong> <span id="modalTglSelesai"></span></p>
                    <p><strong>Pimpinan Proyek:</strong> <span id="modalPimpinan"></span></p>
                    <p><strong>Keterangan:</strong> <span id="modalKeterangan"></span></p>
                    <p><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>

                    <h4>Lokasi</h4>
                    <p><strong>Nama Lokasi:</strong> <span id="modalLokasiNama"></span></p>
                    <p><strong>Negara:</strong> <span id="modalLokasiNegara"></span></p>
                    <p><strong>Provinsi:</strong> <span id="modalLokasiProvinsi"></span></p>
                    <p><strong>Kota:</strong> <span id="modalLokasiKota"></span></p>
                    <p><strong>Created At:</strong> <span id="modalLokasiCreatedAt"></span></p>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding data -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Proyek dan Lokasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="form-group">
                            <label for="proyekId">ID Proyek</label>
                            <input type="number" class="form-control" id="proyekId" required>
                        </div>
                        <div class="form-group">
                            <label for="lokasiId">ID Lokasi</label>
                            <input type="number" class="form-control" id="lokasiId" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveBtn" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
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

        function fetchProyekLokasiData() {
            fetch("http://localhost:8080/api/proyek-lokasi")
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        const tableBody = document.querySelector("#proyekLokasiTable tbody");
                        tableBody.innerHTML = ""; // Kosongkan tabel sebelum mengisi

                        data.forEach(item => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${item.proyek.id || 'N/A'}</td>
                                <td>${item.lokasi.id || 'N/A'}</td>
                                <td>${item.proyek.namaProyek || 'N/A'}</td>
                                <td>${item.lokasi.namaLokasi || 'N/A'}</td>
                                <td><button type="button" class="btn btn-outline-info btn-detail" data-proyek-id="${item.proyek.id}" data-lokasi-id="${item.lokasi.id}">Detail</button></td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        console.error('Data format is not an array:', data);
                        toastr.error('Data format error!', 'Error');
                    }
                })
                .catch(error => {
                    console.error("Error fetching proyek-lokasi data:", error);
                    toastr.error('Gagal mengambil data proyek-lokasi!', 'Error');
                });
        }

        function showModalForDetail(proyekId, lokasiId) {
            // Fetch data for proyek
            fetch(`http://localhost:8080/api/proyek/${proyekId}`)
                .then(response => response.json())
                .then(proyekData => {
                    // Fetch data for lokasi
                    fetch(`http://localhost:8080/api/lokasi/${lokasiId}`)
                        .then(response => response.json())
                        .then(lokasiData => {
                            document.getElementById("modalNamaProyek").textContent = proyekData.namaProyek;
                            document.getElementById("modalClient").textContent = proyekData.client;
                            document.getElementById("modalTglMulai").textContent = new Date(proyekData.tglMulai).toLocaleString();
                            document.getElementById("modalTglSelesai").textContent = proyekData.tglSelesai ? new Date(proyekData.tglSelesai).toLocaleString() : '';
                            document.getElementById("modalPimpinan").textContent = proyekData.pimpinanProyek;
                            document.getElementById("modalKeterangan").textContent = proyekData.keterangan;
                            document.getElementById("modalCreatedAt").textContent = new Date(proyekData.createdAt).toLocaleString();

                            document.getElementById("modalLokasiNama").textContent = lokasiData.namaLokasi;
                            document.getElementById("modalLokasiNegara").textContent = lokasiData.negara;
                            document.getElementById("modalLokasiProvinsi").textContent = lokasiData.provinsi;
                            document.getElementById("modalLokasiKota").textContent = lokasiData.kota;
                            document.getElementById("modalLokasiCreatedAt").textContent = new Date(lokasiData.createdAt).toLocaleString();

                            $('#detailModal').modal('show');
                        })
                        .catch(error => {
                            console.error("Error fetching lokasi data:", error);
                            toastr.error('Gagal mengambil detail lokasi!', 'Error');
                        });
                })
                .catch(error => {
                    console.error("Error fetching proyek data:", error);
                    toastr.error('Gagal mengambil detail proyek!', 'Error');
                });
        }

		function addProyekLokasi(proyekId, lokasiId) {
    fetch("http://localhost:8080/api/proyek-lokasi", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            proyek: { id: proyekId },
            lokasi: { id: lokasiId }
        }),
    })
    .then(response => {
        if (!response.ok) {
            // Jika respons tidak OK, anggap sebagai kesalahan
            return response.text().then(text => {
                throw new Error(text); // Lemparkan teks sebagai kesalahan
            });
        }
        return response.json(); // Parsing respons sebagai JSON jika OK
    })
    .then(data => {
        console.log("Raw server response:", data); // Log data yang diterima
        if (data && typeof data === 'object') {
            toastr.success('Data berhasil ditambahkan!', 'Success');
            $('#addModal').modal('hide');
            fetchProyekLokasiData(); // Memuat ulang data
        } else {
            throw new Error('Format respons tidak sesuai');
        }
    })
    .catch(error => {
        console.error("Error posting data:", error);
        toastr.error('Gagal menambahkan data! ' + error.message, 'Error');
    });
}




document.addEventListener("click", function(event) {
    if (event.target.matches(".btn-detail")) {
        const proyekId = event.target.getAttribute("data-proyek-id");
        const lokasiId = event.target.getAttribute("data-lokasi-id");
        showModalForDetail(proyekId, lokasiId);
    } else if (event.target.matches("#saveBtn")) {
        const proyekId = document.getElementById("proyekId").value;
        const lokasiId = document.getElementById("lokasiId").value;
        if (proyekId && lokasiId) {
            addProyekLokasi(proyekId, lokasiId);
        } else {
            toastr.warning('ID Proyek dan ID Lokasi harus diisi!', 'Warning');
        }
    }
});

// Fetch initial data when page loads
document.addEventListener("DOMContentLoaded", fetchProyekLokasiData);

    </script>
</body>
</html>
