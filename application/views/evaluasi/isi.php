<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url('evaluasi/list')?>" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Batal
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
            <?=form_open('evaluasi/save', array('id'=>'evaluasi'), array('method'=>'add'))?>

                <table id="form-table" class="table text-center table-condensed">
                    <thead>
                        <tr>
                            <th># No</th>
                            <th>Penilaian</th>
                            <th>SP</th>
                            <th>P</th>
                            <th>TP</th>
                            <th>STP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $no = 1;
                         foreach ($penilaian as $d) : ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><input type="hidden" name="penilaian[]" value="<?php echo $d->id_penilaian; ?>"><?= $d->nama_penilaian; ?></td>
            
                            <td>
                                <input type="radio" name="detail[<?php echo $no-1 ?>]" value="SP" required="required"/>
                            </td>
                            <td>
                                <input type="radio" name="detail[<?php echo $no-1 ?>]" value="P" required="required"/>
                            </td>
                            <td>
                                <input type="radio" name="detail[<?php echo $no-1 ?>]" value="TP" required="required"/>
                            </td>
                            <td>
                                <input type="radio" name="detail[<?php echo $no-1 ?>]" value="STP" required="required"/>
                            </td>
                        <?php $no++; ?>
                        <?php endforeach; ?>
                        

                    </tbody>
                </table>
                <table>
                    <tr>
                        <td>
                            Kritik dan Saran :
                        </td>
                        <td colspan="5">
                            <textarea name="kritik_saran" rows="5" cols="80"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Apa inspirasi yg anda dapatkan dari pematerian yang di ajarkan :
                        </td>
                        <td colspan="5">
                            <textarea name="inspirasi" rows="5" cols="80"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Adakah perbedaan antara sebelum dan setelah mengikuti diklat? :
                        </td>
                        <td colspan="5">
                            <textarea name="perbedaan" rows="5" cols="80"></textarea>
                        </td>
                    </tr>
                </table>
                    <input type="hidden" name="kd_evaluasi" value="<?php echo $kd_eval; ?>">
                    <input type="hidden" name="materi_id" value="<?= $id_materi ?>">
                    <div class="form-group pull-right">
                        <button type="reset" class="btn btn-flat btn-default"><i class="fa fa-rotate-left"></i> Reset</button>
                        <button type="submit" id="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/master/evaluasi/add.js"></script>