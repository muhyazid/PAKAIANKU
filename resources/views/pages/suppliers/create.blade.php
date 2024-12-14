<!-- views/pages/suppliers/create.blade.php -->
<div class="modal fade" id="addSuppliersModal" tabindex="-1" role="dialog" aria-labelledby="addSuppliersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="addSuppliersModalLabel">Tambah Supplier</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Supplier</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="no_tlp">No Telepon</label>
                        <input type="text" class="form-control" id="no_tlp" name="no_tlp" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="material_id">Material</label>
                        <select class="form-control" id="material_id" name="material_id">
                            <option value="">Pilih Material</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
