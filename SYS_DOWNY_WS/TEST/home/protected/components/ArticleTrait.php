<?php

trait ArticleTrait
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

	/**
	 * 输出稿件列表
	 */
	public function listArticle($articles, $filters, $title)
	{
		$list = '';
		foreach($articles as $article)
		{
			$list []= [
				$article['id'],
				$article['sn'],
				$article['title'],
				$article->statusText,
				$article['author']['real_name'],
				$article['author']['organization'],
				$article['author']['mobile'] . ($article['author']['mobile'] && $article['author']['phone'] ? '/' : '') . $article['author']['phone'],
				$article['author']['user']['email'],
				date('Y-m-d', $article['create_time']),
			];
		}

		$this->render('/article/index', [
			'list' => $list,
			'filters' => $filters,
			'title' => $title,
		]);
	}
}