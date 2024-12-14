<div class="modal fade" id="tambahProdukModal" tabindex="-1" aria-labelledby="tambahProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md"> <!-- Ganti dari modal-lg ke modal-md -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahProdukModalLabel">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center mb-3">
                        <label for="image" class="form-label">Upload Gambar</label>
                        <div style="margin-bottom: 10px;">
                            <img id="avatar-preview" src="#" alt="Pratinjau Gambar"
                                style="width: 120px; height: 120px; border: 1px solid #ccc; object-fit: cover; display: block; margin: 0 auto;">
                        </div>
                        <input type="file" name="image" id="image" accept="image/*" style="display: none;"
                            required>
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="document.getElementById('image').click();">Browse...</button>
                        <span id="file-name" class="d-block mt-2 text-muted">No file chosen</span>
                    </div>

                    <!-- Input Form -->
                    <div class="form-group mb-3">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" id="nama_produk" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="kategori">Kategori</label>
                        <input type="text" name="kategori" class="form-control" id="kategori" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="price">Harga</label>
                        <input type="number" name="price" class="form-control" id="price"
                            value="{{ old('price') }}" required>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Pratinjau Gambar -->
<script>
    document.getElementById('image').onchange = function(event) {
        const [file] = event.target.files; // Ambil file yang dipilih
        if (file) {
            const preview = document.getElementById('avatar-preview'); // Dapatkan elemen img pratinjau
            preview.src = URL.createObjectURL(file); // Tampilkan gambar yang dipilih di img
        }
    };
</script>
