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

	public function methodDatesAjax()
	{
		$params = $this->_submit->obtain($_REQUEST, array(
			'year' => array(
				array('format', 'int'),
				array('valid', 'between', '', 2010, array(2010, 2099))
			),
			'all' => array(array('valid', 'in', '', 0, array(0, 1)))
		));

		$filecache = new Filecache();
		$key = 'dates.temp';
		$dates = $filecache->get($key);
		if(!$dates)
		{
			$dateObj = Factory::getModel('date');
			$temp = $dateObj->getAll();
			$temp = array_reverse($temp);
			$dates = array(); 
			foreach($temp as $v)
			{
				if(!isset($dates[$v['y'] . '00']))
				{
					$dates[$v['y'] . '00'] = array();
				}
				if(!isset($dates[$v['y'] . '00'][$v['y'] . $v['m']]))
				{
					$dates[$v['y'] . '00'][$v['y'] . $v['m']] = array();
				}
				$dates[$v['y'] . '00'][$v['y'] . $v['m']][] = $v;
			}
			$filecache->set($key, $dates, strtotime(date('Y-m-d')) + 86399 - time());
		}

		$years = array_keys($dates);
		if(!$params['all'])
		{
			$dates = isset($dates[$params['year']]) ? 
				array($params['year'] => $dates[$params['year']]) : 
				array($params['year'] => array());
		}

		$result = array('years' => $years, 'dates' => $dates);
		echo json_encode($result);
	}
}
