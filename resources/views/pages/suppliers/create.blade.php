<form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid">
        <div class="row">
            <!-- Bagian Kiri: Upload Gambar -->
            {{-- <div class="col-md-4 text-center">
                {{-- <label for="image">Upload Gambar</label> --}}
            {{-- <div style="margin-bottom: 10px;">
                    <!-- Kotak Pratinjau Avatar -->
                    <img id="material-avatar-preview" src="#" alt="Gambar"
                        style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover; display: block; margin: 0 auto;">
                </div>
                <!-- Input untuk Pilih File Gambar -->
                <input type="file" name="image" id="material-image" accept="image/*" style="display: none;"
                    required>
                <!-- Tombol untuk Membuka Input File -->
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('material-image').click();">Browse...</button>
                <span id="material-file-name" style="margin-left: 10px;">No file chosen</span>
            </div> --}}

            <!-- Bagian Kanan: Input Form Lainnya -->
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nama_bahan">Nama Suppliers</label>
                    <input type="text" name="nama" class="form-control" id="nama" required>
                </div>
                <div class="form-group">
                    <label for="quantity">No Phone</label>
                    <input type="number" name="no_tlp" class="form-control" id="no_tlp" required>
                </div>
                <div class="form-group">
                    <label for="price">Alamat</label>
                    <input type="text" name="alamat" class="form-control" id="alamat">
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Simpan -->
    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>

    <!-- Script untuk Pratinjau Gambar -->
    {{-- <script>
        // Ketika input file berubah, tampilkan pratinjau gambar
        document.getElementById('material-image').onchange = function(event) {
            const [file] = event.target.files;
            if (file) {
                // Tampilkan pratinjau avatar
                const preview = document.getElementById('material-avatar-preview');
                preview.src = URL.createObjectURL(file);

                // Tampilkan nama file yang dipilih
                document.getElementById('material-file-name').textContent = file.name;
            }
        };
    </script> --}}
</form>
