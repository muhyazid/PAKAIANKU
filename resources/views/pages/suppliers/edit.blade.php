<!-- views/pages/suppliers/edit.blade.php -->
@foreach ($suppliers as $supplier)
    <div class="modal fade" id="editSupplierModal-{{ $supplier->id }}" tabindex="-1" role="dialog"
        aria-labelledby="editSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Supplier</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Supplier</label>
                            <input type="text" class="form-control bg-dark text-white" id="nama" name="nama"
                                value="{{ $supplier->nama }}" required>
                        </div>
                        <div class="form-group">
                            <label for="no_tlp">No Telepon</label>
                            <input type="text" class="form-control bg-dark text-white" id="no_tlp" name="no_tlp"
                                value="{{ $supplier->no_tlp }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control bg-dark text-white" id="alamat" name="alamat" required>{{ $supplier->alamat }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="material_id">Material</label>
                            <select class="form-control bg-dark text-white" id="material_id" name="material_id">
                                <option value="" {{ !$supplier->material ? 'selected' : '' }}>Tidak ada material
                                </option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ $supplier->material && $supplier->material->id == $material->id ? 'selected' : '' }}>
                                        {{ $material->nama_bahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
