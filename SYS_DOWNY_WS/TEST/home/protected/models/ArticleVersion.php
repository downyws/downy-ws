<?php

/**
 * This is the model class for table "{{contrib_article_version}}".
 *
 * The followings are the available columns in table '{{contrib_article_version}}':
 * @property integer $id
 * @property integer $article_id
 * @property integer $create_time
 */
class ArticleVersion extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{article_version}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			['article_id, create_time', 'required'],
			['article_id, create_time', 'numerical', 'integerOnly'=>true],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [
			'attachments' => [self::HAS_MANY, 'ArticleVersionAttachment', 'version_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'article_id' => '稿件ID',
			'create_time' => '创建时间',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
