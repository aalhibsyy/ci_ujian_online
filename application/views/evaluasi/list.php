<div class="row">

    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$subjudul?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <button type="button" onclick="reload_ajax()" class="btn btn-sm btn-flat bg-purple"><i class="fa fa-refresh"></i> Reload</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive px-4 pb-3" style="border: 0">
                <table id="evaluasi" class="w-100 table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Materi</th>
                        <th>Pengajar</th>
                        <th class="text-center">Aksi</th>
                    </tr>        
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Materi</th>
                        <th>Pengajar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/evaluasi/list.js"></script>