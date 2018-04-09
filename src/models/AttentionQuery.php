<?php

namespace yuncms\attention\models;

/**
 * This is the ActiveQuery class for [[Attention]].
 *
 * @see Attention
 */
class AttentionQuery extends \yii\db\ActiveQuery
{
    /**
     * @var string 模型类型
     */
    public $model_class;

    /**
     * @var string 数据表名称
     */
    public $tableName;

    /**
     * @param \yii\db\QueryBuilder $builder
     * @return $this|\yii\db\Query
     */
    public function prepare($builder)
    {
        if (!empty($this->model_class)) {
            $this->andWhere([$this->tableName . '.model_class' => $this->model_class]);
        }
        return parent::prepare($builder);
    }

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => Attention::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return Attention[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Attention|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
