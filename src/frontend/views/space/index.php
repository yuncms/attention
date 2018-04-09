<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Nav;
use yuncms\user\models\User;

/**
 * @var \yii\web\View $this
 * @var  User $model
 * @var string $type
 */
$this->context->layout = '@yuncms/space/frontend/views/layouts/space';
$this->params['user'] = $model;

if ($type == 'questions') {
    $what = Yii::t('attention', 'Questions');
} else if ($type == 'users') {
    $what = Yii::t('attention', 'Users');
} else {
    $what = '';
}

if (!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id) {//Me
    $this->title = Yii::t('attention', '{who} followed {what}', [
        'who' => Yii::t('attention', 'My'),
        'what' => $what
    ]);
} else {
    $this->title = Yii::t('attention', '{who} followed {what}', [
        'who' => Html::encode($model->nickname),
        'what' => $what
    ]);
}

?>
<div class="stream-following">
    <?= Nav::widget([
        'options' => ['class' => 'nav nav-tabs'],
        'items' => [
            //问答
            ['label' => Yii::t('attention', 'Questions'), 'url' => ['/attention/space/index', 'id' => $model->id, 'type' => 'questions'], 'visible' => Yii::$app->hasModule('question')],
            //用户
            ['label' => Yii::t('attention', 'Broadcaster'), 'url' => ['/attention/space/index', 'id' => $model->id, 'type' => 'users'],],
            //用户
            ['label' => Yii::t('attention', 'Lives'), 'url' => ['/attention/space/index', 'id' => $model->id, 'type' => 'lives'], 'visible' => Yii::$app->hasModule('live')]
        ]
    ]); ?>

    <?php if ($type == 'lives') {
        $options = [
            'tag' => 'div',
            'class' => 'live-box'
        ];
        $itemOptions = ['tag' => 'div', 'class' => 'live'];
        \yuncms\live\frontend\assets\LiveAsset::register($this);
    } else {

        $options = [
            'tag' => 'ul',
            'class' => 'list-unstyled stream-following-list'
        ];
        $itemOptions = ['tag' => 'li'];
    } ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => $itemOptions,
        'itemView' => '_item',//子视图
        'viewParams' => ['type' => $type],
        'layout' => "{items}\n{pager}",
        'options' => $options
    ]); ?>

</div>

