<?php
/* @var $this RepositoriesController */
/* @var $model Repositories */

$this->breadcrumbs=array(
	'Repositorios'=>array('admin'),
	'Create',
);

?>

<h1>Crear Repositorios</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>