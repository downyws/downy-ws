<?php
class ActionCron extends Action
{
	public function __construct()
	{
		parent::__construct();
	}

	// 抓取汇率
	public function methodExchangeRate()
	{
		$usetime = ['s' => microtime(true)];
		$update_list = [];

		$params = $this->_submit->obtain($_REQUEST, [
			'must' => [['format', 'int', '', '', null]]
		]);

		$currencyObj = Factory::getModel('currency');
		$currencys = $currencyObj->getAll(true);
		if(!$params['must'])
		{
			foreach($currencys as $k => $v)
			{
				if(date('Y-m-d', $v['rate_last_update_time']) == date('Y-m-d', time()))
				{
					unset($currencys[$k]);
				}
			}
		}

		if(!empty($currencys))
		{
			$c = count($currencys);
			ini_set('memory_limit', ($c * 8) . 'M');
			set_time_limit($c * 60);
			foreach($currencys as $v)
			{
				if($v['id'] == DEFAULT_SURPLUS_CURRENCY)
				{
					continue;
				}

				$exchange_rate = [false, false];
				$error = null;
				eval($v['config']);
				if($error != null)
				{
					(new Logs())->message('cron/rate', var_export($error, true));
				}
				else if(!$currencyObj->updateExchangeRate($v['id'], $exchange_rate))
				{
					(new Logs())->message('cron/rate', 'sql update failed');
				}
				else
				{
					$update_list[] = $v['abbr'];
				}
			}

			// 刷新缓存
			$currencyObj->getAll(true);
		}

		$usetime['e'] = microtime(true);

		echo 'use time: ' . ($usetime['e'] - $usetime['s']) . 's, update: ' . (empty($update_list) ? 'nothing' : implode(' ', $update_list));
	}
}
