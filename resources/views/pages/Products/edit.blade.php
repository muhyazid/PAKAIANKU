<div class="modal fade" id="editProdukModal-{{ $product->id }}" tabindex="-1" aria-labelledby="editProdukModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <label for="edit_image_{{ $product->id }}">Upload Gambar</label>
                            <div style="margin-bottom: 10px;">
                                <img id="edit-avatar-preview-{{ $product->id }}"
                                    src="{{ asset('storage/' . $product->image_path) }}" alt="Gambar"
                                    style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover;">
                            </div>
                            <input type="file" name="image" id="edit_image_{{ $product->id }}" accept="image/*"
                                style="display: none;">
                            <button type="button" class="btn btn-secondary"
                                onclick="document.getElementById('edit_image_{{ $product->id }}').click();">Browse...</button>
                            <span id="edit-file-name-{{ $product->id }}" style="margin-left: 10px;">No file
                                chosen</span>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="edit_nama_produk">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" id="edit_nama_produk"
                                    value="{{ $product->nama_produk }}" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_kategori">Kategori</label>
                                <input type="text" name="kategori" class="form-control" id="edit_kategori"
                                    value="{{ $product->kategori }}" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" id="edit_deskripsi">{{ $product->deskripsi }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_price">Harga</label>
                                <input type="number" name="price" class="form-control" id="edit_price"
                                    value="{{ $product->price }}">
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
