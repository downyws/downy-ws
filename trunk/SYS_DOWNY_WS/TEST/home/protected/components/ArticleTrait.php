<?php

trait ArticleTrait
{
	/**
	 * ��ȡ������
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
	 * �������б�
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