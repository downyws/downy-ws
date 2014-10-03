<?php
class ActionIndex extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	// 统计信息
	public function methodIndex()
	{
		$statisticsObj = Factory::getModel('statistics');
		$statistics = $statisticsObj->getMain();

		$this->assign('statistics', $statistics);
	}

	// 列表
	public function methodListAjax()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'start' => [['valid', 'int', '', '', null]]
		]);

		$recordObj = Factory::getModel('record');

		$count = $recordObj->getOne(null, 'COUNT(*)');
		$data = $recordObj->getList($params['start']);

		$result = ['count' => $count, 'data' => $data];
		echo json_encode($result);
	}

	// 地址搜索
	public function methodSearchAddressAjax()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'term' => [['format', 'trim']]
		]);

		$addressObj = Factory::getModel('address');
		$temp = $addressObj->search($params['term']);
		$result = [];
		foreach($temp as $v)
		{
			$result[] = ['label' => $v['title'], 'value' => json_encode($v)];
		}

		echo json_encode($result);
	}

	// 编辑
	public function methodEdit()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'id' => [['valid', 'int', '', '', null]]
		]);

		$recordObj = Factory::getModel('record');

		// 数据
		$data = $recordObj->getRecord($params['id']);

		// 统计信息
		$statisticsObj = Factory::getModel('statistics');
		$statistics = $statisticsObj->getMain();

		// 分类
		$categoryObj = Factory::getModel('category');
		$temp = $categoryObj->getObjects(null);
		$category = [];
		foreach($temp as $v)
		{
			if($v['parent_id'] == 0)
			{
				$category[$v['id']] = ['id' => $v['id'], 'title' => $v['title'], 'child' => [], 'sort_array' => $v['sort']];
			}
		}
		foreach($temp as $v)
		{
			if($v['parent_id'] > 0)
			{
				$category[$v['parent_id']]['child'][] = ['id' => $v['id'], 'title' => $v['title'], 'sort_array' => $v['sort']];
			}
		}
		$category = $this->sortArray($category, 'utl');
		foreach($category as $k => $v)
		{
			$category[$k]['child'] = $this->sortArray($category[$k]['child'], 'utl');
		}

		// 货币
		$currencyObj = Factory::getModel('currency');
		$temp = $currencyObj->getObjects(null);
		$currency = [];
		foreach($temp as $v)
		{
			$currency[$v['id']] = ['id' => $v['id'], 'title' => $v['abbr'] . ' - ' . $v['title'], 'sort_array' => $v['sort']];
			if($v['id'] == DEFAULT_SURPLUS_CURRENCY)
			{
				$surplus = $v['title'] . ' (' . $v['abbr'] . ')';
			}
		}
		$currency = $this->sortArray($currency, 'utl');

		$this->assign('data', json_encode($data));
		$this->assign('statistics', $statistics);
		$this->assign('category', json_encode($category));
		$this->assign('currency', json_encode($currency));
		$this->assign('surplus', $surplus);
	}

	// 删除
	public function methodDel()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'id' => [['valid', 'int', '', '', null]]
		]);

		$recordObj = Factory::getModel('record');
		$result = $recordObj->remove($params['id']);

		if(!isset($result['message']))
		{
			$result['message'] = '删除成功';
		}
		else
		{
			$result['error'] = true;
		}

		echo json_encode($result);
	}

	public function methodSave()
	{
		$recordObj = Factory::getModel('record');
		$result = $recordObj->save($_POST);
		if(!isset($result['message']))
		{
			$result['message'] = '保存成功';
		}
		else
		{
			$result['error'] = true;
		}

		echo json_encode($result);
	}

	public function methodFileUpload()
	{
		$fileObj = Factory::getModel('file');
		$result = $fileObj->upload($_FILES['file']);
		echo json_encode($result);
	}

	public function methodFileRead()
	{
		$params = $this->_submit->obtain($_REQUEST, [
			'id' => [['valid', 'int', '', '', null]]
		]);

		$fileObj = Factory::getModel('file');
		$result = $fileObj->getObject([['id' => ['eq', $params['id']]]]);

		header('Content-type: ' . $result['type']);
		header('Content-Disposition: attachment; filename="' . $result['title'] . '"');
		echo $result['data'];
	}

	public function sortArray($arr, $type = '')
	{
		$result = [];

		if(!in_array($type, ['utl', 'ltu']))
		{
			$type = 'utl';
		}

		$temp = [];
		foreach($arr as $k => $v)
		{
			$s = $v['sort_array'];
			$i = isset($v['id']) && is_numeric($v['id']) ? $v['id'] : 0;
			unset($v['sort_array']);
			$temp[] = ['s' => $s, 'i' => $i, 'key' => $k, 'val' => $v];
		}
		$c = count($temp);
		for($i = 0; $i < $c; $i++)
		{
			for($j = $i; $j < $c; $j++)
			{
				if(
					($type == 'utl' && ($temp[$i]['s'] < $temp[$j]['s'] || ($temp[$i]['s'] == $temp[$j]['s'] && $temp[$i]['i'] < $temp[$j]['i']))) ||
					($type == 'ltu' && ($temp[$i]['s'] > $temp[$j]['s'] || ($temp[$i]['s'] == $temp[$j]['s'] && $temp[$i]['i'] > $temp[$j]['i'])))
				)
				{
					$t = $temp[$i];
					$temp[$i] = $temp[$j];
					$temp[$j] = $t;
				}
			}
		}

		for($i = 0; $i < $c; $i++)
		{
			$result[] = $temp[$i]['val'];
		}

		return $result;
	}
}
