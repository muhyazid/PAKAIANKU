@section('content')
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">Gambar Produk</label>
            <input type="file" name="image" class="form-control" id="image" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="nama_produk">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" id="nama_produk" required>
        </div>
        <div class="form-group">
            <label for="kategori">Kategori</label>
            <input type="text" name="kategori" class="form-control" id="kategori" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection
