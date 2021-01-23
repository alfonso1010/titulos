<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\file\FileInput;
use yii\grid\GridView;
use common\models\AuthAssignment;
use yii\bootstrap\Button;


$this->title = 'Títulos Producción';
?>
<div class="site-index">

    
    <div class="body-content">
    	<div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
          		<h3 style="color: brown">Títulos enviados a producción</h3>
          <?= Button::widget([
              'label' => 'Cancelar Títulos',
              'options' => [
                'class' => 'btn btn-danger',
                'style' => "margin:5px;float:right;font-size:20px;",
                'onclick' => "modalCancelar()"
              ],
          ]);
          ?>
          <br>
          <br>
          <br>
    			<?= GridView::widget([
	                'dataProvider' => $dataProvider,
    				'filterModel'=>$searchModel,
	                'options' => ['style' => 'border-style: outset;background:white;font-size:18px;'],
	                'columns' => [
	                    'id',
	                    'cveInstitucion',
	                    'nombre_archivo',
	                    'numero_lote',
	                    'fecha_envio',
	                    [   
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Acciones',
                        'headerOptions' => ['style' => 'color:#337ab7', 'class' => ' skip-export '],
                        'template' => '{consultar}{descargar}',
                        'buttons'  => [
                           'consultar' => function ($url, $model) {
                              	return Button::widget([
                								    'label' => 'Consultar',
                								    'options' => [
                                        'class' => 'btn btn-success',
                                        'style' => "margin:5px;",
                                        'onclick' => "modalConsultar(".$model->id.")"
                                    ],
                								]);
                            },    
                            'descargar' => function ($url, $model) {
                              	return Button::widget([
              								    'label' => 'Descargar',
              								    'options' => [
                                    'class' => 'btn btn-primary',
                                    'style' => "margin:5px;",
                                    'onclick' => "modalDescargar(".$model->id.")"
                                  ],
              								]);
                            },                  
                        ],
                    ],
	                ],
	            ]);
	    		?>
          	</div>
      	</div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalWS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <b><h4 class="modal-title" id="titulo_modal"></h4></b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="formulario">
          <div class="form-group">
            <label for="usuario_ws">Usuario Servico Web</label>
            <input type="text" class="form-control" id="usuario_ws"  placeholder="Usuario Servico Web">
          </div>    
          <div class="form-group">
            <label for="password_ws">Password Servico Web</label>
            <input type="password" class="form-control" id="password_ws"  placeholder="Usuario Servico Web">
          </div>  
          <div id="motivo_c" style="display: none">
            <div class="form-group">
            <label for="folio_ws">Folio Control</label>
              <input type="text" class="form-control" id="folio_ws"  placeholder="Folio Control">
            </div> 
            <div class="form-group">
            <label for="password_ws">Motivo de cancelación</label>
              <input type="text" class="form-control" id="motivo_ws"  placeholder="Motivo cancelación">
            </div> 
          </div>
        </div>
        <div id="loading" style="display: none">
          <div class="se-pre-con"></div>
        </div>    
        <div id="respuesta" style="display: none;border: 1px solid;padding: 5px;">
          <h3 style="color: brown">Respuesta Servicio: </h3><br>
          <p style="font-size: 16px;"><b style="color: brown;">No. Lote: </b> <b id="no_lote"></b></p><br>
          <p style="font-size: 16px;"><b style="color: brown;">Mensaje: </b> <b id="mensaje"></b></p>
        </div>
      </div>
      <div class="modal-footer">
        <div id="boton_enviar"></div>
        <br>
        <div><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
      </div>
    </div>
  </div>
   </form>
</div>



<!-- Modal -->
<div class="modal fade" id="ModalWSdes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <?php
    $form = ActiveForm::begin([
      'id'       => 'active-form',
      'action'   => Url::to(['titulos/descargarprod']),
      'options'  => [
        'enctype' => 'multipart/form-data'
      ],
    ]);
  ?>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <b><h4 class="modal-title" > Descargar Título</h4></b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="formulario">
          <div class="form-group">
            <label for="usuario_ws">Usuario Servico Web</label>
            <?= Html::input('text','usuario_ws','', $options=['class'=>'form-control', 'style'=>'width:350px']) ?>

          </div>    
          <div class="form-group">
            <label for="password_ws">Password Servico Web</label>
           <?= Html::input('password','password_ws','', $options=['class'=>'form-control','style'=>'width:350px']) ?>
          </div>  
          <?= Html::input('hidden','id_titulo','', $options=['class'=>'form-control','style'=>'width:350px','id' => "id_titulo_d"]) ?>
        </div>
      </div>
      <div class="modal-footer">
        <?=Html::submitButton("Descargar", ['class' => 'btn btn-success'])?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
  <?php ActiveForm::end();?>
</div>
<style type="text/css">
	.table-bordered > thead > tr > th,
.table-bordered > tbody > tr > th,
.table-bordered > tfoot > tr > th,
.table-bordered > thead > tr > td,
.table-bordered > tbody > tr > td,
.table-bordered > tfoot > tr > td {
    border: 1px solid #3c8dbc;
}

.se-pre-con {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url(https://smallenvelop.com/wp-content/uploads/2014/08/Preloader_11.gif) center no-repeat #fff;
}
</style>
<?php
  $url_consultar =Url::to(['titulos/consultarprod']);
  $url_cancelar =Url::to(['titulos/cancelarprod']);
?>
<script type="text/javascript">
  function modalConsultar(id){
    $('#ModalWS').modal('show');
     $("#respuesta").hide();
     $("#motivo_c").hide();
    $('#titulo_modal').html('<b>Consultar</b>');
    $('#boton_enviar').html('<button type="button" onclick="consultar('+id+')" class="btn btn-primary">Consultar</button>');
  }
  function modalCancelar(){
    $('#ModalWS').modal('show');
    $("#respuesta").hide();
    $("#motivo_c").show();
    $('#titulo_modal').html('<b>Cancelar</b>');
    $('#boton_enviar').html('<button type="button" onclick="cancelar()" class="btn btn-primary">Cancelar</button>');
  }
  function modalDescargar(id){
    $('#ModalWSdes').modal('show');
    $('#id_titulo_d').val(id);
  }
  function consultar(id_titulo){
    $("#loading").show();
     $.ajax({
      type:"POST", 
      async:false,
      url:"<?= $url_consultar ?>",
      data:{
        "usuario_ws": $("#usuario_ws").val(), 
        "password_ws": $("#password_ws").val(),
        "id_titulo"  : id_titulo
      },
      success:function(data){ 
        console.log(data);
        try{
          $('#no_lote').html(data.numeroLote);
          $('#mensaje').html(data.mensaje);
          $("#respuesta").show();
          $("#loading").hide();
        }catch(e){
          $('#no_lote').html("S/N");
          $('#mensaje').html("Ocurrió un error al obtener la información... intente mas tarde");
          $("#respuesta").show();
          $("#loading").hide();
        }
      },
      dataType: "json",
      statusCode: {
        500: function() {
          $('#no_lote').html("S/N");
          $('#mensaje').html("Ocurrió un error al obtener la información... intente mas tarde");
          $("#respuesta").show();
          $("#loading").hide();
        },
        404: function() {
          $('#no_lote').html("S/N");
          $('#mensaje').html("Ocurrió un error al obtener la información... intente mas tarde");
          $("#respuesta").show();
          $("#loading").hide();
        }
      }
    });
  }

  function cancelar(){
    $("#loading").show();
     $.ajax({
      type:"POST", 
      async:false,
      url:"<?= $url_cancelar ?>",
      data:{
        "usuario_ws": $("#usuario_ws").val(), 
        "password_ws": $("#password_ws").val(),
        "motivo_ws": $("#motivo_ws").val(),
        "folio_ws": $("#folio_ws").val(),
      },
      success:function(data){ 
        console.log(data);
        try{
          $('#no_lote').html(data.numeroLote);
          $('#mensaje').html(data.mensaje);
          $("#respuesta").show();
          $("#loading").hide();
          $("#motivo_ws").val("");
          $("#folio_ws").val("");
        }catch(e){
          $('#no_lote').html("S/N");
          $('#mensaje').html("Ocurrió un error al obtener la información... intente mas tarde");
          $("#respuesta").show();
          $("#loading").hide();
        }
      },
      dataType: "json",
      statusCode: {
        500: function() {
          $('#no_lote').html("S/N");
          $('#mensaje').html("Ocurrió un error al obtener la información... intente mas tarde");
          $("#respuesta").show();
          $("#loading").hide();
        },
        404: function() {
          $('#no_lote').html("S/N");
          $('#mensaje').html("Ocurrió un error al obtener la información... intente mas tarde");
          $("#respuesta").show();
          $("#loading").hide();
        }
      }
    });
  }

  
</script>