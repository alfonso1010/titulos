<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;
use common\models\Institucion;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$rol = \Yii::$app->authManager->getRolesByUser($model->id);
$rol = reset($rol);
$rol = (isset($rol->name))?$rol->name:null;

$display = "none";
if($rol == "universidad"){
    $display = "block";
}
$onchange = 
<<<JS

    var seleccionado = $("#tipoUsuario option:selected").text();

    if(seleccionado == 'universidad')
    {
        $('#instituciones').show();
    }else{
    	$('#instituciones').hide();
    }

JS
;

?>

<div class="site-index">

    
    <div class="body-content">
    	<?php $form = ActiveForm::begin(); ?>
    	<div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
        		<div class="col-sm-4">
    			<?php
    				echo '<label class="control-label">Tipo de Usuario</label>';
                    echo Html::dropDownList(
                        'tipoUsuario',
                        null,
                        ArrayHelper::map(AuthItem::find()->where(['type' => 1])->asArray()->all(), 'name','name'),
                        [
                            'id '         => 'tipoUsuario',
                            'class'       => 'form form-control',
                            'prompt'      => 'Seleccione Usuario',
                            'onchange'	  => $onchange,
                            'options'     => [
                                $rol => ["Selected" => true]
                            ],
                        ]
                    );
    			?>
        		</div>
        		<div class="col-sm-1"></div>
				<div class="col-sm-4">
        			<?= $form->field($model, 'username')->textInput() ?>
        		</div>  
          	</div>
      	</div>
      	<br>
      	<div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
        		<div class="col-sm-4">
        			<?= $form->field($model, 'email')->textInput() ?>
        		</div>
	          	<div class="col-sm-1"></div>
				<div class="col-sm-4">
	    			 <?= $form->field($model, 'password_hash')->passwordInput()->label("Password"); ?>
	    		</div>  
	    	</div>
      	</div>
      	<br>

      	<div id="instituciones" class="row" style="margin-left:15px;display: <?= $display ?> ">
        	<div class="col-xs-12">
        		<div class="col-sm-6">
        			<label>Selecciona Instituciones</label>
                    <?php 
                        echo Select2::widget([
                            'name' => 'instituciones',
                            'value' => explode(',',$model->instituciones),
                            'data' => ArrayHelper::map(Institucion::find()->asArray()->all(), 'cveInstitucion','nombreInstitucion'),
                            'options' => ['multiple' => true],
                            'pluginOptions' => [
                                'tags' => true,
                                'tokenSeparators' => [',', ''],
                                'maximumInputLength' => 10
                            ],
                        ]);
                    ?>
        		</div>
	          	<div class="col-sm-1"></div>
	    	</div>
      	</div>
      	<br><br>
      	<div class="row" style="margin-left:15px ">
        	<div class="col-xs-12">
	          	<div class="col-sm-4"></div>
				<div class="col-sm-2">
	    			<div class="form-group">
				        <?= Html::submitButton( ($model->id > 0)?'Actualizar':'Guardar', ['class' => 'btn btn-success']) ?>
				    </div>
	    		</div>  
	    	</div>
      	</div>
      	<?php ActiveForm::end(); ?>
    </div>
</div>

