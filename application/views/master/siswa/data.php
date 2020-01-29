<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Master <?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="mt-2 mb-3">
            <a href="<?= base_url('siswa/add') ?>" class="btn btn-sm btn-flat bg-purple"><i class="fa fa-plus"></i> Tambah</a>
           <!--<a href="<?= base_url('siswa/import') ?>" class="btn btn-sm btn-flat btn-success"><i class="fa fa-upload"></i> Import</a>-->
            <button type="button" onclick="reload_ajax()" class="btn btn-sm btn-flat btn-default"><i class="fa fa-refresh"></i> Reload</button>
            <div class="pull-right">
                <button onclick="bulk_delete()" class="btn btn-sm btn-flat btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button>
            </div>
        </div>
        <?= form_open('siswa/delete', array('id' => 'bulk')); ?>
        <div class="table-responsive">
            <table id="siswa" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Diklat</th>
                        <th width="100" class="text-center">Aksi</th>
                        <th width="100" class="text-center">
                            <input class="select_all" type="checkbox">
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <<th>No.</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Diklat</th>
                        <th width="100" class="text-center">Aksi</th>
                        <th width="100" class="text-center">
                            <input class="select_all" type="checkbox">
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?= form_close() ?>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/siswa/data.js"></script>