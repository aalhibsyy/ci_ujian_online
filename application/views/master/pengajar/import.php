<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <ul class="alert alert-info" style="padding-left: 40px">
            <li>Silahkan import data dari excel, menggunakan format yang sudah disediakan</li>
            <li>Data tidak boleh ada yang kosong, harus terisi semua.</li>
            <li>Untuk data Mata Kuliah, hanya bisa diisi menggunakan ID Mata Kuliah. <a data-toggle="modal" href="#diklatId" style="text-decoration:none" class="btn btn-xs btn-primary">Lihat ID</a>.</li>
        </ul>
        <div class="text-center">
            <a href="<?= base_url('uploads/import/format/pengajar.xlsx') ?>" class="btn-default btn">Download Format</a>
        </div>
        <br>
        <div class="row">
            <?= form_open_multipart('pengajar/preview'); ?>
            <label for="file" class="col-sm-offset-1 col-sm-3 text-right">Pilih File</label>
            <div class="col-sm-4">
                <div class="form-group">
                    <input type="file" name="upload_file">
                </div>
            </div>
            <div class="col-sm-3">
                <button name="preview" type="submit" class="btn btn-sm btn-success">Preview</button>
            </div>
            <?= form_close(); ?>
            <div class="col-sm-6 col-sm-offset-3">
                <?php if (isset($_POST['preview'])) : ?>
                    <br>
                    <h4>Preview Data</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>NIP</td>
                                <td>Nama</td>
                                <td>Email</td>
                                <td>ID Mata Kuliah</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $status = true;
                                if (empty($import)) {
                                    echo '<tr><td colspan="2" class="text-center">Data kosong! pastikan anda menggunakan format yang telah disediakan.</td></tr>';
                                } else {
                                    $no = 1;
                                    foreach ($import as $data) :
                                        ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="<?= $data['nip'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nip'] == null ? 'BELUM DIISI' : $data['nip']; ?>
                                        </td>
                                        <td class="<?= $data['nama_pengajar'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nama_pengajar'] == null ? 'BELUM DIISI' : $data['nama_pengajar'];; ?>
                                        </td>
                                        <td class="<?= $data['email'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['email'] == null ? 'BELUM DIISI' : $data['email'];; ?>
                                        </td>
                                        <td class="<?= $data['diklat_id'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['diklat_id'] == null ? 'BELUM DIISI' : $data['diklat_id'];; ?>
                                        </td>
                                    </tr>
                            <?php
                                        if ($data['nip'] == null || $data['nama_pengajar'] == null || $data['email'] == null || $data['diklat_id'] == null) {
                                            $status = false;
                                        }
                                    endforeach;
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php if ($status) : ?>

                        <?= form_open('pengajar/do_import', null, ['data' => json_encode($import)]); ?>
                        <button type='submit' class='btn btn-block btn-flat bg-purple'>Import</button>
                        <?= form_close(); ?>

                    <?php endif; ?>
                    <br>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="diklatId">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data Mata Kuliah</h4>
            </div>
            <div class="modal-body">
                <table id="diklat" class="table table-condensed table-striped">
                    <thead>
                        <th>ID</th>
                        <th>Mata Kuliah</th>
                    </thead>
                    <tbody>
                        <?php foreach ($diklat as $m) : ?>
                            <tr>
                                <td><?= $m->id_diklat; ?></td>
                                <td><?= $m->nama_diklat; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table;
        table = $("#diklat").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
        });
    });
</script>