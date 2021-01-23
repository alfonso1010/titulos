<?php
use yii\helpers\Url;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Url::to('@web/assets/LOGO.jpeg', true)  ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- /.search form -->
        <?php 
            $items = [];
            $rol = \Yii::$app->authManager->getRolesByUser(\Yii::$app->user->getId());
            $rol = reset($rol);
            array_push($items, ['label' => 'Menu', 'options' => ['class' => 'header']]);
            if ($rol->name == "admin"){
                array_push($items,  ['label' => 'Generación de Títulos XML', 'icon' => 'file-code-o', 'url' => ['titulos/generarxml']]);
                array_push($items,  ['label' => 'Firmado Electrónico Títulos', 'icon' => 'file-code-o', 'url' => ['titulos/firmarxml']]);
                array_push($items,  ['label' => 'Test Firmado Electrónico', 'icon' => 'file-code-o', 'url' => ['titulos/testxml']]);
                array_push($items,  ['label' => 'Envío y registro de Títulos', 'icon' => 'file-code-o', 'url' => ['titulos/enviarsep']]);
                array_push($items,  ['label' => 'Títulos QA', 'icon' => 'file-code-o', 'url' => ['titulos/qa']]);
                array_push($items,  ['label' => 'Títulos Producción', 'icon' => 'file-code-o', 'url' => ['titulos/prod']]);
                array_push($items,  ['label' => 'Usuarios', 'icon' => 'file-code-o', 'url' => ['usuario/index']]);
                array_push($items,  ['label' => 'Limpiar BD', 'icon' => 'file-code-o', 'url' => ['titulos/vaciartablas']]);
                
            }else{
                array_push($items,  ['label' => 'Firmado Electrónico Títulos', 'icon' => 'file-code-o', 'url' => ['titulos/firmarxml']]);
                //array_push($items,  ['label' => 'Envío y registro de Títulos', 'icon' => 'file-code-o', 'url' => ['titulos/enviarsep']]);
            }
            array_push($items, ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest]);

        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $items
            ]
        ) ?>

    </section>

</aside>
