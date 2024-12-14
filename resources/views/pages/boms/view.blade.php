<div class="modal fade" id="viewBoMModal-{{ $bom->id }}" tabindex="-1" role="dialog"
    aria-labelledby="viewBoMModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="viewBoMModalLabel">Detail BoM</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Kode Produksi:</strong> {{ $bom->production_code }}</p>
                <p><strong>Nama Produk:</strong>
                    {{ $bom->product->nama_produk ?? 'Produk tidak ditemukan' }}</p>
                <h6>Komponen:</h6>
                <ul>
                    @foreach ($bom->materials as $material)
                        <li>{{ $material->nama_bahan }} - {{ $material->pivot->quantity }}
                            {{ $material->pivot->unit }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
