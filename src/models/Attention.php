<?php

namespace yuncms\attention\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
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
            TimestampBehavior::className()
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
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('attention', 'Id'),
            'user_id' => Yii::t('attention', 'User Id'),
            'model_id' => Yii::t('attention', 'Model Id'),
            'model_class' => Yii::t('attention', 'Model Class'),
            'created_at' => Yii::t('attention', 'Created At'),
            'updated_at' => Yii::t('attention', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return AttentionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttentionQuery(get_called_class());
    }

//    public function beforeValidate()
//    {
//        $this->user_id = Yii::$app->user->getId();
//        return parent::beforeValidate();
//    }

    /**
     * 创建实例
     * @param $attributes
     * @return bool|Attention
     */
    public static function create($attributes)
    {
        $model = new static ($attributes);
        if ($model->save()) {
            return $model;
        }
        return false;
    }

//    public function afterFind()
//    {
//        parent::afterFind();
//        // ...custom code here...
//    }

    /**
     * @inheritdoc
     */
//    public function beforeSave($insert)
//    {
//        if (!parent::beforeSave($insert)) {
//            return false;
//        }
//
//        // ...custom code here...
//        return true;
//    }

    /**
     * @inheritdoc
     */
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        Yii::$app->queue->push(new ScanTextJob([
//            'modelId' => $this->getPrimaryKey(),
//            'modelClass' => get_class($this),
//            'scenario' => $this->isNewRecord ? 'new' : 'edit',
//            'category'=>'',
//        ]));
//        // ...custom code here...
//    }

    /**
     * @inheritdoc
     */
//    public function beforeDelete()
//    {
//        if (!parent::beforeDelete()) {
//            return false;
//        }
//        // ...custom code here...
//        return true;
//    }

    /**
     * @inheritdoc
     */
//    public function afterDelete()
//    {
//        parent::afterDelete();
//
//        // ...custom code here...
//    }
}
