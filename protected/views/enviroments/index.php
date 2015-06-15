<?php
/* @var $this EnviromentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Enviroments',
);

$this->menu=array(
	array('label'=>'Create Enviroments', 'url'=>array('create')),
	array('label'=>'Manage Enviroments', 'url'=>array('admin')),
);
?>

<h1>Enviroments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
