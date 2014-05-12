<div class="fl">
<h2>基本信息</h2>
<table>
	<tr>
	<th>编号</th>
	<td><?php echo $article['sn']; ?></td>
	</tr>
	<tr>
	<th>标题</th>
	<td><?php echo $article['title']; ?></td>
	</tr>
	<tr>
	<th>英文标题</th>
	<td><?php echo $article['en_title']; ?></td>
	</tr>
	<tr>
	<th>摘要</th>
	<td><?php echo $article['abstract']; ?></td>
	</tr>
	<tr>
	<th>英文摘要</th>
	<td><?php echo $article['en_abstract']; ?></td>
	</tr>
</table>
</div>
<div class="fr">
<h2>稿件内容</h2>
<table>
	<?php foreach($article['versions'] as $version): ?>
	<tr>
	<th><?php echo date('Y-m-d', $version['create_time']); ?></th>
	<td><?php foreach($version['attachments'] as $attachment): ?>
		<a href="<?php echo $this->createUrl('attachment/view', ['id' => $attachment['attachment_id']]); ?>"><?php echo $attachment['title']; ?></a> 
	<?php endforeach; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<h2>操作</h2>
<form class="operation">
	<!-- 初审 -->
	<?php
		$need_all = false;
		$user = Yii::app()->user;
	?>
	<?php if($article->canOperate(Article::STATUS_FIRST) or $user->checkAccess('audit.all')): ?>
	<div<?php if(!$article->canOperate(Article::STATUS_FIRST)): $need_all = true;?> class="all"<?php endif; ?>><input type="radio" name="status" value="1">退回初审</div>
	<?php endif; ?>

	<?php if($article->canOperate(Article::STATUS_SECOND) or $user->checkAccess('audit.all')): ?>
	<div<?php if(!$article->canOperate(Article::STATUS_SECOND)): ?> class="all"<?php endif; ?>><input type="radio" name="status" value="2">提交二审，指派给：<select><option value="0">请选择</option><?php foreach(User::model()->with(['roles', 'author'])->findAll('roles.`name` = "二审人"') as $second_user): ?><option value="<?php echo $second_user['id']; ?>"><?php echo $second_user['author']['real_name']; ?></option><?php endforeach; ?></select></div>
	<?php endif; ?>

	<?php if($article->canOperate(Article::STATUS_THIRD) or $user->checkAccess('audit.all')): ?>
	<div<?php if(!$article->canOperate(Article::STATUS_THIRD)): ?> class="all"<?php endif; ?>><input type="radio" name="status" value="3">提交三审</div>
	<?php endif; ?>

	<?php if($article->canOperate(Article::STATUS_REVIEW) or $user->checkAccess('audit.all')): ?>
	<div<?php if(!$article->canOperate(Article::STATUS_REVIEW)): ?> class="all"<?php endif; ?>><input type="radio" name="status" value="-1">提交复审</div>
	<?php endif; ?>

	<?php if($article->canOperate(Article::STATUS_REFUSE) or $user->checkAccess('audit.all')): ?>
	<div<?php if(!$article->canOperate(Article::STATUS_REFUSE)): ?> class="all"<?php endif; ?>><input type="radio" name="status" value="-2">退稿</div>
	<?php endif; ?>

	<?php if($user->checkAccess('audit.all')): ?>
	<div class="all"><input type="radio" name="status" value="2">指派给：<select><option value="0">请选择</option><?php foreach(User::model()->with(['roles', 'author'])->findAll() as $second_user): ?><option value="<?php echo $second_user['id']; ?>"><?php echo $second_user['author']['real_name']; ?></option><?php endforeach; ?></select></div>
	<?php endif; ?>
	<div class="cc">
		<input type="submit" class="button" value="提交">
		<?php if($need_all): ?><a class="more">更多操作 &gt; &gt;</a><?php endif; ?>
	</div>
</form>
</div>