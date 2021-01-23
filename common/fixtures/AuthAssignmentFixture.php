<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AuthAssignmentFixture extends ActiveFixture
{
    public $modelClass = 'common\models\AuthAssignment';
    public $dataFile = '@common/fixtures/data/authassignment.php';
    public $depends = [
        'common\fixtures\AuthItemFixture', 
    ];
    
}