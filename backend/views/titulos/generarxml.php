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
            	<h3 style="color: brown">Carga de información para construcción de Título Electrónico XML</h3>
            </div>
        </div>
        <div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
				<div class="col-sm-12">
		        	<div class="font-black text-content portlet-title bg-warning">
		                <div class="bg-warning text-justify">
		                  <strong><em>Antes de importar, es indispensable leer las siguientes instrucciones:
		                  </em></strong>
		                </div>
		                <div>
		                  <ul>
		                    <li>Las extensiones del archivo de Excel a importar son .xlsx".</li>
		                    <li>Es necesario que los datos coincidan con la columna correspondiente
		                    </li>
		                    <li>La estructura del archivo <code>NO</code>
		                        es modificable, solo recibe las columnas que se muestran en el ejemplo.
		                        Si se agregan columnas distintas a estas,
		                        el sistema puede tener un comportamiento inestable y no registrar sus datos.</li>
		                    <li>El importador solo carga archivos con un  máximo de
		                       <strong><em>1000 registros </em></strong> por cada uno.</li>
		                    <li>El tiempo de importación dependerá de los registros que contenga el archivo.</li>
		                    <ul>Al terminar la carga, le mostrará un mensaje del estado de la importacion:</ul>
		                      <ul><strong><font color= "green"><em>Archivo Importado Exitosamente</em>
		                      </font></strong>:
		                        Cuando su archivo fue importado.</ul>
		                      <ul><strong><font color= "red"><em>El archivo importado contiene Errores</em>
		                      </font></strong> :
		                         Cuando su archivo no fue importado porque algunos datos estan vacios.</ul>
		                    <li>Si el archivo contiene filas vacias o el contenido no respeta el tipo de dato
		                      de la estructura del archivo,  el sistema especificará la(s)
		                      <font color= "blue">columna</font> (s) y
		                      <font color= "blue">fila</font> (s) del dato(s) erróneo(s)
		                      en la parte inferior del importador.</li>
		                    <li> <strong><em>Nota:</em></strong>
		                      Puede descargar un archivo de ejemplo, presionando el siguiente botón
		                      <button class="btn btn-success waves-effect">
		                         <b><FONT COLOR="white" >
		                           <a href="<?= Url::to('@web/importador_titulos.xlsx', true) ?>" style="color: white; font-size: 14px;" >
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
