<?php
$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>

<h1>Este es el titulo del formulario</h1>

<p>Este es un texto explicativo a modo de subtitulo para acompañar en cualquier lugar del formulario</p>

<div class="form">

	<p class="note">Fields with <span class="required">*</span> are required.</p>
    
    
    <div class="formCols">
    	<div class="formRow">
        	<div class="col1 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
        </div>
    	<div class="formRow">
            <div class="col2 largeField">
            	<label>Label</label>
                <input type="text" />
            </div>
            <div class="col2 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <select>
                        <option>Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="formRow">
        	<div class="col3 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
            <div class="col3 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
            <div class="col3 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
        </div> 
        <div class="formRow">
        	<div class="col4 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
            <div class="col4 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
            <div class="col4 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
            <div class="col4 largeField">
            	<div class="formItem">
                    <label>Label</label>
                    <input type="text" />
                </div>
            </div>
        </div>
        
        <div class="formRowButtons">
        	<input type="button" value="Cancelar" class="red" />
            <input type="button" value="Aceptar" class="green" />
            <input type="button" value="Volver" class="gray" />
        </div>
    
    </div> <!-- formCols -->


	



</div><!-- form -->

