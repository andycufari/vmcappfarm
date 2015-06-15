<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->breadcrumbs=array(
	'Configs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Config', 'url'=>array('index')),
	array('label'=>'Create Config', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('config-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Configs</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<div class="acciones_principales">

<?
$img_src=  Yii::app()->request->baseUrl.'/images/icons/32/';

?>
	<div class="optionButton">
    	<ul>
        	<li onclick="document.location.href='<?echo Yii::app()->createUrl('config/create');?>';">
               <img src="<?=$img_src?>application_add.png"></img>
               <span>Crear</span>
            </li>
            
        </ul>
    </div>

</div>

<br>
<br>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'config-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'key',
		'value',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
