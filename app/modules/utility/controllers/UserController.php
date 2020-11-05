<?php
namespace app\modules\utility\controllers;

use app\models\Menu;
use app\models\User;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class UserController extends Controller
{
   
   public function actionIndex(){
      return $this->render("index", []);
   }

   public function actionGetData()
   {
       $req = Yii::$app->getRequest();
       Yii::$app->getResponse()->format = 'json';
       $query = (new \yii\db\Query())
           ->from("user");

      $orderedParam = $req->get('orderedParam');
      if (($q = $req->get('searchKey'))) {
         $query->andFilterWhere(['OR', 
            ['ilike',"fullname" ,$q]
         ]);

      }
      if($req->get('typeRecord') !== "0"  )
         $query->andFilterWhere(["!=", "status", 10 ]);
       // sorting
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

           'rows' => $query->all(),
       ];
   }

   public function actionView($id){
      $model = $this->findModel($id);
      return $this->render("view", ["model"=> $model]);
   }

   protected function findModel($id)
   {
       if (($model = User::findOne($id)) !== null) {
           return $model;
       } else {
           throw new NotFoundHttpException('The requested page does not exist.');
       }
   }
}