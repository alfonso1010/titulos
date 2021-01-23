<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\file\FileInput;
use kartik\widgets\Select2;

$this->title = 'Firmado de Títulos';

$this->registerJs('

    function seleccionado(){
    	var profesionista = $("#profesionista").val();
    	if(profesionista.length > 0){
    		var carrera = $("#carrera").val();
	    	var arrprof = profesionista.split("-");
	    	var curp = arrprof[0];
	    	var nombre = arrprof[1];
	    	var alumnos = $("#alumnos").val();
	    	$("#alumnos").val(curp+","+alumnos);
	    	$("#profesionista").val("");
	    	$("#profesionista option[value=\'"+profesionista+"\']").prop("disabled", true);
            $("#profesionista").change();
	    	$("#seleccionados").append("<tr id=\""+curp+"\"><td style=\"border: 1px solid;border-color: brown\">"+carrera+"</td><td style=\"border: 1px solid;border-color: brown\">"+curp+"</td><td style=\"border: 1px solid;border-color: brown\">"+nombre+"</td><td style=\"border: 1px solid;border-color: brown\"><button onclick=\'deseleccionado(\""+curp+"\",\""+profesionista+"\");\' class=\"btn btn-danger\">Quitar</button></td></tr>");
    	}else{
    		alert("Por favor seleccione un profesionista");
    	}
    }

    function deseleccionado(curp,profesionista){
        $("#"+curp).remove();
        var alumnos = $("#alumnos").val();
        var nuevoal = alumnos.replace(curp+",","");
        $("#alumnos").val(nuevoal);
        $("#profesionista option[value=\'"+profesionista+"\']").prop("disabled", false);
    }

', View::POS_END);

?>
<div class="site-index">
    <div class="body-content">

        <div class="row" style="margin-left:15px ">
            <div class="col-xs-8">
            	<h3 style="color: brown">Firma y descarga de Título Electrónico XML</h3>
            </div>
        </div>
        <div class="row" style="margin-left:15px;margin-top:30px; ">
        	<div class="col-xs-12">
				<div class="col-sm-4">
					<label>Institución</label>
					<?php
						echo Select2::widget([
                            'name' => 'institucion',
                            'id' => 'institucion',
                            'data' => $busca_instituciones,
                            'options' => [
                                'placeholder' => 'Selecciona institución ...',
                                'onchange'=>'
                                    $("#carga").html("<div class=\'loading\'><img src=\'https://www.jose-aguilar.com/scripts/jquery/loading/images/loader.gif\' alt=\'loading\' /><br/>Un momento, por favor...</div>");
                                    $.post( "'.urldecode(Yii::$app->urlManager->createUrl('titulos/buscacarreras?id=')).'"+$(this).val(), function( data ) {
                                      $("select#carrera").html( data );
                                      $("#carrera").removeAttr("disabled");
                                      $("#carga").html("");
                                    });'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
					?>
				</div>
				<div class="col-sm-1"></div>
				<div class="col-sm-4">
					<br><br>
					<label>Profesionista</label>
					<?php
						echo Select2::widget([
                            'name' => 'profesionista',
                            'id' => 'profesionista',
                            'data' => [],
                            'options' => [
                                'placeholder' => 'Busca profesionista ...',
                            	'disabled' => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
					?>
					<div id="carga1"></div>
				</div>
				<div class="col-sm-2">
					<br><br>
					<button onclick="seleccionado();" class="btn btn-success">Seleccionar</button>
				</div>
			</div>
		</div>	
		<div class="row" style="margin-left:15px;margin-top:20px;">
        	<div class="col-xs-12">
				<div class="col-sm-4">
					<label>Carrera</label>
					<?php
						echo Select2::widget([
                            'name' => 'carrera',
                            'id' => 'carrera',
                            'data' => [],
                            'options' => [
                                'placeholder' => 'Selecciona carrera ...',
                                'disabled' => true,
                                'onchange'=>'
                                    $("#carga1").html("<div class=\'loading\'><img src=\'https://www.jose-aguilar.com/scripts/jquery/loading/images/loader.gif\' alt=\'loading\' /><br/>Un momento, por favor...</div>");
                                    $.post( "'.urldecode(Yii::$app->urlManager->createUrl('titulos/buscaprofesionistas?id=')).'"+$(this).val(), function( data ) {
                                      $("select#profesionista").html( data );
                                      $("#profesionista").removeAttr("disabled");
                                      $("#carga1").html("");
                                    });'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
					?>
					<div id="carga"></div>
				</div>
			</div>
		</div>	
		<br><br><br>
		<div class="row" style="margin-left:15px; ">
            <div class="col-xs-12">
            	<div class="box-body table-responsive no-padding" style="font-size: 20px;background: white">
	            	<table class="table table-hover" >
		                <tbody id="seleccionados">
		                	<tr>
			                	<th style="border: 1px solid;border-color: brown;color: black"><center>Carrera</center></th>
			                	<th style="border: 1px solid;border-color: brown;color: black"><center>Curp</center></th>
			                	<th style="border: 1px solid;border-color: brown;color: black"><center>Profesionista</center></th>
                                <th style="border: 1px solid;border-color: brown;color: black"><center>Acciones</center></th>
		                	</tr>	             
		                </tbody>
	            	</table>
	            </div>
            </div>
        </div>
        <br><br><br><br>
        <?php
            $form = ActiveForm::begin([
                'id'       => 'active-form',
                'action'   => Url::to(['titulos/descargaxml']),
                'options'  => [
                    'class'   => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                    'autocomplete' => 'off'
                ],
            ]);
        ?>
        <?=$form->field($formulario, 'alumnos')->hiddenInput(['id' => "alumnos"])->label(false);?>
        <div class="row" style="margin-left:15px; ">
            <div class="col-xs-12">
            	<div class="col-sm-12">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label class="control-label font-grey-silver portlet_label "> Firmante 1 .Cer: </label>
                        <?php
                            echo $form->field($formulario,'archivo_cer1')->widget(FileInput::classname(),[
                                'pluginOptions' => [
                                    'allowedFileExtensions' => ['cer'],
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                ]
                            ])->label(false);
                        ?>   
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label class="control-label font-grey-silver portlet_label "> Firmante 1 .Key: </label>
                        <?php
                            echo $form->field($formulario,'archivo_key1')->widget(FileInput::classname(),[
                                'pluginOptions' => [
                                    'allowedFileExtensions' => ['key'],
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                ]
                            ])->label(false);
                        ?>   
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label class="control-label font-grey-silver portlet_label "> Password Firmante 1  </label>
                        <?php
                            echo $form->field($formulario,'password1')->passwordInput()->label(false);
                        ?>   
                    </div>
                </div>
            </div>
        </div>
         <div class="row" style="margin-left:15px; ">
            <div class="col-xs-12">
                <div class="col-sm-12">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label class="control-label font-grey-silver portlet_label "> Firmante 2 .Cer: </label>
                        <?php
                            echo $form->field($formulario,'archivo_cer2')->widget(FileInput::classname(),[
                                'pluginOptions' => [
                                    'allowedFileExtensions' => ['cer'],
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                ]
                            ])->label(false);
                        ?>   
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label class="control-label font-grey-silver portlet_label "> Firmante 2 .Key: </label>
                        <?php
                            echo $form->field($formulario,'archivo_key2')->widget(FileInput::classname(),[
                                'pluginOptions' => [
                                    'allowedFileExtensions' => ['key'],
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                ]
                            ])->label(false);
                        ?>   
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label class="control-label font-grey-silver portlet_label "> Password Firmante 2  </label>
                        <?php
                            echo $form->field($formulario,'password2')->passwordInput()->label(false);
                        ?>   
                    </div>
                </div>
            </div>
        </div>

        <center><?=Html::submitButton("Generar XML", ['class' => 'btn btn-success','style' => "font-size:20px"])?></center>
        <?php ActiveForm::end();?>
    </div>
</div>
<style type="text/css">
	.table-bordered>tfoot>tr>td{

	}
</style>
