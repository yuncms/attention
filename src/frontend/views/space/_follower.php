<?php
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yuncms\attention\models\Follow $model
 */
?>
<div class="row">
    <div class="col-md-10">
        <img class="avatar-32" src="<?= $model->user->getAvatar($model->user_id) ?>"/>
        <div>
            <a href="<?= Url::to(['/user/space/view', 'id' => $model->user_id]) ?>"><?= $model->user->nickname ?></a>
            <div
                class="stream-following-followed"><?= $model->user->extend->supports ?><?= Yii::t('attention', 'Support') ?>
                / <?= $model->user->extend->followers ?><?= Yii::t('attention', 'Follower') ?>
                <?php if (isset($model->user->extend->answers)): ?>
                    / <?= $model->user->extend->answers ?><?= Yii::t('attention', 'Answer') ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-2 text-right">
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollowed(get_class($model->user), $model->model_id)): ?>
            <button type="button" class="btn btn-default btn-xs followerUser active" data-target="follow-button"
                    data-source_type="user"
                    data-source_id="<?= $model->model_id; ?>"><?= Yii::t('attention', 'Followed') ?>
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-default followerUser btn-xs" data-target="follow-button"
                    data-source_type="user"
                    data-source_id="<?= $model->model_id; ?>"><?= Yii::t('attention', 'Follow') ?>
            </button>
        <?php endif; ?>
    </div>
</div>