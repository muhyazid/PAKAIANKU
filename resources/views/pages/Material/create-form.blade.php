<form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid">
        <div class="row">
            <!-- Bagian Kiri: Upload Gambar -->
            <div class="col-md-4 text-center">
                <label for="image">Upload Gambar</label>
                <div style="margin-bottom: 10px;">
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
            </div>

            <!-- Bagian Kanan: Input Form Lainnya -->
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nama_bahan">Nama Bahan Baku</label>
                    <input type="text" name="nama_bahan" class="form-control" id="nama_bahan" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Kuantitas</label>
                    <input type="number" name="kuantitas" class="form-control" id="quantity" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" class="form-control" id="price">
                </div>
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" class="form-control" id="satuan" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Simpan -->
    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>

    <!-- Script untuk Pratinjau Gambar -->
    <script>
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
    </script>
</form>
