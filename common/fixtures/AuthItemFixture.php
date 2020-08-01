<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AuthItemFixture extends ActiveFixture
{
    public $tableName = 'auth_item';
    public $dataFile = '@common/fixtures/data/authitem.php';
}