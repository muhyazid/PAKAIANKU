<!-- Modal Edit suppliers -->
<div class="modal fade" id="editSuppliersModal-{{ $suppliers->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editSuppliersModalLabel-{{ $suppliers->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-light" id="editSuppliersModalLabel-{{ $suppliers->id }}">Edit suppliers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('suppliers.update', $suppliers->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="container-fluid">
                        <div class="row">
                            <!-- Bagian Kiri: Upload Gambar -->
                            {{-- <div class="col-md-4 text-center">
                                <label for="image" class="text-light">Upload Gambar</label>
                                <div style="margin-bottom: 10px;">
                                    @if ($suppliers->image)
                                        <img src="{{ asset('storage/' . $suppliers->image) }}" alt="Gambar"
                                            style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover;">
                                    @else
                                        <img id="suppliers-avatar-preview-{{ $suppliers->id }}" src="#"
                                            alt="Gambar"
                                            style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover;">
                                    @endif
                                </div>
                                <!-- Input untuk Pilih File Gambar -->
                                <input type="file" name="image" id="suppliers-image-{{ $suppliers->id }}"
                                    accept="image/*" style="display: none;">
                                <!-- Tombol untuk Membuka Input File -->
                                <button type="button" class="btn btn-secondary"
                                    onclick="document.getElementById('suppliers-image-{{ $suppliers->id }}').click();">Browse...</button>
                                <span id="suppliers-file-name-{{ $suppliers->id }}" style="margin-left: 10px;">No file
                                    chosen</span>
                            </div> --}}

                            <!-- Bagian Kanan: Input Form Lainnya -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama" class="text-light">Nama Suppliers</label>
                                    <input type="text" name="nama" class="form-control" id="nama"
                                        value="{{ $suppliers->nama_bahan }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_tlp" class="text-light">No Phone</label>
                                    <input type="number" name="no_tlp" class="form-control" id="no_tlp"
                                        value="{{ $suppliers->no_tlp }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class="text-light">Alamat</label>
                                    <input type="Text" name="alamat" class="form-control" id="alamat"
                                        value="{{ $suppliers->alamat }}">
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
