<div class="modal fade" id="editProdukModal-{{ $product->id }}" tabindex="-1" aria-labelledby="editProdukModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md"> <!-- Ganti dari modal-lg ke modal-md untuk membuat modal lebih kecil -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProdukModalLabel">Edit Produk - {{ $product->nama_produk }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Edit Produk -->
                <form action="{{ route('products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="text-center mb-3">
                        <label for="edit_image_{{ $product->id }}" class="form-label">Upload Gambar</label>
                        <div style="margin-bottom: 10px;">
                            <img id="edit-avatar-preview-{{ $product->id }}"
                                src="{{ asset('storage/' . $product->image_path) }}" alt="Gambar Produk"
                                style="width: 120px; height: 120px; border: 1px solid #ccc; object-fit: cover; display: block; margin: 0 auto;">
                        </div>
                        <input type="file" name="image" id="edit_image_{{ $product->id }}" accept="image/*"
                            style="display: none;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="document.getElementById('edit_image_{{ $product->id }}').click();">Browse...</button>
                        <span id="edit-file-name-{{ $product->id }}" class="d-block mt-2 text-muted">No file
                            chosen</span>
                    </div>

                    <!-- Input Fields -->
                    <div class="form-group mb-3">
                        <label for="edit_nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" id="edit_nama_produk"
                            value="{{ $product->nama_produk }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_kategori">Kategori</label>
                        <input type="text" name="kategori" class="form-control" id="edit_kategori"
                            value="{{ $product->kategori }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="edit_deskripsi" rows="3">{{ $product->deskripsi }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_price">Harga</label>
                        <input type="number" name="price" class="form-control" id="edit_price"
                            value="{{ $product->price }}" required>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Pratinjau Gambar -->
<script>
    document.getElementById('edit_image_{{ $product->id }}').onchange = function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('edit-avatar-preview-{{ $product->id }}');
            preview.src = URL.createObjectURL(file);
        }
    };
</script>
