<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\attention\rest\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yuncms\attention\rest\models\Attention;
use yuncms\rest\Controller;
use yuncms\rest\models\User;

/**
 * Class AttentionController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AttentionController extends Controller
{
    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return array_merge(parent::verbs(), [
            'follow' => ['POST', 'DELETE'],
            'friends' => ['GET'],
            'followers' => ['GET'],
            'friendships' => ['GET'],
        ]);
    }

    /**
     * 查找用户
     * @param int $userId
     * @return null|User
     * @throws NotFoundHttpException
     */
    protected function findUser($userId)
    {
        if (($user = User::findOne($userId)) != null) {
            return $user;
        }
        throw new NotFoundHttpException("User not found.");
    }

    /**
     * 关注别人
     * @param integer $id
     * @return Attention
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionFollow($id)
    {
        $user = $this->findUser($id);
        if (Yii::$app->request->isPost) {
            /** @var Attention $model */
            if (($model = Attention::find()->where(['user_id' => Yii::$app->user->getId(), 'model_class' => \yuncms\user\models\User::class, 'model_id' => $user->id])->one()) != null) {
                Yii::$app->getResponse()->setStatusCode(200);
                return $model;
            } else {
                $model = new Attention([
                    'model_id' => $user->id,
                    'model_class' => \yuncms\user\models\User::class,
                    'user_id' => Yii::$app->user->getId()
                ]);
                if ($model->save() === false && !$model->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
                }
                Yii::$app->getResponse()->setStatusCode(201);
                return $model;
            }
        } else if (Yii::$app->request->isDelete) {
            if (($model = Attention::find()->where(['user_id' => Yii::$app->user->getId(), 'model_class' => \yuncms\user\models\User::class, 'model_id' => $user->id])->one()) != null) {
                if (($model->delete()) != false) {
                    Yii::$app->getResponse()->setStatusCode(204);
                } elseif (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
                }
            } else {
                throw new NotFoundHttpException("Object not found.");
            }
        }
        throw new MethodNotAllowedHttpException();
    }

    /**
     * 获取用户的关注列表
     * @param string $id
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function actionFriends($id)
    {
        $user = User::findOne($id);
        $query = Attention::find()->where(['user_id' => $user->id, 'model_class' => \yuncms\user\models\User::class]);
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
        ]);
    }

    /**
     * 获取用户的粉丝列表
     * @param int $id
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function actionFollowers($id)
    {
        $user = User::findOne($id);
        $query = Attention::find()->where(['model_class' => \yuncms\user\models\User::class, 'model_id' => $user->id]);
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
        ]);
    }

    /**
     * 获取两个用户之间是否存在关注关系
     * @param int $source_id 源用户的UID
     * @param int $target_id 目标用户的UID
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionFriendships($source_id, $target_id)
    {
        $source = $this->findUser($source_id);
        $target = $this->findUser($target_id);
        return [
            'target' => [
                "id" => $source->id,
                "username" => $source->username,
                "following" => Attention::find()->where(['user_id' => $source->id, 'model_class' => \yuncms\user\models\User::class, 'model_id' => $target->id])->exists()
            ],
            'source' => [
                "id" => $target->id,
                "screen_name" => $target->username,
                "following" => Attention::find()->where(['user_id' => $target->id, 'model_class' => \yuncms\user\models\User::class, 'model_id' => $source->id])->exists()
            ],
        ];
    }
}