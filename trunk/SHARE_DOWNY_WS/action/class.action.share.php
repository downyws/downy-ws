<?php
class ActionShare extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'tosite' => [['valid', 'function', '', '', function($arg){
				return ($arg != 'base') && file_exists(APP_DIR_TOSITE . $arg . '/tosite.' . $arg . '.php');
			}]],
			'url' => [['valid', 'url', '', '', null]],
			'desc' => [['format', 'trim']]
		]);
		$images = $this->_submit->obtainArray($_REQUEST, [
			'img' => [['valid', 'url', '', '', null]]
		]);
		$params['img'] = [];
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
