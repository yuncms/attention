<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\attention\frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yuncms\tag\models\Tag;
use yuncms\user\models\User;
use yuncms\attention\models\Attention;
use yuncms\user\Module;

/**
 * 关注操作
 * @package yuncms\user
 * @property Module $module
 */
class AttentionController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'store' => ['POST'],
                    'tag' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['store', 'tag'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new UnauthorizedHttpException(Yii::t('attention', 'The request has not been applied because it lacks valid authentication credentials for the target resource.'));
                }
            ],
        ];
    }

    public function actionStore()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Yii::$app->request->post('model');
        $modelId = Yii::$app->request->post('model_id');
        /** @var null|\yii\db\ActiveRecord $source */
        $source = null;
        if ($model == 'user') {
            /** @var null|\yuncms\user\models\User $source */
            $source = User::findOne($modelId);
            $subject = $source->nickname;
        } else if ($model == 'question' && Yii::$app->hasModule('question')) {
            $source = \yuncms\question\models\Question::findOne($modelId);
            /** @var null|\yuncms\question\models\Question $source */
            $subject = $source->title;
        } else if ($model == 'article' && Yii::$app->hasModule('article')) {
            /** @var null|\yuncms\article\models\Article $source */
            $source = \yuncms\article\models\Article::findOne($modelId);
            $subject = $source->title;
        } else if ($model == 'live' && Yii::$app->hasModule('live')) {
            /** @var null|\yuncms\live\models\Stream $source */
            $source = \yuncms\live\models\Stream::findOne($modelId);
            $subject = $source->title;
        }//etc..

        if (!$source) {
            throw new NotFoundHttpException ();
        }

        /*再次关注相当于是取消关注*/
        $attention = Attention::findOne(['user_id' => Yii::$app->user->id, 'model' => get_class($source), 'model_id' => $modelId]);
        if ($attention) {
            $attention->delete();
            if ($model == 'user') {
                $source->extra->updateCounters(['followers' => -1]);
            } else {
                $source->updateCounters(['followers' => -1]);
            }
            return ['status' => 'unfollowed'];
        }


        $data = [
            'user_id' => Yii::$app->user->id,
            'model_id' => $modelId,
            'model' => get_class($source),
        ];

        $attention = Attention::create($data);
        if ($attention) {
            switch ($model) {
                case 'question' :
                    notify(Yii::$app->user->id, $source->user_id, 'follow_question', $subject, $source->id);
                    doing(Yii::$app->user->id, 'follow_question', get_class($source), $modelId, $subject);
                    $source->updateCounters(['followers' => 1]);
                    break;
                case 'user':
                    $source->extra->updateCounters(['followers' => 1]);
                    notify(Yii::$app->user->id, $modelId, 'follow_user');
                    break;
                default:
                    $source->updateCounters(['followers' => 1]);
            }
            $attention->save();
        }
        return ['status' => 'followed'];
    }
}