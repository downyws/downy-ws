<?php
class ActionIndex extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	public function methodIndex()
	{
		$this->assign('stats_thumb', Factory::getModel('statistics')->getThumbnail());
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
			$result[] = ['label' => $v['title'], 'value' => $v['title'], 'data' => json_encode($v)];
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

		// 分类
		$categoryObj = Factory::getModel('category');
		$category = $categoryObj->getAllForSelect();

		// 货币
		$currencyObj = Factory::getModel('currency');
		$currency = $currencyObj->getAllForSelect();
		foreach($currency as $v)
		{
			if($v['id'] == DEFAULT_SURPLUS_CURRENCY)
			{
				$surplus = $v['title'];
				break;
			}
		}

		$this->assign('data', json_encode($data));
		$this->assign('stats_thumb', Factory::getModel('statistics')->getThumbnail());
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
}
