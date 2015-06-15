<?php
/* @var $this RepositoriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Repositories',
);

$this->menu=array(
	array('label'=>'Create Repositories', 'url'=>array('create')),
	array('label'=>'Manage Repositories', 'url'=>array('admin')),
);
?>

<h1>Repositories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
