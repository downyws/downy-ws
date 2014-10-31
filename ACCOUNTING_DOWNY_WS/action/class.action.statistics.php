<?php
class ActionStatistics extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		// 分类
		$categoryObj = Factory::getModel('category');
		$category = $categoryObj->getAllForSelect();

		// 图标数据
		$statisticsObj = Factory::getModel('statistics');
		$datas = [];
		$daystamp = strtotime(date('Y-m-d'));
		$start_month = date('Y', strtotime(date('Y') . '-01-01') - 1) . date('-m-01');
		$end_month = date('Y-m', strtotime(date('Y-m-01')) - 1) . '-01';

		$option = ['max_count' => 7, 'sort' => 'ASC', 'exclude' => function($data){
			return $data['value'] > 0;
		}];
		$datas['cate_30'] = $this->formatPie($statisticsObj->category($daystamp - 86400 * 29, $daystamp), $option);
		$datas['cate_180'] = $this->formatPie($statisticsObj->category($daystamp - 86400 * 179, $daystamp), $option);
		$datas['cate_0'] = $this->formatPie($statisticsObj->category(0, 0), $option);
		$datas['shopping_30'] = $this->formatPie($statisticsObj->category($daystamp - 86400 * 29, $daystamp, CATE_SHOPPING_ID), $option);
		$datas['shopping_180'] = $this->formatPie($statisticsObj->category($daystamp - 86400 * 179, $daystamp, CATE_SHOPPING_ID), $option);
		$datas['shopping_0'] = $this->formatPie($statisticsObj->category(0, 0, CATE_SHOPPING_ID), $option);

		$option = ['max_count' => 10, 'sort' => 'DESC', 'exclude' => function($data){
			return false;
		}];
		$datas['address_30'] = $this->formatPie($statisticsObj->address($daystamp - 86400 * 29, $daystamp), $option);
		$datas['address_180'] = $this->formatPie($statisticsObj->address($daystamp - 86400 * 179, $daystamp), $option);
		$datas['address_0'] = $this->formatPie($statisticsObj->address(0, 0), $option);

		$option = [];
		$datas['year_income'] = $this->formatLine($statisticsObj->incexpByMonthGroupMonth($start_month, $end_month, 'income'), $option);
		$datas['year_expenditure'] = $this->formatLine($statisticsObj->incexpByMonthGroupMonth($start_month, $end_month, 'expenditure'), $option);

		$this->assign('stats_thumb', Factory::getModel('statistics')->getThumbnail());
		$this->assign('category', $category);
		$this->assign('datas', $datas);
	}

	public function methodSearch()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'address_title' => [['format', 'trim']],
			'start_time' => [['valid', 'time', '', '', null]],
			'end_time' => [['valid', 'time', '', '', null]]
		]);
		$temp = $this->_submit->obtainArray($_REQUEST, [
			'category_id' => [['format', 'trim'], ['valid', 'int', '', '', null]]
		]);
		if(!empty($temp))
		{
			$params['category_id'] = [];
			foreach($temp as $v)
			{
				$params['category_id'][] = $v['category_id'];
			}
		}

		$result = [];

		$statisticsObj = Factory::getModel('statistics');
		$result['data'] = $statisticsObj->search($params);
		if(empty($result['data']))
		{
			$result = ['message' => '没有记录'];
		}

		echo json_encode($result);
	}

	public function formatPie($data, $option)
	{
		// 排序
		$data = array_values($data);
		$c = count($data);
		$t = null;
		for($i = 0; $i < $c; $i++)
		{
			for($j = $i; $j < $c; $j++)
			{
				if(
					($option['sort'] == 'ASC' && $data[$i]['value'] > $data[$j]['value']) || 
					($option['sort'] == 'DESC' && $data[$i]['value'] < $data[$j]['value'])
				){
					$t = $data[$i];
					$data[$i] = $data[$j];
					$data[$j] = $t;
				}
			}
		}

		// 排除不需要的
		foreach($data as $k => $v)
		{
			if($option['exclude']($v))
			{
				unset($data[$k]);
			}
		}

		// 合并过多的选项
		if(count($data) > $option['max_count'])
		{
			$data = array_values($data);
			$c = count($data);
			$t = ['id' => 0, 'name' => '其它', 'value' => 0];
			for($i = $option['max_count']; $i < $c; $i++)
			{
				$t['value'] += $data[$i]['value'];
			}
			$data = array_slice($data, 0, $option['max_count'] - 1);
			$data[] = $t;
		}

		return $data;
	}
	public function formatLine($data, $option)
	{
		return $data;
	}
}
