<?php
/**
 * 投稿
 */
class ArticleController extends Controller 
{
	use ConsoleTrait;
	use ArticleTrait;

    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
		return [
			['deny', 'roles' => ['audit']],
			['allow', 'users' => ['@']],
			['deny']
		];
	}

	/**
	 * 我的稿件
	 */
	public function actionIndex()
	{
		$filters = $this->fetchFilters();

		$criteria = new CDbCriteria;
		$criteria->order = 'create_time DESC';

		$criteria->addCondition('author_id = :user_id');
		$criteria->params[':user_id'] = Yii::app()->user->id;

		if($filters['keywords'] !== '')
		{
			$criteria->addCondition('t.sn LIKE :keywords'
				. ' OR t.title LIKE :keywords OR en_title LIKE :keywords'
				. ' OR abstract LIKE :keywords OR en_abstract LIKE :keywords'
				. ' OR author.real_name LIKE :keywords OR author.organization LIKE :keywords'
				. ' OR author.phone LIKE :keywords OR author.mobile LIKE :keywords');
			$criteria->params[':keywords'] = '%' . $filters['keywords'] . '%';
		}

		$articles = Article::model()->with('author')->findAll($criteria);

		$this->listArticle($articles, $filters, '我的稿件');
	}
}