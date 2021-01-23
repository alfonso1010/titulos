<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\file\FileInput;
use kartik\widgets\Select2;

$form = ActiveForm::begin([
		'id'       => 'active-form',
		'action'   => Url::to(['titulos/enviarsep']),
		'options'  => [
			'class'   => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		],
	]);
$this->title = 'Enviar Títulos a WS';
?>
<div class="site-index">

    
    <div class="body-content">

        <div class="row" style="margin-left:15px ">
            <div class="col-xs-8">
            	<h3 style="color: brown">Envio de títulos electrónicos al web service de la SEP</h3>
            </div>
        </div>
       
        <div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
				<div class="col-sm-12">
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			            <?php
			            	echo $form->field($formulario, 'institucion')->widget(Select2::classname(), [
							    'data' => $busca_instituciones,
				                'options' => [
				                    'placeholder' => '',
				                    'multiple' => false,
				                ],
				                'pluginOptions' => [
				                    'allowClear' => true
				                ],
							]);
			            ?>
					</div>
					<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<?= $form->field($formulario, 'usuario_ws')->textInput()->label('Usuario WS') ?>
					</div>
					<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						 <label class="control-label font-grey-silver portlet_label "> Password WS  </label>
                        <?= $form->field($formulario,'password_ws')->passwordInput()->label(false); ?>   
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
				<div class="col-sm-12">
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<?= $form->field($formulario, 'nombre_archivo')->textInput()->label('Nombre Archivo: ') ?>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			            <?php
			            echo $form->field($formulario, 'ambiente')->widget(Select2::classname(), [
						     'data' => [0 => "Ambiente QA",1 => "Producción"],
						    'pluginOptions' => [
						        'allowClear' => true
						    ],
						]);
			            ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
				<div class="col-sm-12">
					<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
						<label class="control-label font-grey-silver portlet_label "> Buscar Título(s): </label>
                        <?php
	                        echo $form->field($formulario,'titulo')->widget(FileInput::classname(),[
	                            'pluginOptions' => [
	                                'allowedFileExtensions' => ['zip','xml'],
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
		
	    <br><br><br>
		<div class="row" style="margin-left:15px ">
			<div class="col-xs-12">
				<div class="col-sm-5"></div>
				<div class="col-sm-4">
					<?=Html::submitButton("Enviar", ['class' => 'btn btn-success','style' => "font-size:20px"])?>
				</div>
			</div>
		</div>

	<?php if(!empty($data_respuesta)){ ?>
		<br><br>
		<div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
				<div class="col-sm-12">
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
					<div style="border: 2px solid;padding: 25px;" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<b style="font-size: 22px;color: brown;">Respuesta de web service:</b>
						<br><br>
						<b style="font-size: 18px;color: brown">Número Lote: </b> <b  style="font-size: 18px;"><?= strlen($data_respuesta['numeroLote'])>0?$data_respuesta['numeroLote']:"Error"; ?></b>
						<br><br>
						<b style="font-size: 18px;color: brown">Mensaje:</b><b  style="font-size: 18px;"> <?= $data_respuesta['mensaje'] ?></b>
						<br><br>
						<b style="font-size: 18px;color: brown">Ambiente:</b><b  style="font-size: 18px;"> <?= $data_respuesta['ambiente'] ?></b> 
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
		
    </div>
</div>
<?php ActiveForm::end();?>
