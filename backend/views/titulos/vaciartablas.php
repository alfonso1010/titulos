<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\file\FileInput;


$this->title = 'Limpieza de tablas';
?>
<div class="site-index">

    
    <div class="body-content">

        <div class="row" style="margin-left:15px ">
            <div class="col-xs-8">
            	<h3 style="color: brown">El siguiente botón realiza una limpieza de todas las tablas relacionadas con importaciones de títulos electrónicos, su borrado es PERMANENTE y no se puede deshacer</h3>
            </div>
        </div>
        
		<div class="row" style="margin-left:15px ">
			<div class="col-xs-12">
				<div class="col-sm-5"></div>
				<div class="col-sm-4">
					<br><br>
					<button style="font-size: 25px;" data-toggle="modal" data-target="#modal-default" class="btn btn-danger">Realizar eliminado</button>
				</div>
			</div>
		</div>
		
    </div>
</div>



<div class="modal fade" id="modal-default">
	<?php
		$form = ActiveForm::begin([
			'id'       => 'active-form',
			'action'   => Url::to(['titulos/vaciartablas']),
			'options'  => [
				'class'   => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			],
		]);
	?>
  	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmación</h4>
      </div>
      <div class="modal-body">
        <label>Por favor ingrese contraseña de borrado:</label>
        <input type="password" name="password" class="active-form">
      </div>
      <div class="modal-footer">
        <?= Html::submitButton("Eliminar", ['class' => 'btn btn-danger','style' => "font-size:20px"])?>
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <?php ActiveForm::end();?>
  <!-- /.modal-dialog -->
</div>