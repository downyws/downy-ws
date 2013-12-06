<?php

class Front
{
	public static function dispatch()
	{
		$action = !empty($_GET['a']) ? strtolower($_GET['a']) : '';
		$method = !empty($_GET['m']) ? strtolower($_GET['m']) : '';
		$type = !empty($_GET['t']) ? strtolower($_GET['t']) : '';

		if($action && $method)
		{
			$actionPath = APP_DIR_ACTION . 'class.action.' . $action . '.php';

			if(file_exists($actionPath))
			{
				if(stripos($action, 'mobi_') === 0)
				{
					$parentActionPath = APP_DIR_ACTION . 'class.action.' . substr($action, 5) . '.php';
					file_exists($parentActionPath) && require_once($parentActionPath);
				}
				require_once($actionPath);

				$actionName = 'Action' . $action;
				$methodName = 'method' . $method . $type;
				if(class_exists($actionName))
				{
					$actionObj = new $actionName;
					if(is_callable(array($actionObj, $methodName)))
					{
						if(empty($type) && file_exists(APP_DIR_TEMPLATE . $action . '_' . $method . '.html'))
						{
							$actionObj->initTemplate(false);
							$actionObj->assign('action', $action);
							$actionObj->assign('method', $method);
							$actionObj->$methodName();
							$actionObj->render($action . '_' . $method . '.html');
						}
						else
						{
							$actionObj->$methodName();
						}
						exit;
					}
				}
			}
		}
		header('HTTP/1.1 404 Not Found');
		exit;
	}

	public static function redirect($url, $code = 302)
	{
		switch($code)
		{
			case 301:
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . $url);
				exit;
			case 302:
				header('HTTP/1.1 302 Moved Temporarily');
				header('Location: ' . $url);
				exit;
			case 404:
				header('HTTP/1.1 404 Not Found');
				exit;
		}
	}
}
