<?php

/**
 * This is the model class for table "{{contrib_article_version_attachment}}".
 *
 * The followings are the available columns in table '{{contrib_article_version_attachment}}':
 * @property integer $version_id
 * @property integer $attachment_id
 * @property string $title
 */
class ArticleVersionAttachment extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{article_version_attachment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('version_id, attachment_id, title', 'required'),
			array('version_id, attachment_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [
			'version' => [self::HAS_ONE, 'ArticleVersion', 'version_id'],
			'attachment' => [self::HAS_ONE, 'Attachment', 'attachment_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'version_id' => 'Version',
			'attachment_id' => 'Attachment',
			'title' => 'Title',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('attachment_id',$this->attachment_id);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleVersionAttachment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
