<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Users', 'url'=>array('index')),
	array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>Create Users</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'max_apps'=>$max_apps,'max_apps_r'=>$max_apps_r)); ?>