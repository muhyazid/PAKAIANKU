<!-- views/pages/suppliers/view.blade.php -->
@foreach ($suppliers as $supplier)
    <div class="modal fade" id="viewSupplierModal-{{ $supplier->id }}" tabindex="-1" role="dialog"
        aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSupplierModalLabel">Detail Supplier</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama Supplier:</strong> {{ $supplier->nama }}</p>
                    <p><strong>No Telepon:</strong> {{ $supplier->no_tlp }}</p>
                    <p><strong>Alamat:</strong> {{ $supplier->alamat }}</p>
                    <p><strong>Material:</strong>
                        {{ $supplier->material ? $supplier->material->nama_bahan : 'Tidak ada material' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
