@foreach ($customers as $customer)
    <div class="modal fade" id="editModal-{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Customer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_customer">Kode Customer</label>
                            <input type="text" class="form-control bg-dark text-white border-light"
                                id="kode_customer" name="kode_customer" value="{{ $customer->kode_customer }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_customer">Nama Customer</label>
                            <input type="text" class="form-control bg-dark text-white border-light"
                                id="nama_customer" name="nama_customer" value="{{ $customer->nama_customer }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control bg-dark text-white border-light" id="alamat" name="alamat" required>{{ $customer->alamat }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No Telp</label>
                            <input type="number" class="form-control bg-dark text-white border-light" id="no_telp"
                                name="no_telp" value="{{ $customer->no_telp }}" required>
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
