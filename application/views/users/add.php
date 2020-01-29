<div class="row">
    <?php if($this->ion_auth->is_admin()) : ?>
    <div class="col-sm-12 mb-4">
        <a href="<?=base_url('users')?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Batal
        </a>
    </div>
    <?=form_open('users/addnew', array('id'=>'admin'), array('method'=>'add'))?>
    <div class="col-sm-4">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Data User</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" >
                    <small class="help-block"></small>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control">
                        <small class="help-block"></small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control">
                    <small class="help-block"></small>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <label for="level">Level User</label>
                    <select id="level" name="level" class="form-control select2" style="width: 100%!important">
                        <option value="">Pilih Level</option>
                        <option value="1" >admin</option>
                        <option value="2" >pengajar</option>
                        <option value="3" >mahasiswa</option>
                    </select>
                    <small class="help-block"></small>
                </div>
            </div>
            <div class="box-body pb-0">
                <div class="form-group">
                    <label for="new">Password</label>
                    <input type="password" placeholder="Password Baru" name="password" class="form-control">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="new_confirm">Konfirmasi Password</label>
                    <input type="confirm_password" placeholder="Konfirmasi Password Baru" name="password_confirm" class="form-control">
                    <small class="help-block"></small>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btn-info" class="btn btn-info">Simpan</button>
            </div>
        </div>
    </div>
                <?=form_close()?>
    <?php endif; ?>
</div>

<script src="<?=base_url()?>assets/dist/js/app/users/edit.js"></script>
