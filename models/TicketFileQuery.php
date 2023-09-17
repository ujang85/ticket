<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TicketFile]].
 *
 * @see TicketFile
 */
class TicketFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TicketFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TicketFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
