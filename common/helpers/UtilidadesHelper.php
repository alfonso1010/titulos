<?php

namespace common\helpers;

use Yii;
use yii\httpclient\Client;

class UtilidadesHelper {

    public static function behaviorRbac() {
        return [
            'allow' => true,
            'roles' => ['@'],
            'matchCallback' => function($rule, $action) {
                $module                 = Yii::$app->controller->module->id;
                $action                 = Yii::$app->controller->action->id;
                $controller             = Yii::$app->controller->id;
                $controladorAccion      = "/$controller/$action";
                $controladorCompleto    = "/$controller/*";
                $moduloControladorAccion   = "/$module/$controller/$action";
                $moduloControladorCompleto = "/$module/$controller/*";
                $moduloCompleto = "/$module/*";
                $post = Yii::$app->request->post();
                if (\Yii::$app->user->can($controladorAccion)
                    || \Yii::$app->user->can($controladorCompleto)
                    || \Yii::$app->user->can($moduloControladorCompleto)
                    || \Yii::$app->user->can($moduloCompleto)
                ){
                    return true;
                }
            }
        ];
    }

    public static function generarFirma($key_temp,$nombre_key,$password,$cadena_original){
        $client = new Client();
        $response = $client->createRequest()
        ->setMethod("POST")
        ->addHeaders([
            'content-type' => 'multipart/form-data'
        ])
        ->setUrl("http://localhost:8080/generarFirma")
        ->setData([
            'password' => $password,
            'cadena_original' => $cadena_original
        ])
        ->addFile('files',$key_temp,['fileName' => $nombre_key])
        ->send();
        
        $httpCode = (int)$response->headers['http-code'];
       
        return [
            'response' => $response,
            'code'     => $httpCode
        ];
    }
}

?>