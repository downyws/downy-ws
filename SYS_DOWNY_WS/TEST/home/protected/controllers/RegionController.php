<?php

class RegionController extends Controller
{
	public function actionSearch($id)
	{
		$data = Yii::app()->cache->get('region_children');
		if($data === false)
		{
			$regions = Region::model()->findAllByAttributes(['is_delete' => 0]);
			
			$temp = $data = [];
			foreach($regions as $v)
			{
				$temp[$v['id']] = json_decode(CJavaScript::jsonEncode($v), true);
				$temp[$v['id']]['children'] = [];
			}
			foreach($regions as $v)
			{
				$k = $v['parent_id'];
				if(!isset($data[$k]))
				{
					$data[$k] = isset($temp[$k]) ? $temp[$k] : [];
					$data[$k]['children'] = [];
				}
				$data[$k]['children'][] = json_decode(CJavaScript::jsonEncode($v), true);
			}
			foreach($temp as $k => $v)
			{
				if(!isset($data[$k]))
				{
					$data[$k] = $v;
				}
			}

			Yii::app()->cache->set('region_children', $data, 864000);
		}

		$this->renderJson(isset($data[$id]) ? $data[$id] : null);
	}
}
