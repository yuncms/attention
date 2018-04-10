<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\attention\rest\models;

use yuncms\rest\models\User;

/**
 * Class Attention
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Attention extends \yuncms\attention\models\Attention
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}