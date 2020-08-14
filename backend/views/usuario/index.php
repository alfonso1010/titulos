<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\file\FileInput;
use yii\grid\GridView;
use common\models\AuthAssignment;


$this->title = 'Usuarios';
?>
<div class="site-index">

    
    <div class="body-content">
    	<div class="row" style="margin-left:15px ">
        	<div class="col-xs-10">
          		<h3 style="color: brown">Usuarios del sistema</h3>
          		<br><br>
	          	<p>
	        		<?= Html::a('Crear Usuario', ['create'], ['class' => 'btn btn-success','style' => "float:right"]) ?>
	    		</p>
	    		<br>
    			<?= GridView::widget([
	                'dataProvider'=> $dataProvider,
	                'columns' => [
	                    'username',
	                    'email',
	                    [	
	                    	"attribute" => "rol",
	                    	"label" => "Rol",
	                    	"value" => function($model){
	                    		$rol = AuthAssignment::findOne(['user_id' => $model->id]);
	                    		return (!is_null($rol))?$rol->item_name:"No asignado";
	                    	}
	                    ],
	                    [   
	                        'class' => 'yii\grid\ActionColumn',
	                        'header' => 'Acciones',
	                        'headerOptions' => ['style' => 'color:#337ab7', 'class' => ' skip-export '],
	                        'template' => '{update} {delete}',
	                    ],
	                ],
	            ]);
	    		?>
          	</div>
      	</div>
    </div>
</div>
