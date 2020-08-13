<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Usuario</p>

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
                array_push($items,  ['label' => 'Limpiar BD', 'icon' => 'file-code-o', 'url' => ['titulos/vaciartablas']]);
            }else{
                array_push($items,  ['label' => 'Firmado Electrónico Títulos', 'icon' => 'file-code-o', 'url' => ['titulos/firmarxml']]);
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
