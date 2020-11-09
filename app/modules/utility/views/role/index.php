<?php 
use yii\helpers\Url;
$dataUrl = url::to(["get-data"]);
$viewUrl = url::to(["view"]);
$editUrl = url::to(["edit"]);

$this->title = 'Role Management';
$this->params['breadcrumbs'][] = ['label' => 'Utility', 'url' => ['/utility/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="datatable">
   <div class="row toolbar">
      <div class="col-md-10 bar-search form-inline">
         <form id="form_search" class="form-inline text-right ">
            <span class="hidden searchbox">
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
               <th data="serialcolumn" width="50" >No.</th>
               <th data="name" orderable="true" >Rolename</th>
               <th data="options" formatter="func.options" className="text-center" width="90"></th>
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
      let html =  `<span class="glyphicon glyphicon-search btn-link btn-view" data-id=\${row.name}></span> `;
      html+= `<span class="glyphicon glyphicon-pencil btn-link btn-edit" data-id=\${row.name}></span> `;
      html+= `<span class="glyphicon glyphicon-trash btn-link btn-del" data-id=\${row.name}></span> `;

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

JS;
$this->registerJs($js, yii\web\View::POS_READY);
?>