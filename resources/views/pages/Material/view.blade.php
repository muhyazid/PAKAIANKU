<!-- Modal View Material -->
<div class="modal fade" id="viewMaterialModal-{{ $material->id }}" tabindex="-1" role="dialog"
    aria-labelledby="viewMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-light" id="viewMaterialModalLabel">View {{ $material->nama_bahan }}</h5>
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
                        <div class="text-light"><strong>Name:</strong> {{ $material->nama_bahan }}</div>
                        <div class="text-light"><strong>Kuantitas:</strong> {{ $material->kuantitas }}</div>
                        <div class="text-light"><strong>Satuan:</strong> {{ $material->satuan }}</div>
                        <div class="text-light"><strong>Stock:</strong> {{ $material->stock }}</div>
                        <div class="text-light"><strong>Updated at:</strong>
                            {{ $material->updated_at->format('M d, Y H:i:s') }}</div>
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
    .text-light {
        color: #f8f9fa !important;
        /* Warna teks terang */
    }

    .modal-header .close {
        color: #ffffff;
        /* Tetap menggunakan warna terang untuk tombol close */
    }

    .modal-body img {
        width: 100%;
        height: auto;
    }
</style>
