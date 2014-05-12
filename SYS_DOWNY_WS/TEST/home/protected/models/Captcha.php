<?php

class Captcha extends CModel
{
	public $captchaAction;
	public $captcha = null;

	public function attributeNames()
	{
		return ['captcha'];
	}

	public function rules()
	{
		return [['captcha', 'captcha']];
	}
}