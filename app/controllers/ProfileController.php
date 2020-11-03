<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

class ProfileController extends Controller
{
   public function actionIndex(){
      $id = Yii::$app->user->id;
      $model = User::findOne($id);
      // if(!$model){
      //    Yii::$app->session->setFlash("warning", "Cannot Access Destination Page");
      //    return $this->redirect(Yii::$app->request->referrer);
      // }
         
      return $this->render("index", ["model"=>$model]);
   }
}