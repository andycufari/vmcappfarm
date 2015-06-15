<?php
/* @var $this AdminMenuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Admin Menus',
);

$this->menu=array(
	array('label'=>'Create AdminMenu', 'url'=>array('create')),
	array('label'=>'Manage AdminMenu', 'url'=>array('admin')),
);
?>

<h1>Admin Menus</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
