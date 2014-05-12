<?php
/**
 * 审稿
 */
class AuditController extends Controller 
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
			['allow', 'roles' => ['audit']],
			['deny'],
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

		//指派给我的
		$user = Yii::app()->user;
		$condition = ['t.editor_id = :editor_id'];
		$criteria->params[':editor_id'] = $user->id;

		//我所参与的审稿阶段，二审只有指定人才能审稿
		if($user->checkAccess('audit.first'))
		{
			$condition []= 't.article_status = ' . Article::STATUS_FIRST;
		}

		if($user->checkAccess('audit.third'))
		{
			$condition []= 't.article_status = ' . Article::STATUS_THIRD;
		}

		if($user->checkAccess('audit.review'))
		{
			$condition []= 't.article_status = ' . Article::STATUS_REVIEW;
		}

		$criteria->addCondition(join(' OR ', $condition));

		//属于本角色的状态或者指派的文章

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

	/**
	 * 近期稿件
	 */
	public function actionRecent()
	{
		$filters = $this->fetchFilters();

		$criteria = new CDbCriteria;
		$criteria->order = 'create_time DESC';

		$criteria->addCondition('create_time >= UNIX_TIMESTAMP() - 86400 * 90');

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

		$this->listArticle($articles, $filters, '近期稿件');
	}

	/**
	 * 所有稿件
	 */
	public function actionAll()
	{
		$filters = $this->fetchFilters();

		$criteria = new CDbCriteria;
		$criteria->order = 'create_time DESC';

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

		$this->listArticle($articles, $filters, '所有稿件');
	}

	/**
	 * 稿件详情
	 */
	public function actionView()
	{
		if(!($id = Yii::app()->request->getParam('id')) or !($article = Article::model()->findByPk($id)))
		{
			throw new CHttpException(404, 'The requested page does not exist.'); 
		}

		$this->layout = '';

		$this->render('view', [
			'article' => $article,
		]);
	}

	/**
	 * 更新状态、审稿人
	 */
	public function actionOperation()
	{
		$article_status = Yii::app()->request->getParam('status');

		if(is_null($article_status))
		{

		}

		$editor_id = Yii::app()->request->getParam('editor_id');

		
	}
}