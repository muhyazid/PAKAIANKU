<div class="modal fade" id="viewProdukModal-{{ $product->id }}" tabindex="-1" aria-labelledby="viewProdukModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProdukModalLabel">View Produk - {{ $product->nama_produk }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="Gambar"
                            style="width: 150px; height: 150px; border: 1px solid #ccc; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Name:</strong> {{ $product->nama_produk }}</p>
                        <p><strong>Kategori:</strong> {{ $product->kategori }}</p>
                        <p><strong>Deskripsi:</strong> {{ $product->deskripsi }}</p>
                        <p><strong>Harga:</strong> Rp {{ $product->price }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
