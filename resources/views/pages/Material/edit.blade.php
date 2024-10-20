<!-- Modal Edit Material -->
<div class="modal fade" id="editMaterialModal-{{ $material->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editMaterialModalLabel-{{ $material->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-light" id="editMaterialModalLabel-{{ $material->id }}">Edit Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('materials.update', $material->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="container-fluid">
                        <div class="row">
                            <!-- Bagian Kiri: Upload Gambar -->
                            <div class="col-md-4 text-center">
                                <label for="image" class="text-light">Upload Gambar</label>
                                <div style="margin-bottom: 10px;">
                                    @if ($material->image)
                                        <img src="{{ asset('storage/' . $material->image) }}" alt="Gambar"
                                            style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover;">
                                    @else
                                        <img id="material-avatar-preview-{{ $material->id }}" src="#"
                                            alt="Gambar"
                                            style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover;">
                                    @endif
                                </div>
                                <!-- Input untuk Pilih File Gambar -->
                                <input type="file" name="image" id="material-image-{{ $material->id }}"
                                    accept="image/*" style="display: none;">
                                <!-- Tombol untuk Membuka Input File -->
                                <button type="button" class="btn btn-secondary"
                                    onclick="document.getElementById('material-image-{{ $material->id }}').click();">Browse...</button>
                                <span id="material-file-name-{{ $material->id }}" style="margin-left: 10px;">No file
                                    chosen</span>
                            </div>

                            <!-- Bagian Kanan: Input Form Lainnya -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama_bahan" class="text-light">Nama Bahan Baku</label>
                                    <input type="text" name="nama_bahan" class="form-control" id="nama_bahan"
                                        value="{{ $material->nama_bahan }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="quantity" class="text-light">Kuantitas</label>
                                    <input type="number" name="kuantitas" class="form-control" id="quantity"
                                        value="{{ $material->kuantitas }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="price" class="text-light">Price</label>
                                    <input type="number" name="price" class="form-control" id="price"
                                        value="{{ $material->price }}">
                                </div>
                                <div class="form-group">
                                    <label for="satuan" class="text-light">Satuan</label>
                                    <input type="text" name="satuan" class="form-control" id="satuan"
                                        value="{{ $material->satuan }}" required>
                                </div>
                            </div>
                        </div>
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

<!-- Tambahkan CSS untuk teks terang -->
<style>
    .text-light {
        color: #f8f9fa !important;
        /* Warna teks terang */
    }

    .modal-header .close {
        color: #ffffff;
        /* Tetap menggunakan warna terang untuk tombol close */
    }

    .modal-title {
        color: #f8f9fa !important;
        /* Warna terang untuk judul modal */
    }

    .modal-body img {
        width: 100%;
        height: auto;
    }

    label {
        color: #f8f9fa;
        /* Warna label form menjadi terang */
    }
</style>
