<?php
/* @var $this ApplicationsController */
/* @var $data Applications */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appcode')); ?>:</b>
	<?php echo CHtml::encode($data->appcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />


	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('oauth_return_url')); ?>:</b>
	<?php echo CHtml::encode($data->oauth_return_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('initialize_app_url')); ?>:</b>
	<?php echo CHtml::encode($data->initialize_app_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adduser_url')); ?>:</b>
	<?php echo CHtml::encode($data->adduser_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deluser_url')); ?>:</b>
	<?php echo CHtml::encode($data->deluser_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('provisionokmail_id')); ?>:</b>
	<?php echo CHtml::encode($data->provisionokmail_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('addusermail_id')); ?>:</b>
	<?php echo CHtml::encode($data->addusermail_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delusermail_id')); ?>:</b>
	<?php echo CHtml::encode($data->delusermail_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('version')); ?>:</b>
	<?php echo CHtml::encode($data->version); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cfappframework_id')); ?>:</b>
	<?php echo CHtml::encode($data->cfappframework_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cfappframework_attr')); ?>:</b>
	<?php echo CHtml::encode($data->cfappframework_attr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appfile_path')); ?>:</b>
	<?php echo CHtml::encode($data->appfile_path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appfile_type')); ?>:</b>
	<?php echo CHtml::encode($data->appfile_type); ?>
	<br />

	*/ ?>

</div>