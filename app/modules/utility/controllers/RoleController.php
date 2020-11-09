<?php
namespace app\modules\utility\controllers;

use app\models\AuthItem;
use app\models\Menu;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class RoleController extends Controller
{
   
   public function actionIndex(){
      return $this->render("index", []);
   }

   public function actionView($id){
      $model = $this->findModel($id);
      return $this->render("view", [
         "model"=> $model, 
         "menu"=> $this->getMenu(Menu::getMenuTree())
      ]);
   }

   protected function findModel($id)
   {
       if (($model = AuthItem::findOne($id)) !== null) {
           return $model;
       } else {
           throw new NotFoundHttpException('The requested page does not exist.');
       }
   }

   public function actionGetData()
   {
       $req = Yii::$app->getRequest();
       Yii::$app->getResponse()->format = 'json';
       $query = (new Query())
           ->from("auth_item")->andWhere(["type"=> 1]);

      $orderedParam = $req->get('orderedParam');
      if (($q = $req->get('searchKey'))) {
         $query->andFilterWhere(['OR', 
            ['ilike',"name" ,$q]
         ]);

      }
      // sort order
      if ($orderedParam) {
         $orderKey = $orderedParam["key"];
         $orderType = $orderedParam["type"];
         if(!empty($orderKey) AND !empty($orderType))
            $query->orderBy([$orderKey => $orderType == '1' ? SORT_ASC : SORT_DESC]);
      } else {
      }

       // paging
       $limit = $req->get('limit', 15);
       $page = $req->get('page', 1);
       $total = $query->count();
       $query->offset(($page - 1) * $limit)->limit($limit);
       return [
           'total' => $total,
           'limit' => $limit,
            'page'=> $page,
           'rows' => $query->all(),
       ];
   }

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

  
}