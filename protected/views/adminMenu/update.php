<?php
/* @var $this AdminMenuController */
/* @var $model AdminMenu */

$this->breadcrumbs=array(
	'Admin Menus'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AdminMenu', 'url'=>array('index')),
	array('label'=>'Create AdminMenu', 'url'=>array('create')),
	array('label'=>'View AdminMenu', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AdminMenu', 'url'=>array('admin')),
);
?>

<h1>Update AdminMenu <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>