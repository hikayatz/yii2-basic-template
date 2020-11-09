<?php
namespace app\modules\utility\controllers;

use app\models\UserApp;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{

    public function actionIndex()
    {
        return $this->render("index", []);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render("view", ["model" => $model]);
    }

    public function actionCreate()
    {
		  $model = new UserApp(["status" => 1, "new_password" => 123456]);
		  $model->scenario  = "create";
        $model->status = 1;
        if ($model->load($_POST)) {
            $model->status = $model->status == 1 ? UserApp::STATUS_ACTIVE : UserApp::STATUS_DELETED;
            $model->setPassword($model->new_password);
            $model->generateAuthKey();
            //$baru = $model->isNewRecord;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'User berhasil di daftarkan');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
					var_dump($model->getErrors()); die();
                Yii::$app->session->setFlash('error', 'User gagal dibuat');
            }

        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = UserApp::findOne($id)) !== null) {
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
            ->from("user");

        $orderedParam = $req->get('orderedParam');
        if (($q = $req->get('searchKey'))) {
            $query->andFilterWhere(['OR',
                ['ilike', "fullname", $q],
            ]);

        }
        if ($req->get('typeRecord') !== "0") {
            $query->andFilterWhere(["!=", "status", 10]);
        }

        // sorting
        if ($orderedParam) {
            $orderKey = $orderedParam["key"];
            $orderType = $orderedParam["type"];
            if (!empty($orderKey) and !empty($orderType)) {
                $query->orderBy([$orderKey => $orderType == '1' ? SORT_ASC : SORT_DESC]);
            }

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
            'page' => $page,
            'rows' => $query->all(),
        ];
    }

    public function actionGetRole($id)
    {
        $req = Yii::$app->getRequest();
        Yii::$app->getResponse()->format = 'json';
        $query = (new Query())
            ->from("auth_item")
            ->leftJoin("auth_assignment", "auth_assignment.item_name = auth_item.name AND user_id = '$id'");
        // sorting
        if ($orderedParam) {
            $orderKey = $orderedParam["key"];
            $orderType = $orderedParam["type"];
            if (!empty($orderKey) and !empty($orderType)) {
                $query->orderBy([$orderKey => $orderType == '1' ? SORT_ASC : SORT_DESC]);
            }

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
}
