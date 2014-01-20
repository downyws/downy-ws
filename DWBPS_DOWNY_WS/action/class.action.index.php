<?php
class ActionIndex extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{

	}

	public function methodLogsAjax()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'year' => array(
				array('format', 'int'),
				array('valid', 'between', '', 2010, array(2010, 2014))
			),
			'all' => array(array('valid', 'in', '', 0, array(0, 1)))
		));

		$filecache = new Filecache();
		$key = 'logs.temp';
		$result = $filecache->get($key);
		if(!$result)
		{
			$logObj = Factory::getModel('log');
			$result = array('years' => array(), 'logs' => $logObj->getAll());
			for($i = 0; $i < count($result['logs']); $i++)
			{
				for($j = $i; $j < count($result['logs']); $j++)
				{
					if($result['logs'][$i]['create_time'] < $result['logs'][$j]['create_time'])
					{
						list($result['logs'][$i], $result['logs'][$j]) = array($result['logs'][$j], $result['logs'][$i]);
					}
				}
				$result['years'][] = $result['logs'][$i]['y'];
			}
			$result['years'] = array_values(array_unique($result['years']));
			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		if(!$params['all'])
		{
			foreach($result['logs'] as $k => $v)
			{
				if($v['years'] != $params['year'])
				{
					unset($result['logs'][$k]);
					break;
				}
			}
		}

		echo json_encode($result);
	}
}
