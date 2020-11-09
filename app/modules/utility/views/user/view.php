<?php 
use yii\helpers\Url;
use app\models\User;

$dataUrl = url::to(["get-role", "id"=> $model->id]);
$getdata = url::to(["get-data", "id"=> $model->id]);

$this->title ="Info User";
$this->params['breadcrumbs'][] = ['label' => 'Utility', 'url' => ['/utility/default']];
$this->params['breadcrumbs'][] = ['label' => 'User Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
   <div class="col-md-4">
      <div class="text-center">

         <img src="<?= User::getUrlAvatar($model) ?>" class="img-responsive img-thumbnail" alt="Image" style="max-width: 200px">

      </div>
      <h3 class="text-center"><?= strtoupper($model->fullname)  ?></h3>
      <div class="text-center mb-5"> Username : <?= $model->username  ?> </div>
      <div class="text-center mb-5"> Email : <?= $model->email  ?> </div>
      <div class="text-center mb-5">  
         <?php if($model->status == User::STATUS_ACTIVE): ?>
            <span class="label label-primary">ACTIVE</span>
         <?php else: ?>
            <span class="label label-warning">NON ACTIVE</span>
         <?php endif; ?>
      </div>

   </div>
   <!--col-md-8 begin   -->
   <div class="col-md-8">
      <div class="panel with-nav-tabs panel-default">
         <div class="panel-heading">
            <ul class="nav nav-tabs">
               <li class="active"><a href="#role" data-toggle="tab">Role User</a></li>
               <li><a href="#update-user" data-toggle="tab">Update User</a></li>
               <li><a href="#activity" data-toggle="tab">Log Activity</a></li>
            </ul>
         </div>
         <div class="panel-body">
            <div class="tab-content">
               <div class="tab-pane in active" id="role">
                  <div class="text-left">
                     <a href="<?= Url::to(["/utility/role/create"])?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Add Role</a>
                  </div>
                  <!--datatable begin  -->
                  <div id="datatable">
                     <div class="table-responsive">
                        <table class="table table-bordered table-sortable">
                           <thead>
                              <tr class="btn-primary">
                                 <th data="serialcolumn" width="50">No.</th>
                                 <th data="item_name" orderable="true">Rolename</th>
                                 <th data="options" formatter="func.options" className="text-center" width="90px"></th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <!-- datatable end -->
               </div>
               <div class="tab-pane" id="update-user">
                 
               </div>
               <div class="tab-pane" id="activity">Default 3</div>

            </div>
         </div>
      </div>
   </div>
   <!--col-md-8 end  -->
</div>

<?php 

$js = <<<JS
   $("#datatable").datagrid({
      url: '$dataUrl',
      showloading: false,
      disablePush:true,
      payload: {
         typeRecord: $('select[name="inactive"]'),
      },
   })

   func.options= function(row){
      let html =  `<span class="glyphicon glyphicon-search btn-link btn-view" data-id=\${row.id}></span> `;
      html+= `<span class="glyphicon glyphicon-trash btn-link btn-del" data-id=\${row.id}></span> `;

      return html;
   }
JS;
$this->registerJs($js, yii\web\View::POS_READY);
?>