<?php
/* @var $this RepositoriesController */
/* @var $data Repositories */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repo_url')); ?>:</b>
	<?php echo CHtml::encode($data->repo_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repo_user')); ?>:</b>
	<?php echo CHtml::encode($data->repo_user); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repo_passwd')); ?>:</b>
	<?php echo CHtml::encode($data->repo_passwd); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('key_file')); ?>:</b>
	<?php echo CHtml::encode($data->key_file); ?>
	<br />

	*/ ?>

</div>