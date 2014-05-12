<!DOCTYPE html>
<head>
<meta charset="utf-8">
<link type="text/css" rel="StyleSheet" href="/css/console.css">
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
</head>
<body>
<header>《上海经济研究》投审稿系统</header>
<section>
	<nav>
		<ul>
			<?php $user = Yii::app()->user; ?>
			<?php $setCurrent = function ($action){
				if($action == join('/', [$this->id, $this->action->id]))
				{
					echo ' class="current"';
				}
			}; ?>
			<?php if($user->checkAccess('audit')): ?>
				<?php if($user->checkAccess('audit.first') or $user->checkAccess('audit.second') or $user->checkAccess('audit.third') or $user->checkAccess('audit.review')): ?>
					<li<?php echo $setCurrent('audit/index'); ?>><a href="<?php echo $this->createUrl('audit/index'); ?>">我的审稿</a></li>
				<?php endif; ?>
				<?php if($user->checkAccess('audit.all')): ?>
					<li<?php echo $setCurrent('audit/recent'); ?>><a href="<?php echo $this->createUrl('audit/recent'); ?>">近期稿件</a></li>
					<li<?php echo $setCurrent('audit/all'); ?>><a href="<?php echo $this->createUrl('audit/all'); ?>">所有稿件</a></li>
				<?php endif; ?>
				<?php if($user->checkAccess('document')): ?>
					<li<?php echo $setCurrent('manage/document'); ?>><a href="<?php echo $this->createUrl('manage/document'); ?>">内容管理</a></li>
				<?php endif; ?>
				<?php if($user->checkAccess('user')): ?>
					<li<?php echo $setCurrent('manage/user'); ?>><a href="<?php echo $this->createUrl('manage/user'); ?>">用户管理</a></li>
				<?php endif; ?>
				<?php if($user->checkAccess('system')): ?>
					<li<?php echo $setCurrent('manage/system'); ?>><a href="<?php echo $this->createUrl('manage/system'); ?>">系统设置</a></li>
				<?php endif; ?>
			<?php else: ?>
				<li><a href="<?php echo $this->createUrl('article/new'); ?>">新增投稿</a></li>
				<li<?php echo $setCurrent('article/index'); ?>><a href="<?php echo $this->createUrl('article/index'); ?>">我的投稿</a></li>
				<li<?php echo $setCurrent('user/profile'); ?>><a href="<?php echo $this->createUrl('user/profile'); ?>">修改资料</a></li>
			<?php endif; ?>
			<li<?php echo $setCurrent('user/password'); ?>><a href="<?php echo $this->createUrl('user/password'); ?>">修改密码</a></li>
			<li><a href="<?php echo $this->createUrl('user/logout'); ?>">退出</a></li>
		</ul>
	</nav>
	<div class="body"><?php echo $content; ?></div>
</section>
</body>
</html>