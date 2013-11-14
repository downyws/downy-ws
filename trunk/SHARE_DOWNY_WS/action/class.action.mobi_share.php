<?php
class ActionMobi_Share extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'tosite' => array(array('valid', 'function', '', '', function($arg){
				return ($arg != 'base') && file_exists(APP_DIR_TOSITE . $arg . '/tosite.' . $arg . '.php');
			})),
			'url' => array(array('valid', 'url', '', '', null)),
			'desc' => array(array('format', 'trim'))
		));
		$images = $this->_submit->obtainArray($_REQUEST, array(
			'img' => array(array('valid', 'url', '', '', null))
		));
		$params['img'] = array();
		foreach($images as $v)
		{
			if(!empty($v['img']))
			{
				$params['img'][] = $v['img'];
			}
			if(count($params['img']) >= 5)
			{
				break;
			}
		}
		$this->assign('params', $params);

		if(!empty($params['tosite']) && !empty($params['url']))
		{
			include_once(APP_DIR_TOSITE . $params['tosite'] . '/tosite.' . $params['tosite'] . '.php');
			$class = 'ToSite' . $params['tosite'];
			$tositeObj = new $class();
			$this->assign('name', $tositeObj->getName($params['tosite']));
			$this->assign('url', $tositeObj->getUrl($params));
		}
		else
		{
			include_once(APP_DIR_TOSITE . 'base/tosite.base.php');
			$tositeObj = new ToSiteBase();
			$this->assign('tosites', $tositeObj->sites);
		}
	}
}