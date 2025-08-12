<div class="modal fade" id="modalPeminjaman" tabindex="-1" aria-labelledby="modalPeminjamanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalPeminjamanLabel">Tambah Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="/peminjaman/store" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="namaPeminjam" class="form-label">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" class="form-control" id="namaPeminjam"
                            placeholder="Masukkan nama" required>
                    </div>

                    <div class="mb-3">
                        <label for="barangDipinjam" class="form-label">Barang</label>
                        <input type="text" id="barangDipinjam" class="form-control" disabled>
                        <input type="hidden" name="barang_id" id="barangId">
                    </div>


                    <div class="mb-3">
                        <label for="tanggalPinjam" class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="form-control" id="tanggalPinjam" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggalKembali" class="form-label">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" class="form-control" id="tanggalKembali">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalPeminjaman = document.getElementById('modalPeminjaman');
    modalPeminjaman.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // tombol yang diklik
        var barangId = button.getAttribute('data-barang-id');
        var barangNama = button.getAttribute('data-barang-nama');

        // Isi input modal
        document.getElementById('barangDipinjam').value = barangNama;
        document.getElementById('barangId').value = barangId;
    });
});
</script>

