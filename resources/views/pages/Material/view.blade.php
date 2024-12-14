<!-- views/pages/materials/view.blade.php -->
@foreach ($materials as $material)
    <div class="modal fade" id="viewMaterialModal-{{ $material->id }}" tabindex="-1" role="dialog"
        aria-labelledby="viewMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMaterialModalLabel">Detail Material</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        @if ($material->image)
                            <img src="{{ asset('storage/' . $material->image) }}" alt="{{ $material->nama_bahan }}"
                                class="img-fluid rounded mb-3">
                        @else
                            Tidak ada gambar
                        @endif
                    </div>
                    <p><strong>Nama:</strong> {{ $material->nama_bahan }}</p>
                    <p><strong>Kuantitas:</strong> {{ $material->kuantitas }}</p>
                    <p><strong>Satuan:</strong> {{ $material->satuan }}</p>
                    <p><strong>Stock:</strong> {{ $material->stock }}</p>
                    <p><strong>Harga:</strong> {{ number_format($material->price, 2, ',', '.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
