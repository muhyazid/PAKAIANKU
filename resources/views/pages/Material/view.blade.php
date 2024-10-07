<!-- Modal View Material -->
<div class="modal fade" id="viewMaterialModal-{{ $material->id }}" tabindex="-1" role="dialog"
    aria-labelledby="viewMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMaterialModalLabel">View {{ $material->nama_bahan }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Bagian Kiri: Gambar -->
                    <div class="col-md-4">
                        @if ($material->image)
                            <img src="{{ asset('storage/' . $material->image) }}" alt="Gambar Bahan"
                                style="width: 100%; border-radius: 5px;">
                        @else
                            <div><strong>Gambar:</strong> Tidak ada gambar</div>
                        @endif
                    </div>
                    <!-- Bagian Kanan: Informasi -->
                    <div class="col-md-8">
                        <div><strong>Name:</strong> {{ $material->nama_bahan }}</div>
                        <div><strong>Kuantitas:</strong> {{ $material->kuantitas }}</div>
                        <div><strong>Satuan:</strong> {{ $material->satuan }}</div>
                        <div><strong>Stock:</strong> {{ $material->stock }}</div>
                        <div><strong>Updated at:</strong> {{ $material->updated_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-dialog {
        max-width: 600px;
        /* Ubah sesuai kebutuhan */
    }

    .modal-body img {
        width: 100%;
        /* Sesuaikan gambar dengan lebar modal */
        height: auto;
        /* Biarkan tinggi proporsional */
    }
</style>
