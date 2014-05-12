<?php

class RegionController extends Controller
{
	public function actionSearch($id)
	{
		$data = Yii::app()->cache->get('region_children');

	//	if(!isset($data))
		{
			$regions = Region::model()->findAllByAttributes(['is_delete' => 0]);
			$temp = $data = [];
			foreach($regions as $v)
			{
				$temp[$v['id']] = json_decode(CJavaScript::jsonEncode($v), true);
			}
			foreach($regions as $v)
			{
				$k = $v['parent_id'];
				if(!isset($data[$k]))
				{
					$data[$k] = $temp[$v['id']];
					$data[$k]['children'] = [];
				}
				$data[$k]['children'][] = json_decode(CJavaScript::jsonEncode($v), true);
			}
			Yii::app()->cache->set('region_children', $data, 864000);
		}

		$this->renderJson(isset($data[$id]) ? $data[$id] : null);
	}
}
