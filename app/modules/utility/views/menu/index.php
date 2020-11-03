<?php
use yii\helpers\Url;

$save = Url::to(["save"]);
$saveMenu = Url::to(["save-menu"]);
$delete = Url::to(["delete"]);

?>
<div class="row">
   <div class="col-md-5">

      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title">Form Menu</h3>
         </div>
         <div class="panel-body">
            <form action="<?=$save?>" method="POST" class="form-horizontal" id="form-menu" role="form">
               <input type="hidden" name="id"/>
               <div class="form-group">
                  <label for="input" class="col-sm-3 control-label">Label</label>
                  <div class="col-sm-9">
                     <input type="text" name="label" id="label" class="form-control" placeholder="Label" />
                  </div>
               </div>
               <div class="form-group">
                  <label for="input" class="col-sm-3 control-label">Url</label>
                  <div class="col-sm-9">
                     <input type="text" name="url" id="url" class="form-control" placeholder="Url" />
                  </div>
               </div>
               <div class="form-group">
                  <label for="input" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-9">
                     <input type="text" name="description" id="description" class="form-control" placeholder="Description" />
                  </div>
               </div>
               <div class="form-group">
                  <label for="input" class="col-sm-3 control-label">Icon</label>
                  <div class="col-sm-9">
                     <input type="text" name="icon" id="icon" class="form-control"  placeholder="Icon" />
                  </div>
               </div>
            


               <div class="form-group">
                  <div class="col-sm-9 col-sm-offset-3">
                     <button type="reset" class="btn btn-danger btn-reset">Reset</button>
                     <button type="submit" class="submit btn btn-primary btn-submit">Save</button>
                  </div>
               </div>
            </form>

         </div>
      </div>


   </div>
   <div class="col-md-7">

      <div class="panel panel-default">
         <div class="panel-heading">
            <div class="panel-title">
               Menu Site generator
            </div>
         </div>
         <div class="panel-body">
            <div class="dd" id="nestable"> 
               <?php echo $menu ?>
            </div>
            <input type="hidden" id="nestable-output">
         </div>
      </div>

   </div>
</div>


<?php
$js = <<<JS
var updateOutput = function(e)
{
   var list   = e.length ? e : $(e.target),
      output = list.data('output');
   if (window.JSON) {
      output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
   } else {
      output.val('JSON browser support required for this demo.');
   }
};
$('.dd').nestable({ /* config options */ }).on('change', updateOutput);
updateOutput($('#nestable').data('output', $('#nestable-output')));

$(".btn-submit").click(function(e){
   e.preventDefault();

   $.ajax({
      type: "POST",
      url: "$save",
      data: $('#form-menu').serialize(),
      dataType: "json",
      cache : false,
      success: function(json){
         if(json.type == 'add'){
            $("#menu-id").append(json.menu_html);
         } else if(json.type == 'edit'){
            var data = json.data
            $('#label-show'+data.id).html(data.label);
            $('#link-show'+data.id).html(data.url);
            $('#icon-show'+data.id).attr("class", data.icon);
            $('#link-show'+data.id).closest(".dd-item").find(".item_actions").attr("data-json", json.data_json)
         }
         $("#form-menu").resetForm();
         $(".btn-submit").html("Save");
      } ,error: function(xhr, status, error) {
         alert(error);
      },
   });
});


$(document).on("click",".btn-del",function() {
   var x = confirm('Delete this menu?');
   var id = $(this).attr('data-id');
   if(x){
      $("#load").show();
         $.ajax({
            type: "POST",
            url: "$delete",
            data: { id : id },
            cache : false,
            success: function(data){
               $("#load").hide();
               $("li[data-id='" + id +"']").remove();
            } ,error: function(xhr, status, error) {
            alert(error);
            },
      });
   }
});

$(document).on("click",".btn-edit",function() {
   var dataJson = $(this).closest(".item_actions").attr("data-json")
   if(dataJson){
      $("#form-menu").loadJSON(dataJson);
      $(".btn-submit").html('Update');
   }  
});

$('.btn-reset').on('click', function() {
   $("#form-menu").resetForm();
   $(".btn-submit").html('Save');
})

$('.dd').on('change', function() {
   $("#load").show();

   var dataString = { 
      data : $("#nestable-output").val(),
   };

   $.ajax({
      type: "POST",
      url: "$saveMenu",
      data: dataString,
      cache : false,
      success: function(data){
         $("#load").hide();
      } ,error: function(xhr, status, error) {
         alert(error);
      },
   });
});


JS;

$this->registerJs($js)
?>