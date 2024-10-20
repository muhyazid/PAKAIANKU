<div class="modal fade" id="viewBoMModal-{{ $bom->id }}" tabindex="-1" role="dialog"
    aria-labelledby="viewBoMModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal-size" role="document"> <!-- Tambahkan custom-modal-size -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="viewBoMModalLabel">Detail Bill of Materials (BoM)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white">
                <h5>Nama Produk: {{ $bom->product_name }}</h5>
                <p>Kode Produksi: {{ $bom->production_code }}</p>
                <p>Total Komponen: {{ $bom->components->count() }}</p>

                <h6>Daftar Komponen:</h6>
                <ul>
                    @foreach ($bom->components as $component)
                        <li>
                            {{ $component->material->nama_bahan }} - Jumlah: {{ $component->quantity }}
                            {{ $component->unit }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
