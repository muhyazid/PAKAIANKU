<!-- Modal View suppliers -->
<div class="modal fade" id="viewSuppliersModal-{{ $suppliers->id }}" tabindex="-1" role="dialog"
    aria-labelledby="viewSuppliersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-light" id="viewSuppliersModalLabel">View {{ $suppliers->nama }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Bagian Kanan: Informasi -->
                    <div class="col-md-8">
                        <div class="text-light"><strong>Name:</strong> {{ $suppliers->nama }}</div>
                        <div class="text-light"><strong>Kuantitas:</strong> {{ $suppliers->no_tlp }}</div>
                        <div class="text-light"><strong>Satuan:</strong> {{ $suppliers->alamat }}</div>
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
