<!-- views/pages/materials/create.blade.php -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" role="dialog" aria-labelledby="addMaterialModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="addMaterialModalLabel">Tambah Material</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_bahan">Nama Bahan</label>
                        <input type="text" class="form-control bg-dark text-white" id="nama_bahan" name="nama_bahan"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="kuantitas">Kuantitas</label>
                        <input type="number" class="form-control bg-dark text-white" id="kuantitas" name="kuantitas"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" class="form-control bg-dark text-white" id="satuan" name="satuan"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" class="form-control bg-dark text-white" id="stock" name="stock"
                            value="0" disabled>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control bg-dark text-white" id="price" name="price"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="image">Gambar</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Material</button>
                </div>
            </form>
        </div>
    </div>
</div>
