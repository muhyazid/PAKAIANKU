<form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid">
        <div class="row">
            <!-- Bagian Kiri -->
            <div class="col-md-4">
                <!-- Image Placeholder -->
                <div class="form-group">
                    <label for="image">Upload Gambar</label>
                    <!-- Placeholder Gambar Kotak -->
                    <div id="image-container"
                        style="width: 150px; height: 150px; position: relative; border: 1px solid #ccc; background-color: #f8f8f8; overflow: hidden;">
                        <!-- Gambar Preview -->
                        <img id="preview-image" src="#" alt="Gambar Bahan"
                            style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <!-- Ikon Kamera -->
                        <div id="camera-icon"
                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 24px; cursor: pointer;">
                            <i class="fa fa-camera"></i>
                        </div>
                        <!-- Input File (Hidden) -->
                        <input type="file" name="image" id="image" accept="image/*" style="display: none;">
                    </div>
                </div>

                <!-- Checkbox Options -->
                <div class="form-group">
                    <label for="can_be_sold">Can be sold</label>
                    <input type="checkbox" name="can_be_sold" id="can_be_sold">
                </div>
                <div class="form-group">
                    <label for="can_be_purchased">Can be purchased</label>
                    <input type="checkbox" name="can_be_purchased" id="can_be_purchased">
                </div>
            </div>
            <!-- Bagian Kanan -->
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nama_bahan">Nama Bahan Baku</label>
                    <input type="text" name="nama_bahan" class="form-control" id="nama_bahan" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="kuantitas" class="form-control" id="quantity" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" class="form-control" id="price">
                </div>

                <!-- Tambahkan input untuk kolom satuan -->
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" class="form-control" id="satuan" required>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>

    <style>
        #image-container {
            border-radius: 8px;
            /* Membuat sudut kotak sedikit melengkung (opsional) */
            cursor: pointer;
            /* Mengubah kursor menjadi pointer saat hover */
        }

        #camera-icon {
            cursor: pointer;
            /* Mengubah kursor menjadi pointer saat hover */
        }

        #camera-icon i {
            font-size: 2em;
            /* Ukuran ikon kamera */
        }
    </style>

    <script>
        // Ketika ikon kamera di-klik, buka file input
        document.getElementById('camera-icon').addEventListener('click', function() {
            document.getElementById('image').click();
        });

        // Ketika file gambar dipilih, tampilkan preview gambar
        document.getElementById('image').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const previewImage = document.getElementById('preview-image');
                previewImage.src = URL.createObjectURL(file);
                previewImage.style.display = 'block';
                document.getElementById('camera-icon').style.display =
                    'none'; // Sembunyikan ikon kamera setelah gambar diunggah
            }
        });
    </script>

</form>
