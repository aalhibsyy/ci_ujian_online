<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Master <?=$subjudul?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
	</div>
    <div class="box-body">
		<div class="mt-2 mb-4">
        <?php echo anchor('materi_ujian/input', '<button class="btn btn-primary mb-3">Tambah Materi Ujian</button>') ?>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm btn-flat btn-default"><i class="fa fa-refresh"></i> Reload</button>
	
		</div>
        <table id="materi_ujian" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Diklat</th>
                <th>Materi</th>
                <th>AKSI</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no =1;
            foreach ($materi_ujian as $materi) : ?>
                <tr>
                    <td width="20px"><?php echo $no++ ?></td>
                    <td><?php echo $materi->filename ?></td>
                    <td><?php echo $materi->nama_diklat ?></td>
                    
                   
                   
                    <!--<?php echo anchor('materi_ujian/update/' . $materi->id, '<div class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></div>'); ?>
                    -->
                    <td>
                        <?php echo anchor('materi_ujian/delete/' . $materi->id, '<div class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></div>'); ?>
                     
                    </td>
                    <td><a href="<?php echo base_url().'materi_ujian/download/' . $materi->id; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    </div>
</div>