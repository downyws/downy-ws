<?php

/**
 * This is the model class for table "{{contrib_article}}".
 *
 * The followings are the available columns in table '{{contrib_article}}':
 * @property integer $id
 * @property integer $author_id
 * @property integer $sn
 * @property integer $article_status
 * @property integer $editor_id
 * @property string $title
 * @property string $en_title
 * @property string $keywords
 * @property string $en_keywords
 * @property string $abstract
 * @property string $en_abstract
 * @property integer $create_time
 * @property integer $update_time
 */
class Article extends ContribActiveRecord
{
	const STATUS_FIRST = 0;
	const STATUS_SECOND = 1;
	const STATUS_THIRD = 2;
	const STATUS_ACCEPT = 3;

	const STATUS_REVIEW = -1;
	const STATUS_REFUSE = -2;

	static $statusText = [
		self::STATUS_REFUSE => '退稿',
		self::STATUS_REVIEW => '复审中',
		self::STATUS_FIRST => '初审中',
		self::STATUS_SECOND => '二审中',
		self::STATUS_THIRD => '三审中',
		self::STATUS_ACCEPT => '已录用',
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{article}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			['author_id, article_status, editor_id, title, en_title, keywords, en_keywords, abstract, en_abstract', 'required'],
			['author_id, article_status, editor_id, create_time, update_time', 'numerical', 'integerOnly'=>true],
			['title, en_title', 'length', 'max'=>255],
			['keywords, en_keywords, abstract, en_abstract', 'length', 'max'=>1024],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [
			'author' => [self::BELONGS_TO, 'Author', 'author_id', 'joinType' => 'INNER JOIN'],
			'versions' => [self::HAS_MANY, 'ArticleVersion', 'article_id', 'together' => FALSE],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'author_id' => '作者',
			'article_status' => '状态',
			'editor_id' => '编辑',
			'title' => '标题',
			'en_title' => '英文标题',
			'keywords' => '关键词',
			'en_keywords' => '英文关键词',
			'abstract' => '摘要',
			'en_abstract' => '英文摘要',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
		];
	}

	/**
	 * 普通用户可在该稿件中可进行的操作
	 */
	public function canOperate($new_status)
	{
		switch($new_status)
		{
		case self::STATUS_FIRST:
			return in_array($this['article_status'], [self::STATUS_REVIEW]);
		case self::STATUS_SECOND:
			return in_array($this['article_status'], [self::STATUS_REVIEW, self::STATUS_FIRST]);
		case self::STATUS_THIRD:
			return in_array($this['article_status'], [self::STATUS_FIRST, self::STATUS_SECOND]);
		case self::STATUS_REVIEW:
			return in_array($this['article_status'], [self::STATUS_FIRST]);
		case self::STATUS_REFUSE:
			return in_array($this['article_status'], [self::STATUS_REVIEW, self::STATUS_SECOND, self::STATUS_THIRD]);
		}
	}


	public function getStatusText()
	{
		return isset(self::$statusText[$this->article_status]) ? self::$statusText[$this->article_status] : '';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Article the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
