@foreach ($customers as $customer)
    <div class="modal fade" id="viewModal-{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detail Customer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body stacked-info">
                    <p><strong>Kode Customer:</strong> {{ $customer->kode_customer }}</p>
                    <p><strong>Nama Customer:</strong> {{ $customer->nama_customer }}</p>
                    <p><strong>Alamat:</strong> {{ $customer->alamat }}</p>
                    <p><strong>No Telp:</strong> {{ $customer->no_telp }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
