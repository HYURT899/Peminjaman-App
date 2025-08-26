<!-- Modal Edit Asset -->
<div class="modal fade" id="editRowModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editAssetModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssetModalLabel-{{ $item->id }}">Edit Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.assets.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Kode Asset</label>
                        <input type="text" name="kode_asset" class="form-control" value="{{ $item->kode_asset }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Asset</label>
                        <input type="text" name="nama_asset" class="form-control" value="{{ $item->nama_asset }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Gambar</label><br>
                        @if ($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar" width="80" class="mb-2">
                        @endif
                        <input type="file" name="gambar" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control">{{ $item->deskripsi }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ $item->stok }}" required min="1">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
