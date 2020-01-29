<div class="container-fluid">

    <div class="alert alert-success" role="alert">
        <i class="fas fa-chalkboard-teacher"></i>Form Materi Ujian
    </div>

    <form method="POST" action="<?php echo base_url(); ?>materi_ujian/insert" enctype="multipart/form-data">
    <!--<form method="post" action="<?php echo base_url('pengajar/materi_ujian/input_aksi') ?>">-->
        <div class="form-group">
        <label for="file">Pilih File</label>
            <input type="file" name="upload" required>
            
        </div>
        <input type="hidden" name="diklat_id" value="<?= $diklat_id->diklat_id ?>">

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>