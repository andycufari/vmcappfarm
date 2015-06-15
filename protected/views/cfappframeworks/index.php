<?php
/* @var $this CfappframeworksController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cfappframeworks',
);

$this->menu=array(
	array('label'=>'Create Cfappframeworks', 'url'=>array('create')),
	array('label'=>'Manage Cfappframeworks', 'url'=>array('admin')),
);
?>

<h1>Cfappframeworks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
