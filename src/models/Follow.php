<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\attention\models;

use yuncms\user\models\User;

/**
 * This is the model class for table "user_attentions".
 *
 * @property integer $user_id
 * @property string $follow_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Follow extends Attention
{
    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'model_id']);
    }
}