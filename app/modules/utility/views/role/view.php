<?php 
use yii\helpers\Url;



$this->title ="View Role";
$this->params['breadcrumbs'][] = ['label' => 'Utility', 'url' => ['/utility/default']];
$this->params['breadcrumbs'][] = ['label' => 'Role Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
   <div class="col-md-4"></div>
   <div class="col-md-8">
      <div class="panel with-nav-tabs panel-success">
         <div class="panel-heading">
            <ul class="nav nav-tabs">
               <li class="active"><a href="#menu" data-toggle="tab">Menu Permission</a></li>
               <li><a href="#role" data-toggle="tab">User Role</a></li>
            </ul>
         </div>
         <div class="panel-body">
            <div class="tab-content">
               <div class="tab-pane in active" id="menu">
               </div>
               <div class="tab-pane in active" id="role">
               </div>
            
            </div>
         </div>

      </div>
   </div>
</div>