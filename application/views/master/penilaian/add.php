<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url('penilaian')?>" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Batal
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?=form_open('penilaian/save', array('id'=>'penilaian'), array('method'=>'add'))?>
                
                    <div class="form-group">
                        <label for="nama_penilaian">Penilaian</label>
                        <input placeholder="Nama Penilaian" type="text" name="nama_penilaian" class="form-control">
                        <small class="help-block"></small>
                    </div>
                 
                    <div class="form-group">
                    <label for="materi">Materi</label>
                    <select name="materi" id="materi" class="form-control select2" style="width: 100%!important">
                        <option value="" disabled selected>Pilih Materi</option>
                        <?php foreach ($materi as $row) : ?>
                            <option value="<?=$row->id_materi?>"><?=$row->nama_materi?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block"></small>
                </div>
                   
                    <div class="form-group pull-right">
                        <button type="reset" class="btn btn-flat btn-default"><i class="fa fa-rotate-left"></i> Reset</button>
                        <button type="submit" id="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/master/penilaian/add.js"></script>