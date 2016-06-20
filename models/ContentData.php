<?php
/**
 * Content table
 * 
 * @author Sergey Kozin
 * @package Live content
 */
namespace ssoft\livecontent\models;

use yii\db\ActiveRecord;

class ContentData extends ActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public static function tableName(){
		return '{{%live_content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		return [
			[['place', 'type'], 'required'],
			[['place', 'type'], 'string', 'max'=>255],
			[['id'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return [
			'id' => 'ID',			
			'place' => 'ID place',
			'type' => 'Type',
			'content' => 'Content',		
			'modified_at' => 'Modified',	
		];
	}

	public static function place($placeID)
    {
        return static::findOne([
            'place' => $placeID
        ]);
    }

	public function beforeSave($insert) {
		$this->modified_at = new \yii\db\Expression('NOW()');
		return parent::beforeSave($insert);
	}
}
