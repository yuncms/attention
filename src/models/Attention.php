<?php

namespace yuncms\attention\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\db\ActiveRecord;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%attentions}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $model_id
 * @property string $model_class
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Attention extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attentions}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'model_id', 'model_class'], 'required'],
            [['user_id', 'model_id'], 'integer'],
            [['model_class'], 'string', 'max' => 255],
            [['user_id', 'model_id', 'model_class'], 'unique', 'targetAttribute' => ['user_id', 'model_id', 'model_class']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms/attention', 'Id'),
            'user_id' => Yii::t('yuncms/attention', 'User Id'),
            'model_id' => Yii::t('yuncms/attention', 'Model Id'),
            'model_class' => Yii::t('yuncms/attention', 'Model Class'),
            'created_at' => Yii::t('yuncms/attention', 'Created At'),
            'updated_at' => Yii::t('yuncms/attention', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return AttentionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttentionQuery(get_called_class());
    }

}
