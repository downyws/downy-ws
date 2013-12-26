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
				array('valid', 'between', '', 2010, array(2010, 2014))
			),
			'all' => array(array('valid', 'in', '', 0, array(0, 1)))
		));

		$filecache = new Filecache();
		$key = 'dates.temp';
		$result = $filecache->get($key);
		if(!$result)
		{
			$dateObj = Factory::getModel('date');
			$result = array('years' => array(), 'dates' => $dateObj->getAll());
			for($i = 0; $i < count($result['dates']); $i++)
			{
				for($j = $i; $j < count($result['dates']); $j++)
				{
					if($result['dates'][$i]['meet_time'] < $result['dates'][$j]['meet_time'])
					{
						list($result['dates'][$i], $result['dates'][$j]) = array($result['dates'][$j], $result['dates'][$i]);
					}
				}
				$result['years'][] = $result['dates'][$i]['y'];
			}
			$result['years'] = array_values(array_unique($result['years']));
			$filecache->set($key, $result, strtotime(date('Y-m-d')) + 86399 - time());
		}

		if(!$params['all'])
		{
			foreach($result['dates'] as $k => $v)
			{
				if($v['years'] != $params['year'])
				{
					unset($result['dates'][$k]);
					break;
				}
			}
		}

		echo json_encode($result);
	}
}
