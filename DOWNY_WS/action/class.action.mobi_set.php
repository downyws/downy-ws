<?php
class ActionMobi_Set extends ActionSet
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodSiteType()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'app_name' => array(array('format', 'trim')),
			'app_url' => array(array('valid', 'url', '', '', null)),
			'callback' => array(array('valid', 'url', '', '', null))
		));

		if(empty($params['callback']) || empty($params['app_url']))
		{
			$this->redirect('/mobi/');
		}
		else if(isset($_COOKE['SITE_TYPE_DEFAULT']))
		{
			$site_type_default = in_array($_COOKE['SITE_TYPE_DEFAULT'], array('PC', 'MOBI')) ? $_COOKE['SITE_TYPE_DEFAULT'] : 'MOBI';
			$this->redirect($params['app_url'] . 'index.php?a=cookie&m=set&s=PC&expire=max&key=SITE_TYPE&val=' . $site_type_default . '&callback=' . urlencode($params['callback']));
		}
		else
		{
			$params['callback'] = urlencode($params['callback']);
			$this->assign('params', $params);
		}
	}
}
