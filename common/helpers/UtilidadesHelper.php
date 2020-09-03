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

    public static function enviarTitulo($ambiente,$nombre_archivo,$file_temp,$nombre_file,$usuario,$password){
        $client = new Client();
        $response = $client->createRequest()
        ->setMethod("POST")
        ->setUrl("http://localhost:8080/cargarTitulo")
        ->setData([
            'ambiente' => $ambiente,
            'nombre_archivo' => $nombre_archivo,
            'usuario' => $usuario,
            'password' => $password
        ])
        ->addFile('file',$file_temp,['fileName' => $nombre_file])
        ->send();
        
        $httpCode = (int)$response->headers['http-code'];
       
        return [
            'response' => $response,
            'code'     => $httpCode
        ];
    }

    public static function consultarTitulo($ambiente,$no_lote,$usuario,$password){
        $client = new Client();
        $response = $client->createRequest()
        ->setMethod("POST")
        ->setUrl("http://localhost:8080/consultarTitulo")
        ->setData([
            'ambiente' => $ambiente,
            'no_lote' => $no_lote,
            'usuario' => $usuario,
            'password' => $password
        ])
        ->send();
        $httpCode = (int)$response->headers['http-code'];
       
        return [
            'response' => $response,
            'code'     => $httpCode
        ];
    }

    public static function cancelarTitulo($ambiente,$folio_control,$motivo,$usuario,$password){
        $client = new Client();
        $response = $client->createRequest()
        ->setMethod("POST")
        ->setUrl("http://localhost:8080/cancelarTitulo")
        ->setData([
            'ambiente' => $ambiente,
            'folio_control' => $folio_control,
            'motivo' => $motivo,
            'usuario' => $usuario,
            'password' => $password
        ])
        ->send();
        $httpCode = (int)$response->headers['http-code'];
       
        return [
            'response' => $response,
            'code'     => $httpCode
        ];
    }

     public static function descargarTitulo($ambiente,$no_lote,$usuario,$password){
        $client = new Client();
        $response = $client->createRequest()
        ->setMethod("POST")
        ->setUrl("http://localhost:8080/descargarTitulo")
        ->setData([
            'ambiente' => $ambiente,
            'no_lote' => $no_lote,
            'usuario' => $usuario,
            'password' => $password
        ])
        ->send();
        $httpCode = (int)$response->headers['http-code'];
       
        return [
            'response' => $response,
            'code'     => $httpCode
        ];
    }
}

?>