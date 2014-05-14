<?php

trait ManageTrait
{
	/**
	 * 获取过滤项
	 */
	public function fetchFilters()
	{
		$keywords = Yii::app()->request->getParam('keywords');
		if(is_string($keywords))
		{
			$keywords = trim($keywords);
		}
		else
		{
			$keywords = '';
		}

		return [
			'keywords' => $keywords,
		];
	}

	public function listUser($datas, $filters, $title, $type = '')
	{
		$list = '';
		foreach($datas as $data)
		{
			$temp = [
				$data['id'],
				$data['username'],
				$data['real_name'],
				$data['email'],
				date('Y-m-d H:i', $data['visit_time']),
				date('Y-m-d H:i', $data['create_time']),
			];

			$list[] = ($type == 'ajax') ? [$data['id'], $temp] : $temp;
		}

		if($type == 'ajax')
		{
			$this->renderJson(['success' => true, 'data' => $list]);
		}
		else
		{
			$this->render('/manage/user', [
				'list' => $list,
				'filters' => $filters,
				'title' => $title,
			]);
		}
	}
}