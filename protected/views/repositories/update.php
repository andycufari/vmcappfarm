<?php
/* @var $this RepositoriesController */
/* @var $model Repositories */

$this->breadcrumbs=array(
	'Repositorios'=>array('admin'),
	'Update',
);


?>

<h1>Actualizar Repositorio: <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>