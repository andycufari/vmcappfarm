<?php
/* @var $this ApplicationsController */
/* @var $model Applications */

$this->breadcrumbs=array(
	'My Applications'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Applications', 'url'=>array('index')),
	array('label'=>'Manage Applications', 'url'=>array('admin')),
);
?>
	<link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/css/flick/jquery-ui-1.8.24.custom.css" rel="stylesheet" />
	<link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/css/multiselect/multiselect.css" rel="stylesheet" />
	<!--<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery-1.8.2.min.js"></script>-->
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery-ui-1.8.24.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery_ddslick.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery_multiselect.js"></script>

<h1>Create Application</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'cfframeworks' => $cfframeworks, 'enviroments' => $enviroments,'services'=>$services, 'appservices'=>$appservices,'repositories'=>$repositories)); ?>