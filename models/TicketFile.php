<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_file".
 *
 * @property int $id
 * @property int|null $ticket_id
 * @property resource|null $file
 */
class TicketFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    
    public static function tableName()
    {
        return 'ticket_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id'],'integer'],
            [['file'], 'string'],
            [['ext'],'string', 'max' => 10],
            [['file'],'required' ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ticket_id' => Yii::t('app', 'Ticket ID'),
            'file' => Yii::t('app', 'File'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TicketFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TicketFileQuery(get_called_class());
    }
}
