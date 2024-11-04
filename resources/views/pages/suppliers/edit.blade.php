<div class="modal fade" id="editSuppliersModal-{{ $supplier->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama" class="form-control" value="{{ $supplier->nama }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>No Telepon</label>
                        <input type="text" name="no_tlp" class="form-control" value="{{ $supplier->no_tlp }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $supplier->alamat }}">
                    </div>

                    <div class="form-group">
                        <label>Material</label>
                        <select name="material_id" class="form-control" required>
                            <option value="">Pilih Material</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}"
                                    {{ $supplier->material_id == $material->id ? 'selected' : '' }}>
                                    {{ $material->nama_bahan }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>

            </div>
        </div>
    </div>
</div>
