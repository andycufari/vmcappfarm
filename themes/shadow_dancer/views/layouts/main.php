<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/buttons.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/icons.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/tables.css" />
    
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/mbmenu.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/mbmenu_iestyles.css" />
	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery-1.8.2.min.js"></script>
    <link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jqueryalerts/jquery.alerts.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jqueryalerts/jquery.alerts.js"></script>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<script>
	var spinnerVisible = false;
		function preloader_on(){
			if (!spinnerVisible) {
	            $("div#spinner").fadeIn("fast");
	            spinnerVisible = true;
	        }
		}

		function preloader_off(){
			if (spinnerVisible) {
	            var spinner = $("div#spinner");
	            spinner.stop();
	            spinner.fadeOut("fast");
	            spinnerVisible = false;
        	}
		}

		var url_applications_update = '<? echo Yii::app()->createUrl('applications/update'); ?>';
		var url_applications_manage = '<? echo Yii::app()->createUrl('applications/manage'); ?>';
		var url_applications_ajax = '<? echo Yii::app()->createUrl('applications/ajaxmanage'); ?>';
		var url_applications_checkstatus = '<? echo Yii::app()->createUrl('applications/checkprovstatus'); ?>';
	</script>

</head>

<body>
   <div id="spinner">
        Loading...
    </div>
<div class="container" id="page">
	<div id="topnav">
		<div class="topnav_text"><a href='<? echo Yii::app()->createUrl('site/index')?>'>Home</a> | <a href='https://auth.red.enncloud.com/users/edit.<? echo 1;?>'>My Account</a>  | <a href='<? echo Yii::app()->createUrl('site/logout'); ?>'>Logout (<?=Yii::app()->user->name?>)</a> </div>
	</div>
	<div id="header">
    	<div id="headerContent">
			<div id="logo"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.png"></img><?php //echo CHtml::encode(Yii::app()->name); ?></div>
        </div>
	</div><!-- header -->
    <div id="mainMbMenu">
	<?php 

		$this->widget('application.extensions.mbmenu.MbMenu',array(
            'items'=>array(
                array('label'=>'Dashboard', 'url'=>array('/site/index'),'itemOptions'=>array('class'=>'test')),
                array('label'=>'Applications',
                  'items'=>array(
                    array('label'=>'My Applications', 'url'=>array('/applications/admin'),'itemOptions'=>array('class'=>'icon_chart')),
					array('label'=>'Create Application', 'url'=>array('/applications/create')),
					
                  ),
                ),
                array('label'=>'Enviroments',
                  'items'=>array(
                    array('label'=>'My Enviroments', 'url'=>array('/enviroments/admin')),
                    array('label'=>'Create Enviroment', 'url'=>array('/enviroments/create')),
                    array('label'=>'Repositories', 'url'=>array('/repositories/admin')),
                    array('label'=>'Create Repository', 'url'=>array('/repositories/create')),
					
                  ),
                ),
				array('label'=>'Settings', 
					'items' => array(
						array('label'=>'Users','url'=>array('/users/admin'),'visible'=>Yii::app()->user->isAdmin()),
						array('label'=>'Globals','url'=>array('/config/admin'), 'visible'=>Yii::app()->user->isAdmin()),
						
				  ),'visible'=>Yii::app()->user->isAdmin()
				),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
            ),

    )); 
    ?> 
	
    
		
	</div> <!--mainmenu -->
	<div class="breadcrumbsContent">
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	</div>

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Cumulos<br/>
		All Rights Reserved.<br/>
		
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>