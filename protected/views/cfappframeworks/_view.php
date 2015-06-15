<?php
/* @var $this CfappframeworksController */
/* @var $data Cfappframeworks */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cfcode')); ?>:</b>
	<?php echo CHtml::encode($data->cfcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ram')); ?>:</b>
	<?php echo CHtml::encode($data->ram); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imgsrc')); ?>:</b>
	<?php echo CHtml::encode($data->imgsrc); ?>
	<br />


</div>