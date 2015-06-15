<?php
/* @var $this RepositoriesController */
/* @var $model Repositories */

$this->breadcrumbs=array(
	'Repositories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Repositories', 'url'=>array('index')),
	array('label'=>'Create Repositories', 'url'=>array('create')),
	array('label'=>'Update Repositories', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Repositories', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Repositories', 'url'=>array('admin')),
);
?>

<h1>View Repositories #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'repo_url',
		'type',
		'repo_user',
		'repo_passwd',
		'key_file',
	),
)); ?>
