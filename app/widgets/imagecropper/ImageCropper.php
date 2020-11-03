<?php
namespace app\widgets\imagecropper;

use yii\base\Widget;
use yii\web\View;
use yii\helpers\Html;
use Yii;
class ImageCropper extends Widget
{
    public $defaultImage = "";
    public $linkUpdate = "upload-file";
    public $placeHolderImage = "Drop your avatar here";
    public $cssImageContainer = "imagearea col-md-12 col-sm-12";

    function init(){
      if(empty($defaultImage)){
         $this->defaultImage = Yii::getAlias("@web/public/site")."/avatar.jpg";
      }
    }
    public function run()
    {
       $this->init();
$html = <<<HTML

<div class="row00">
   <!-- begin col-md-12 -->
   <div class="{$this->cssImageContainer}">
      <div class="avatarimage" id="drop-area">
         <img src="{$this->defaultImage}" alt="avatar" id="avatarimage" />
         <p>Drop your avatar here</p>
      </div>
      <div class="buttonarea">
         <label class="btn upload btn-primary"> <i class="fa fa-upload"></i> &nbsp; Upload<input type="file" class="sr-only"
               id="input" name="image" accept="image/*"></label>
         <button class="btn camera btn-success" data-backdrop="static" data-toggle="modal" data-target="#cameraModal"><i
               class="fa fa-camera"></i> &nbsp; Camera</button>
      </div>
      <div class="alert" role="alert"></div>
   </div>
   <!-- end col-md-12 -->
</div>

<!-- modal cropper begin -->
<div class="modal" id="myModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Make Selection</h4>
         </div>
         <div class="modal-body" >
            <div id="cropimage"  >
               <img id="imageprev" src="assets/img/bg.png" />
            </div>

            <div class="progress">
               <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0"
                  aria-valuemin="0" aria-valuemax="100" style="width: ;">
                  <span class="sr-only"> % Complete</span>
               </div>
            </div>

         </div>
         <div class="modal-footer">
            <div class="btngroup">
               <button type="button" class="btn upload1 float-left btn-danger" data-dismiss="modal">Close</button>
               <button type="button" class="btn btnsmall" id="rotateL" title="Rotate Left"><i
                     class="fa fa-undo"></i></button>
               <button type="button" class="btn btnsmall" id="rotateR" title="Rotate Right"><i
                     class="fa fa-repeat"></i></button>
               <button type="button" class="btn btnsmall" id="scaleX" title="Flip Horizontal"><i
                     class="fa fa-arrows-h"></i></button>
               <button type="button" class="btn btnsmall" id="scaleY" title="Flip Vertical"><i
                     class="fa fa-arrows-v"></i></button>
               <button type="button" class="btn btnsmall" id="zoomIn" title="Zoom In"><i
                     class="fa fa-search-plus"></i></button>
               <button type="button" class="btn btnsmall" id="zoomOut" title="Zoom Out"><i
                     class="fa fa-search-minus"></i></button>
               <button type="button" class="btn btnsmall" id="reset" title="Reset"><i
                     class="fa fa-refresh"></i></button>
               <button type="button" class="btn camera1 float-right btn-primary" id="saveAvatar">Save</button>
            </div>

         </div>
      </div>
   </div>
</div>

<!-- modal cropper end -->

<!-- modal take webcam begin -->
<div class="modal fade" id="cameraModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Take a picture</h4>
         </div>
         <div class="modal-body">
            <div id="my_camera"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary take_snapshot">Take a picture</button>
         </div>
      </div>
   </div>
</div>
<!-- modal take webcam end -->
HTML;
      $this->registerClientScript();
      return $html;
    }

    public function registerClientScript()
    {
      $view = $this->getView();
      ImageCropperAsset::register($view);
$js = <<<JS
// Configure a few settings and attach camera
function configure(){
   Webcam.set({
      width: 640,
      height: 480,
      image_format: 'jpeg',
      jpeg_quality: 100
   });
   Webcam.attach('#my_camera');
}
// A button for taking snaps

function take_snapshot() {
   // take snapshot and get image data
   Webcam.snap( function(data_uri) {
      // display results in page
      $("#cameraModal").modal('hide');
      $("#myModal").modal({backdrop: "static"});
      $("#cropimage").html('<img id="imageprev" src="'+data_uri+'"/>');
      cropImage();
      //document.getElementById('cropimage').innerHTML = ;
   } );

   Webcam.reset();
}

function saveSnap(){
   // Get base64 value from <img id='imageprev'> source
   var base64image =  document.getElementById("imageprev").src;

      Webcam.upload( base64image, 'upload.php', function(code, text) {
         console.log('Save successfully');
         //console.log(text);
      });
}

$('#cameraModal').on('show.bs.modal', function () {
  configure();
})

$('#cameraModal').on('hide.bs.modal', function () {
  Webcam.reset();
  $("#cropimage").html("");
})

$('#myModal').on('hide.bs.modal', function () {
 $("#cropimage").html('<img id="imageprev" src="assets/img/bg.png"/>');
})


/* UPLOAD Image */
var input = document.getElementById('input');
var alert = $('.alert');


/* DRAG and DROP File */
$("#drop-area").on('dragenter', function (e){
	e.preventDefault();
});

$("#drop-area").on('dragover', function (e){
	e.preventDefault();
});

$("#drop-area").on('drop', function (e){
	var image = document.querySelector('#imageprev');
	var files = e.originalEvent.dataTransfer.files;

	var done = function (url) {
          input.value = '';
          image.src = url;
          alert.hide();
		  $("#myModal").modal({backdrop: "static"});
		  cropImage();
        };

	var reader;
        var file;
        var url;

        if (files && files.length > 0) {
          file = files[0];

          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }

	e.preventDefault();

});

/* INPUT UPLOAD FILE */
input.addEventListener('change', function (e) {
var image = document.querySelector('#imageprev');
   var files = e.target.files;
   var done = function (url) {
      input.value = '';
      image.src = url;
      alert.hide();
   $("#myModal").modal({backdrop: "static"});
   cropImage();

   };
   var reader;
   var file;
   var url;

   if (files && files.length > 0) {
      file = files[0];

      if (URL) {
      done(URL.createObjectURL(file));
      } else if (FileReader) {
      reader = new FileReader();
      reader.onload = function (e) {
         done(reader.result);
      };
      reader.readAsDataURL(file);
      }
   }
});
/* CROP IMAGE AFTER UPLOAD */
function cropImage() {
   var image = document.querySelector('#imageprev');
   var minAspectRatio = 0.5;
   var maxAspectRatio = 1.5;

   var cropper = new Cropper(image, {
   aspectRatio: 11 /12,
   minCropBoxWidth: 220,
   minCropBoxHeight: 240,

      ready: function () {
         var cropper = this.cropper;
         var containerData = cropper.getContainerData();
         var cropBoxData = cropper.getCropBoxData();
         var aspectRatio = cropBoxData.width / cropBoxData.height;
         //var aspectRatio = 4 / 3;
         var newCropBoxWidth;
      cropper.setDragMode("move");
         if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) {
         newCropBoxWidth = cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);

         cropper.setCropBoxData({
            left: (containerData.width - newCropBoxWidth) / 2,
            width: newCropBoxWidth
         });
         }
      },

      cropmove: function () {
         var cropper = this.cropper;
         var cropBoxData = cropper.getCropBoxData();
         var aspectRatio = cropBoxData.width / cropBoxData.height;

         if (aspectRatio < minAspectRatio) {
         cropper.setCropBoxData({
            width: cropBoxData.height * minAspectRatio
         });
         } else if (aspectRatio > maxAspectRatio) {
         cropper.setCropBoxData({
            width: cropBoxData.height * maxAspectRatio
         });
         }
      },


   });

   $("#scaleY").click(function(){
   var Yscale = cropper.imageData.scaleY;
   if(Yscale==1){ cropper.scaleY(-1); } else {cropper.scaleY(1);};
   });

   $("#scaleX").click( function(){
   var Xscale = cropper.imageData.scaleX;
   if(Xscale==1){ cropper.scaleX(-1); } else {cropper.scaleX(1);};
   });

   $("#rotateR").click(function(){ cropper.rotate(45); });
   $("#rotateL").click(function(){ cropper.rotate(-45); });
   $("#reset").click(function(){ cropper.reset(); });
   $("#zoomIn").click(function(){ cropper.zoom(0.1); });
   $("#zoomOut").click(function(){ cropper.zoom(-0.1); });

   $("#saveAvatar").on("click", function(e){
      e.preventDefault();
      var progress = $('.progress');
      var progressBar = $('.progress-bar');
      var avatar = document.getElementById('avatarimage');
      var alert = $('.alert');
         canvas = cropper.getCroppedCanvas({
         width: 220,
         height: 240,
         });

         progress.show();
         alert.removeClass('alert-success alert-warning');
         canvas.toBlob(function (blob) {
         var formData = new FormData();
         console.log(blob)
         formData.append('avatar', blob, 'avatar.jpg');
         $.ajax('upload.php', {
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            xhr: function () {
               var xhr = new XMLHttpRequest();

               xhr.upload.onprogress = function (e) {
               var percent = '0';
               var percentage = '0%';

               if (e.lengthComputable) {
                  percent = Math.round((e.loaded / e.total) * 100);
                  percentage = percent + '%';
                  progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
               }
               };

               return xhr;
            },

            success: function () {
               //alert.show().addClass('alert-success').text('Upload success');
            },

            error: function () {
               //avatar.src = initialAvatarURL;
               alert.show().addClass('alert-warning').text('Upload error');
            },

            complete: function () {
               $("#myModal").modal('hide');
                     progress.hide();
               initialAvatarURL = avatar.src;
               avatar.src = canvas.toDataURL();
            },
         });
         });

   });
};

JS;
$view->registerJs($js, View::POS_READY);

    }

}