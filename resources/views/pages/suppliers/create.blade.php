<form action="{{ route('suppliers.store') }}" method="POST">
    @csrf
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h5 class="modal-title mb-3">Tambah Supplier Baru</h5>
                <div class="card p-3">
                    <div class="form-group">
                        <label for="nama">Nama Supplier</label>
                        <input type="text" name="nama" class="form-control" id="nama"
                            placeholder="Masukkan Nama Supplier" required>
                    </div>

                    <div class="form-group">
                        <label for="no_tlp">No Telepon</label>
                        <input type="text" name="no_tlp" class="form-control" id="no_tlp"
                            placeholder="Masukkan No Telepon" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" class="form-control" id="alamat" rows="3" placeholder="Masukkan Alamat"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="material_id">Material yang Disediakan</label>
                        <select name="material_id" class="form-control" id="material_id" required>
                            <option value="">Pilih Material</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
