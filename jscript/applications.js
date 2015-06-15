function confirm_action(action,appid,exdata){
	switch(action)
	{
	  	case 'delete':
	  		jConfirm('¿Está seguro que desea ELIMINAR la aplicación?', 'AppManager', function(r) {
    		if(r){
    			execute_action(action,appid,exdata);
    			}
			});
	  	break;
		case 'stop':
	 	 jConfirm('¿Está seguro que desea DETENER la aplicación?', 'AppManager', function(r) {
    		if(r){
    			execute_action(action,appid,exdata);
    	 	}
		 });
		break;
		case 'deploy':
		jConfirm('¿Está seguro que desea DESPLEGAR en forma completa la aplicación?<br>Si la aplicación ya se encuentra desplegada esta acción va a eliminar servicios y archivos para volver a hacer un despliegue completo.', 'AppManager', function(r) {
    		if(r){
    			execute_action(action,appid,exdata);
    	 	}
		 });
		break;
		default:
	 	 execute_action(action,appid,exdata);
	}

}

function do_action(action,appid,exdata){
	refresh_on = false;
	if(action == 'modify'){       		
    		document.location.href= url_applications_update+'/id/'+appid;
    	}else{
    		if(action == 'manage'){
    			document.location.href= url_applications_manage+'/id/'+appid;	
    		}else{
    			confirm_action(action,appid,exdata);
    		}	
    	}
}

function execute_action(action,appid,exdata){
	preloader_on();
	$.post(url_applications_ajax,{id:appid,action:action,data:exdata},function(data){
		preloader_off();
		//var obj = jQuery.parseJSON(data);
		//var ram_array=obj.ram.split(',');
		//alert(data);
		if(data == 'refresh'){
			document.location.href = '';
			return 1;
		}

		$("#msg_dialog").html('<textarea id="codetext" style="width:400px;height:330px"></textarea>');
		$("#msg_dialog").dialog({ resizable: false, width: 450,height: 380});
		myCodeMirror = CodeMirror.fromTextArea(document.getElementById('codetext'),{ theme: 'blackboard',lineWrapping:true,lineNumbers:true,readOnly:true});
		myCodeMirror.setSize(420,340);
		myCodeMirror.setValue(data);

	}); 	
}

function add_env(){
	if($("env_key").val()!=""){
		if($("env_value").val()!=""){
			do_action('add_env','<?=$app->id?>',$("#env_key").val()+'='+$("#env_value").val());
		}
	}

}

function checkStatus(apps){
	$.post(url_applications_checkstatus,{appid:apps},function(data){
		
		if(data == 1){
			setInterval("document.location.href=''",20000);		}
	});
}

