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
	    	$("option[value=\'"+profesionista+"\']").remove(); 
	    	$("#seleccionados").append("<tr><td style=\"border: 1px solid;border-color: brown\">"+carrera+"</td><td style=\"border: 1px solid;border-color: brown\">"+curp+"</td><td style=\"border: 1px solid;border-color: brown\">"+nombre+"</td></tr>");
    	}else{
    		alert("Por favor seleccione un profesionista");
    	}
    	
    	
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
		                	</tr>	             
		                </tbody>
	            	</table>
	            </div>
            </div>
        </div>
        <br><br><br><br>
        <div class="row" style="margin-left:15px; ">
            <div class="col-xs-12">
            	<?php
            	$form = ActiveForm::begin([
					'id'       => 'active-form',
					'action'   => Url::to(['titulos/descargaxml']),
					'options'  => [
						'class'   => 'form-horizontal',
						'enctype' => 'multipart/form-data'
					],
				]);
            	?>
            	<input type="hidden" name="alumnos" id="alumnos"  />
            	<center><?=Html::submitButton("Generar XML", ['class' => 'btn btn-success','style' => "font-size:20px"])?></center>
            	<?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
	.table-bordered>tfoot>tr>td{

	}
</style>
