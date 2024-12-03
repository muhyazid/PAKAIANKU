@extends('layouts.master')

@section('title', 'Daftar BoM')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Daftar Bill of Materials (BoM)</h3>
        <!-- Tombol untuk memunculkan modal Tambah BoM -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createBoMModal">
            Tambah BoM
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-dark">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Kode Produksi</th>
                            <th>Jumlah Produksi</th>
                            <th>Komponen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boms as $bom)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bom->product->nama_produk ?? 'Produk tidak ditemukan' }}</td>
                                <td>{{ $bom->production_code }}</td>
                                <td>{{ $bom->quantity }}</td>
                                <td>
                                    <ul>
                                        @foreach ($bom->materials as $material)
                                            <li>{{ $material->nama_bahan }} - {{ $material->pivot->quantity }}
                                                {{ $material->pivot->unit }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <!-- Tombol View (Lihat) -->
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#viewBoMModal-{{ $bom->id }}" title="View">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('boms.edit', $bom->id) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Tombol Report -->
                                    <a href="{{ route('boms.report', $bom->id) }}" class="btn btn-success"
                                        title="Unduh Laporan">
                                        <i class="fas fa-file-pdf"></i> Laporan
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('boms.destroy', $bom->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus"
                                            onclick="return confirm('Apakah kamu yakin ingin menghapus BoM ini?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal untuk Melihat Detail BoM -->
                            <div class="modal fade" id="viewBoMModal-{{ $bom->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="viewBoMModalLabel-{{ $bom->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewBoMModalLabel-{{ $bom->id }}">Detail BoM
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Nama Produk: {{ $bom->product->nama_produk ?? 'Produk tidak ditemukan' }}
                                            </h6>
                                            <p>Kode Produksi: {{ $bom->production_code }}</p>
                                            <p>Jumlah Produksi: {{ $bom->quantity }}</p>

                                            <h6>Komponen:</h6>
                                            <ul>
                                                @foreach ($bom->materials as $material)
                                                    <li>{{ $material->nama_bahan }} - {{ $material->pivot->quantity }}
                                                        {{ $material->pivot->unit }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk Menambahkan BoM -->
    <div class="modal fade" id="createBoMModal" tabindex="-1" role="dialog" aria-labelledby="createBoMModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBoMModalLabel">Tambah Bill of Materials (BoM)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('boms.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="product_id">Nama Produk</label>
                            <select name="product_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="production_code">Kode BoM</label>
                            <input type="text" class="form-control" name="production_code" id="production_code" readonly>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Jumlah Produksi</label>
                            <input type="number" class="form-control" name="quantity" required>
                        </div>
                        <h4>Komponen / Material</h4>
                        <table class="table table-bordered" id="components-table">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="materials[0][material_id]" class="form-control" required>
                                            <option value="">Pilih Material</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="materials[0][quantity]" class="form-control" required>
                                    </td>
                                    <td>
                                        <select name="materials[0][unit]" class="form-control">
                                            <option value="gram">gram</option>
                                            <option value="meter">meter</option>
                                            <option value="pcs">pcs</option>
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-success btn-add-row">+</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Fungsi untuk auto-generate kode
        function initializeBoMCode() {
            fetch('{{ route('boms.nextCode') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('production_code').value = data.code;
                })
                .catch(error => console.error('Error:', error));
        }

        // Initialize kode saat modal dibuka
        document.getElementById('createBoMModal').addEventListener('show.bs.modal', function(e) {
            initializeBoMCode();
        });

        // Fungsi untuk menambah baris material
        let rowCount = 1;
        document.querySelector('.btn-add-row').addEventListener('click', function() {
            let table = document.querySelector('#components-table tbody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="materials[${rowCount}][material_id]" class="form-control" required>
                        <option value="">Pilih Material</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->nama_bahan }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="materials[${rowCount}][quantity]" class="form-control" required></td>
                <td>
                    <select name="materials[${rowCount}][unit]" class="form-control">
                        <option value="gram">gram</option>
                        <option value="meter">meter</option>
                        <option value="pcs">pcs</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-remove-row">-</button></td>
            `;
            table.appendChild(newRow);
            rowCount++;
        });

        // Event delegation untuk tombol remove
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
