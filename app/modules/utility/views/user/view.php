<?php 
$this->title ="Info User";
$this->params['breadcrumbs'][] = ['label' => 'Utility', 'url' => ['/utility/default']];
$this->params['breadcrumbs'][] = ['label' => 'User Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
   <div class="col-md-4">
      <div class="text-center">
         
         <img src="#" class="img-responsive img-thumbnail" alt="Image">
         
      </div>
      <h3 class="text-center"><?= strtoupper($model->fullname)  ?></h3>
      <div class="text-center mb-5"> Username : <?= $model->username  ?> </div>
      <div class="text-center mb-5"> Email : <?= $model->email  ?> </div>
      
   </div>
   <div class="col-md-8">
   
   <div class="panel panel-default ">
      <div role="tabpanel">
         <!-- Nav tabs -->
         <ul class="nav nav-tabs" role="tablist">
             <li role="presentation" class="active">
                 <a href="#role-user" aria-controls="home" role="tab" data-toggle="tab">1. Role</a>
             </li>
             <li role="presentation">
                 <a href="#activity" aria-controls="tab" role="tab" data-toggle="tab">2. Activity</a>
             </li>
             <li role="presentation">
                 <a href="#update" aria-controls="tab" role="tab" data-toggle="tab">3. Update User</a>
             </li>
         </ul>
     
         <!-- Tab panes -->
         <div class="tab-content">
             <div role="tabpanel" class="tab-pane active" id="role-user">...</div>
             <div role="tabpanel" class="tab-pane" id="activity">
                <div class="text-center"><h3>NO RECORD</h3> <br></div>
             </div>
             <div role="tabpanel" class="tab-pane active" id="update">...</div>

         </div>
      </div>
   </div>
   
    
     
            
   </div>
</div>

