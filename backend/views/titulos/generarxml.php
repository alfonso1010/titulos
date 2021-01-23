<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\file\FileInput;

$form = ActiveForm::begin([
		'id'       => 'active-form',
		'action'   => Url::to(['titulos/generarxml']),
		'options'  => [
			'class'   => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		],
	]);
$this->title = 'Generar Títulos XML';
?>
<div class="site-index">

    
    <div class="body-content">

        <div class="row" style="margin-left:15px ">
            <div class="col-xs-8">
            	<h3 style="color: brown">Carga de información para construcción de Título Electrónico</h3><br>
            </div>
        </div>
        <div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
				<div class="col-sm-12">
		        	<div class="font-black text-content portlet-title bg-warning">
		                <div>
		                  <ul>
		                   
		                    <li> <strong><em>Nota:</em></strong>
		                      Puede descargar un archivo de ejemplo, presionando el siguiente botón
		                      <button class="btn btn-success waves-effect">
		                         <b><FONT COLOR="white" >
		                           <a href="<?= Url::to('@web/importador_titulos_v3.xlsx', true) ?>" style="color: white; font-size: 14px;" >
		                           Ejemplo de archivo a Importar</a></FONT></b>
		                      </button>  Para visualizar el formato del mismo e importar correctamente
		                      la información que desea.
		                    </li>
		                  </ul>
		                </div>
		            </div>
		        </div>
		    </div>
        </div>
        <br>
        <div class="col-sm-1">
			<?=$form->field($formulario, 'id_importacion')->hiddenInput()->label(false);?>
		</div>
        <?php if(empty($array_revision)){ ?>
	        <div class="row" style="margin-left:15px ">
	        	<div class="col-xs-12">
					<div class="col-sm-12">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<label class="control-label font-grey-silver portlet_label "> Buscar archivo: </label>
	                        <?php
		                        echo $form->field($formulario,'archivo')->widget(FileInput::classname(),[
		                            'pluginOptions' => [
		                                'allowedFileExtensions' => ['xlsx'],
		                                'showPreview' => false,
		                                'showCaption' => true,
		                                'showRemove' => true,
		                                'showUpload' => false,
		                            ]
		                        ])->label(false);
	                        ?>   
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if(!empty($array_revision)){ ?>
			<div class="row" style="margin-left:15px ">
	            <div class="col-xs-12">
	            	<div class="box-body table-responsive no-padding" style="font-size: 20px;background: white">
		            	<table class="table table-hover" >
			                <tbody>
			                	<tr>
				                	<th style="border: 1px solid;border-color: brown"><center>Nombre Institución</center></th>
				                	<th style="border: 1px solid;border-color: brown"><center>Carrera</center></th>
				                	<th style="border: 1px solid;border-color: brown"><center>Profesionista</center></th>
			                	</tr>
			                	<?php 
			                	foreach ($array_revision as $key => $value) {
			                	    //print_r($array_revision);die();
			                		echo '<tr>';
				                		echo '<td style="border: 1px solid;border-color: brown">';
				                			echo $value['institucion'];
				                		echo '</td>';
				                		echo '<td style="border: 1px solid;border-color: brown">';
				                			foreach ($value['carreras'] as $key1 => $value1) {
				                				echo $value1."<br>";
				                			}
				                		echo '</td>';
				                		echo '<td style="border: 1px solid;border-color: brown">';
				                			foreach ($value['profesionistas'] as $key2 => $value2) {
				                				echo $value2."<br>";
				                			}
				                		echo '</td>';
			                		echo '</tr>';
			                	}
			                	?>		             
			                </tbody>
		            	</table>
		            </div>
	            </div>
	        </div>
	    <?php } ?>
	    <br><br><br>
		<div class="row" style="margin-left:15px ">
			<div class="col-xs-12">
				<div class="col-sm-5"></div>
				<div class="col-sm-4">
					<?=Html::submitButton((empty($array_revision))?"Revisar":"Guardar", ['class' => 'btn btn-success','style' => "font-size:20px"])?>
				</div>
			</div>
		</div>
		
    </div>
</div>
<?php ActiveForm::end();?>
<style type="text/css">
	.table-bordered>tfoot>tr>td{

	}
</style>
