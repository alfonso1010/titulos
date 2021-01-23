<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AuthItemChildFixture extends ActiveFixture
{
    public $tableName = 'auth_item_child';
    public $dataFile = '@common/fixtures/data/authitemchild.php';
     public $depends = [
        'common\fixtures\AuthAssignmentFixture', 
    ];
}