<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('update.data', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $data->id }}">
                @method('PUT')
                <div class="modal-body">
                    <!-- Error Alert -->
                    <div id="errorAlert" class="alert alert-danger" style="display: none;">
                        <strong>Validation Error!</strong>
                        <ul id="errorList" class="mb-0 mt-2"></ul>
                    </div>

                    <div class="mb-3 row">
                        <label for="nama_produk" class="col-sm-2 col-form-label">Nama Produk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_produk"
                                value="{{ $data->nama_produk }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="Pria" {{ $data->kategori == 'Pria' ? 'selected' : '' }}>Pria</option>
                                <option value="Wanita" {{ $data->kategori == 'Wanita' ? 'selected' : '' }}>Wanita
                                </option>
                                <option value="Anak-anak" {{ $data->kategori == 'Anak-anak' ? 'selected' : '' }}>
                                    Anak-anak</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tipe" class="col-sm-2 col-form-label">Tipe</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="tipe">
                                <option value="">Pilih Tipe</option>
                                <option value="Baju" {{ $data->tipe == 'Baju' ? 'selected' : '' }}>Baju</option>
                                <option value="Celana" {{ $data->tipe == 'Celana' ? 'selected' : '' }}>Celana</option>
                                <option value="Rok" {{ $data->tipe == 'Rok' ? 'selected' : '' }}>Rok</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="harga" value="{{ $data->harga }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="stok" value="{{ $data->stok }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="old_foto" value="{{ $data->foto }}">
                            <img src="{{ asset('storage/produk/' . $data->foto) }}" class="img-preview" alt=""
                                style="height:100px; width: 100px; margin-bottom: 10px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px;">
                            <input type="file" class="form-control" accept=".png,.jpg,.jpeg" name="foto" id="inputFoto"
                                onchange="previewImg()">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImg() {
        const fotoInput = document.querySelector('#inputFoto');
        const imgPreview = document.querySelector('.img-preview');

        if (fotoInput.files && fotoInput.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
            };
            
            reader.readAsDataURL(fotoInput.files[0]);
        }
    }
</script>