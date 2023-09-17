<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property string|null $ticket_no
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $ext;
    public $file;
    public $ticket_id;
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at','updated_at'], 'integer'],
            [['ticket_no'],'string', 'max' => 32],
            [['description'],'string', 'max' => 200],
            [['description','ticket_no'],'required','message' => "data harus diisi"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ticket_no' => Yii::t('app', 'Ticket No'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getFileticket()
    {
        return $this->hasMany(TicketFile::className(), [ 'id'=>'ticket_id' ]);
    }
    public function behaviors(){    
        return [        
            TimestampBehavior::className(),    
        ];
    }
    
    // public function extraFields()
    // {
    //     return ['ticket_file'];
    // }
    /**
     * {@inheritdoc}
     * @return TicketQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TicketQuery(get_called_class());
    }
}
