<?php if( $this->ion_auth->is_admin() ) : ?>
<div class="row">
    <?php foreach($info_box as $info) : ?>
    <div class="col-lg-3 col-xs-8">
        <div class="small-box bg-<?=$info->box?>">
        <div class="inner">
            <h3><?=$info->total;?></h3>
            <p><?=$info->title;?></p>
        </div>
        <div class="icon">
            <i class="fa fa-<?=$info->icon?>"></i>
        </div>
        <a href="<?=base_url().strtolower($info->title);?>" class="small-box-footer">
            More info <i class="fa fa-arrow-circle-right"></i>
        </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php elseif( $this->ion_auth->in_group('pengajar') ) : ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Akun</h3>
            </div>
            <table class="table table-hover">
                <tr>
                    <th>Nama</th>
                    <td><?=$pengajar->nama_pengajar?></td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td><?=$pengajar->nip?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?=$pengajar->email?></td>
                </tr>
                <tr>
                    <th>Diklat</th>
                    <td><?=$pengajar->nama_diklat?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php else : ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Akun</h3>
            </div>
            <table class="table table-hover">
                <tr>
                    <th>NIS</th>
                    <td><?=$siswa->nis?></td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td><?=$siswa->nama?></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td><?=$siswa->jenis_kelamin === 'L' ? "Laki-laki" : "Perempuan" ;?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?=$siswa->email?></td>
                </tr>
                <tr>
                    <th>Diklat</th>
                    <td><?=$siswa->nama_diklat?></td>
                </tr>
                
            </table>
        </div>
    </div>
    
</div>

<?php endif; ?>