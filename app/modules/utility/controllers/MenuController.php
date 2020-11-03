<?php
namespace app\modules\utility\controllers;

use app\models\Menu;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
class MenuController extends Controller
{
    public function actionIndex()
    {

        Url::remember();
        return $this->render('index', [
           "menu" => $this->getMenu(Menu::getMenuTree())
           ]);
    }

    // build menu tree html
    protected function getMenu($items, $class = 'dd-list')
    {
       $html = '<ol class="' . $class . '" id="menu-id">';
        foreach ($items as $key => $value) {
         $menuJson = json_encode([
            "label"=>$value["label"],
            "id"=>$value["id"],
            "description"=>$value["description"],
            "icon"=>$value["icon"],
            "url"=>$value["link"],
         ]);
$html .= <<<HTML
   <li class="dd-item" data-id="{$value['id']}">
        <div class="pull-right item_actions" data-json='{$menuJson}'>
            <div class="btn btn-sm btn-danger pull-right btn-del" data-id="{$value['id']}">
                <span class="glyphicon glyphicon-trash"></span>
            </div>
            <div class="btn btn-sm btn-primary pull-right btn-edit" data-id="{$value['id']}">
                <span class="glyphicon glyphicon-pencil"></span>
            </div>
        </div>
        <div class="dd-handle">
            <span class="{$value['icon']}" id="icon-show{$value['id']}"></span>  <span id="label-show{$value['id']}">{$value['label']}</span> :  <span class="url" style="font-weight:normal" id="link-show{$value['id']}">{$value['link']}</span>
        </div>
HTML;
   
            if (array_key_exists('child', $value)) {
                $html .= $this->getMenu($value['child'], 'child');
            }
            $html .= "</li>";
        }
        $html .= "</ol>";

        return $html;

    }

    public function actionSaveMenu(){
       Yii::$app->response->format = "json";
       $success = 200;
       $msg = "";
       try {
         $success = 200;
         $db = Yii::$app->db;
         // bind post data
         $data = json_decode($_POST['data']);
         $readbleArray = $this->parseJsonArray($data);
         // update menu
         $i=0;
         foreach($readbleArray as $row){
            $i++;
            $sql = "UPDATE m_menu set parent_id = :parent, menu_order=:order WHERE id =:id";
            $db->createCommand($sql, [":parent"=> $row['parentID'], ":order"=> $i, ":id"=>$row['id'] ])
               ->execute();
         }
       } catch (\Throwable $th) {
          //throw $th;
          $msg= $th->getMessage();
         
       }
       return [
           "code"=> $success, 
           "msg"=>$msg 
       ];

    }

    // parsing arrayJson from client nestable 
    function parseJsonArray($jsonArray, $parentID = 0) {

      $return = array();
      foreach ($jsonArray as $subArray) {
        $returnSubSubArray = array();
        if (isset($subArray->children)) {
           $returnSubSubArray = $this->parseJsonArray($subArray->children, $subArray->id);
        }
    
        $return[] = array('id' => $subArray->id, 'parentID' => $parentID);
        $return = array_merge($return, $returnSubSubArray);
      }
      return $return;
    }

    public function actionSave(){
      Yii::$app->response->format = "json";
      $db= Yii::$app->db;
      $code = 500;
      $msg = "";
      $model = Menu::findOne((int)$_POST["id"]);
      $type = "edit";
      if(!$model)
         $model = new Menu();
      if($model->load($_POST, "")){
         if($model->isNewRecord ){
            $sql = "SELECT count(id) FROM m_menu WHERE (parent_id = 0 OR parent_id IS NULL)";
            $count = $db->createCommand($sql)->queryScalar();
            $model->menu_order = $count + 1;
            $type = "add";
            $menuJson = json_encode($model);
$html .= <<<HTML
   <li class="dd-item" data-id="{$value['id']}">
        <div class="pull-right item_actions" data-json='{$menuJson}'>
            <div class="btn btn-sm btn-danger pull-right btn-del" data-id="{$model->id}">
                <span class="glyphicon glyphicon-trash"></span>
            </div>
            <div class="btn btn-sm btn-primary pull-right btn-edit" data-id="{$model->id}">
                <span class="glyphicon glyphicon-pencil"></span>
            </div>
        </div>
        <div class="dd-handle">
            <span class="{$model->icon}" id="icon-show{$model->id}"></span>  <span id="label-show{$model->id}">{$model->label}</span> :  <span class="url" style="font-weight:normal" id="link-show{$model->id}">{$model->url}</span>
        </div>
   </li>
HTML;
         }
         $model->save(false);
         $code = 200;
      }

      return [
         "code"=>$code, 
         "msg"=>$msg,
         "type"=>$type, 
         "data"=>$model,
         "data_json"=>\yii\helpers\Json::encode($model), 
         "menu_html"=>$html
      ]; 
    }
    public function actionDelete(){
      Yii::$app->response->format = "json";
      $code = 500;
      try {
         
         $model = Menu::findOne($_POST["id"]);
         if($model){
            $model->delete();
            $code= 200;
         }
      } catch (\Throwable $th) {
         //throw $th;
         $msg = $th->getMessage();
      }
      return [
         "code"=> $code, 
         "msg"=>$msg    
      ];
    }

}