<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\attention\frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yuncms\user\models\User;

/**
 * Class SpaceController
 * @package yuncms\attention\frontend\controllers
 */
class SpaceController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'follower'],
                        'roles' => ['?', '@']
                    ]
                ]
            ]
        ];
    }

    public $attentionClassMaps = [
        'questions' => 'yuncms\question\models\Question',
        'users' => 'yuncms\user\models\User',
        'lives' => 'yuncms\live\models\Stream'
    ];

    /**
     * 我的粉丝
     * @param int $id
     * @return string
     */
    public function actionFollower($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getFans()->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('follower', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 我的关注
     * @param int $id
     * @param string $type
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($id, $type = 'users')
    {
        $model = $this->findModel($id);
        if (!isset($this->attentionClassMaps[$type])) {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getAttentions()->andWhere(['model' => $this->attentionClassMaps[$type]])->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('index', [
            'model' => $model,
            'type' => $type,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}