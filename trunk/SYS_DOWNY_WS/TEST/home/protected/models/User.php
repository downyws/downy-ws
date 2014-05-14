<?php

/**
 * This is the model class for table "contrib_user".
 *
 * The followings are the available columns in table 'contrib_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $real_name
 * @property string $email
 * @property integer $create_time
 * @property integer $visit_time
 */
class User extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('username, email, password, real_name', 'required', 'on' => 'insert'),
			array('username, email', 'unique', 'message' => '{attribute}已被注册', 'on' => 'insert'),
			array('username, password', 'length', 'min' => 6, 'max' => 32, 'on' => 'insert'),
			array('username', 'match', 'pattern' => '/^[a-z_0-9\-]+$/i', 'on' => 'insert'),
			array('email', 'email', 'on' => 'insert'),
			array('email, real_name', 'length', 'max' => 85)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
			'roles' => [self::MANY_MANY, 'AuthItem', '{{auth_assignment}}(userid, itemname)', 'joinType' => 'INNER JOIN'],
			'author' => [self::HAS_ONE, 'Author', 'user_id', 'joinType' => 'INNER JOIN'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '编号',
			'username' => '用户名',
			'password' => '密码',
			'email' => '邮箱',
			'real_name' => '真实姓名',
			'create_time' => '创建时间',
			'visit_time' => '访问时间'
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function resetPassword($use_email = true)
	{
		if(!$this['id'] || !$this['email'])
		{
			return false;
		}

		if($use_email)
		{
			$validate = new Validate;
			$validate->attributes = [
				'user_id' => $this['id'],
				'code' => md5(time() . mt_rand()),
				'create_time' => time(),
				'visit_time' => 0
			];

			if(!$validate->save())
			{
				return false;
			}
			$url = Yii::app()->params['siteUrl'] . '/user/recover/?key=' . base64_encode($this['id']) . '&code=' . $validate['code'];

			// 发送email
			Yii::import('ext.phpmailer.PHPMailer');
			$config = Yii::app()->params['email'];
			$email = new PHPMailer(true);
			$email->IsSMTP();
			$email->SMTPAuth = $config['SMTPAuth'];
			$email->Port = $config['Port'];
			$email->Host = $config['Host'];
			$email->Username = $config['Username'];
			$email->Password = $config['Password'];
			$email->From = $config['From'];
			$email->FromName = $config['FromName'];
			$email->IsHTML($config['IsHTML']);
			$email->CharSet = 'UTF-8';
			try
			{
				$email->AddAddress($this['email']);
				$email->Subject = '密码重置';
				$email->MsgHTML(
					'<table>' .
					'<tr><td>您好，</td><td>&nbsp;</td></tr>' .
					'<tr><td>&nbsp;</td><td>请点击以下链接继续找回密码：</td></tr>' .
					'<tr><td>&nbsp;</td><td>' . $url . '</td></tr>' .
					'<tr><td>&nbsp;</td><td>如不能直接点击请复制上一行的地址到浏览器中。</td></tr>' .
					'<tr><td>&nbsp;</td><td>谢谢！</td></tr>' .
					'<tr><td>&nbsp;</td><td>&nbsp;</td></tr>' .
					'<tr><td>&nbsp;</td><td style="text-align:right">' . $config['FromName'] . '</td></tr>' .
					'</table>'
				);
				return $email->Send();
			}
			catch(phpmailerException $e)
			{
				return false;
			}

			return true;
		}
		else
		{
			$pwd = mt_rand(100000, 999999);
			$this['password'] = md5($pwd);
			if($this->save())
			{
				return $pwd;
			}
			return false;
		}
	}
}
