<?php 
use yii\helpers\Url;
$dataUrl = url::to(["get-data"]);
$viewUrl = url::to(["view"]);
$editUrl = url::to(["edit"]);

$this->title = 'User Management';
$this->params['breadcrumbs'][] = ['label' => 'Utility', 'url' => ['/utility/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="datatable">
   <div class="row toolbar">
      <div class="col-md-10 bar-search form-inline">
         <form id="form_search" class="form-inline text-right ">
            <span class="hidden searchbox">
               <select class="form-control input-sm medis-section" name="inactive" id="inactive">
                  <option value="0"> SEMUA </option>
                  <option value="1"> Non Active </option>

               </select>
               <input type="text" class="form-control input-sm" autofocus name="searchKey" placeholder="Pencarian">
               <button type="submit" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-search"></span>
                  Cari</button>
               <button type="reset" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-remove"></span>
                  Reset</button>
            </span>
            <a href="javascript:void(0)" class="btn btn-sm btn-primary" id="togglesearch"><span class="glyphicon glyphicon-filter"></span></a>
         </form>
      </div>
   </div>
   <div class="table-responsive">
      <table class="table table-bordered table-hover table-condensed table-sortable">
         <thead>
            <tr class="btn-primary">
               <th data="serialcolumn" width="30" >No.</th>
               <th data="fullname" orderable="true" >Nama Lengkap</th>
               <th data="username" orderable="true">Username</th>
               <th data="email">Email</th>
               <th data="status" className="text-center">Status</th>
               <th data="Last Login">Last Login</th>
               <th data="options" formatter="func.options" className="text-center"></th>
            </tr>
         </thead>
         <tbody>
         </tbody>
      </table>
   </div>
</div>


<?php 

$js = <<<JS
   $("#datatable").datagrid({
      url: '$dataUrl',
      showloading: true,
      payload: {
         typeRecord: $('select[name="inactive"]'),
      },
   })

   func.options= function(row){
      let html =  `<span class="glyphicon glyphicon-search btn-link btn-view" data-id=\${row.id}></span> `;
      html+= `<span class="glyphicon glyphicon-pencil btn-link btn-edit" data-id=\${row.id}></span> `;
      html+= `<span class="glyphicon glyphicon-trash btn-link btn-del" data-id=\${row.id}></span> `;

      return html;
   }

   $(document).on('click', '.btn-edit', function(event) {
      var id = $(this).attr("data-id");
      window.location = `$editUrl?id=\${id}`;
   })

   $(document).on('click', '.btn-del', function(event) {
      var id = $(this).attr("data-id");
   })

   $(document).on('click', '.btn-view', function(event) {
      var id = $(this).attr("data-id");
      window.location = `$viewUrl?id=\${id}`;
   })
// generateDatatable($('#datatableDaftarTungguWrapper'), {
//       url: 'https://jkttim.epuskesmas.id/pelayanan',
//       columns: JSON.parse('[{"data":"number"}]'),
//       payload: {
//          ruangan_id: $("#datatableDaftarTungguWrapper").find("select[name='ruangan_id']"),
//          daftarTunggu: true
//       },
//       initDatatable,
//       bindElement: (record, element) => {
         
//          if (record.tanggal_selesai !== '') {
//             $(element).addClass('success')
//          } else if (record.tanggal_mulai !== '') {
//             $(element).addClass('danger')
//          }
//          bindElementDoubleClick($(element), () => {
//             window.location.replace("https://jkttim.epuskesmas.id/pelayanan/show/idCustom".replace('idCustom', record.id))
//          })
//       }
// })
JS;
$this->registerJs($js, yii\web\View::POS_READY);
?>