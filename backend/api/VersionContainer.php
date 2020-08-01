<?php

namespace backend\api;

use yii\helpers\ArrayHelper;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use tecnocen\oauth2server\filters\auth\CompositeAuth;

class VersionContainer extends \tecnocen\roa\modules\ApiContainer
{
    public function behaviors()
    {
        return ArrayHelper::merge([
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => ['X-Pagination-Per-Page','X-Pagination-Current-Page','X-Pagination-Page-Count','X-Pagination-Total-Count'],
                    'Access-Control-Allow-Headers' => ['header','Authorizations','Origin','Content-Type','Accept','Authorization', 'X-Request-With'],
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::class,
                'oauth2Module' => $this->getOauth2Module(),
                'authMethods' => [
                    ['class' => HttpBearerAuth::class],
                    [
                        'class' => QueryParamAuth::class,
                        // !Important, GET request parameter to get the token.
                        'tokenParam' => 'accessToken',
                    ],
                ],
                 // no requerir token para generar token
                'except' => [
                    'oauth2/*', // the oauth2 module
                    'index/*', // controller that return this module info
                    '*/options', // all OPTIONS actions for CORS preflight
                ]
            ],
        ],parent::behaviors());
    }
    /**
     * @inheritdoc
     */
    public $identityClass = models\User::class;

    /**
     * @inheritdoc
     */
    public $versions = [
        'v1' => ['class' => v1\Version::class]
    ];
}
