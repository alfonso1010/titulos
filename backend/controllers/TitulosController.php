<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use common\models\formularios\GenerarXmlForm;
use common\models\formularios\FirmaTituloForm;
use yii\web\UploadedFile;
use PHPExcel_Reader_Exception;
use PHPExcel_Worksheet as Worksheet;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Importaciones;
use common\models\TituloElectronico;
use common\models\Institucion;
use common\models\Responsables;
use common\models\Carrera;
use common\models\Profesionista;
use common\models\Expedicion;
use common\models\Antecedente;
use common\helpers\UtilidadesHelper;

/**
 * BuzonController implements the CRUD actions for Buzon model.
 */
class TitulosController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [];
        
        $behaviors['access'] = [
            'class' => \yii\filters\AccessControl::className(),
            'rules' => [
                \common\helpers\UtilidadesHelper::behaviorRbac()
            ],
        ];
        return $behaviors;
    }

 
    public function actionFirmarxml()
    {
        $busca_instituciones =  ArrayHelper::map(
            Institucion::find()
            ->all(),
            'cveInstitucion',
            'nombreInstitucion'
        );
        $formulario = new FirmaTituloForm();
         return $this->render('firmarxml',[
            'busca_instituciones' => $busca_instituciones,
            'formulario' => $formulario
        ]);

    }

    public function actionDescargaxml()
    {
        $formulario = new FirmaTituloForm();
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $formulario->load($data);
            $alumnos = trim($formulario->alumnos,",");
            $arr_alumnos = explode(",", $alumnos);
            $nombre_zip = "";
            if(count($arr_alumnos) > 1){
                $zip = new \ZipArchive;
                $nombre_zip = 'xml/'.date("H:i:s").'_titulos.zip';
                if (!$zip->open($nombre_zip, \ZipArchive::CREATE) === TRUE){
                    \Yii::$app->session->setFlash('error', 'Error al crear zip');
                    return $this->redirect(['titulos/firmarxml']);
                }
            }
            foreach ($arr_alumnos as $key => $curp) {
                $profesionista = Profesionista::findOne(['curp' => $curp]);
                if(is_null($profesionista)){
                    \Yii::$app->session->setFlash('error', 'No existe el profesionista');
                    return $this->redirect(['titulos/firmarxml']);
                }
                $titulo_electronico = TituloElectronico::findOne(['curpProfesionista' => $curp]);
                if(is_null($titulo_electronico)){
                    \Yii::$app->session->setFlash('error', 'No existe Información de título electrónico');
                    return $this->redirect(['titulos/firmarxml']);
                }
                $carrera = Carrera::findOne(['cveCarrera' => $profesionista->cveCarrera]);
                if(is_null($carrera)){
                    \Yii::$app->session->setFlash('error', 'No existe la carrera del profesionista');
                    return $this->redirect(['titulos/firmarxml']);
                }
                $institucion = Institucion::findOne(['cveInstitucion' => $carrera->cveInstitucion]);
                if(is_null($institucion)){
                    \Yii::$app->session->setFlash('error', 'No existe las institución del profesionista');
                    return $this->redirect(['titulos/firmarxml']);
                }
                $responsables = Responsables::findAll(['cveInstitucion' => $institucion->cveInstitucion]);
                if(empty($responsables)){
                    \Yii::$app->session->setFlash('error', 'No existen responsables para la institución');
                    return $this->redirect(['titulos/firmarxml']);
                }
                $expedicion = Expedicion::findOne(['curpProfesionista' => $curp]);
                if(is_null($expedicion)){
                    \Yii::$app->session->setFlash('error', 'No existe el expedicion para el profesionista');
                    return $this->redirect(['titulos/firmarxml']);
                }
                $antecedente = Antecedente::findOne(['curpProfesionista' => $curp]);
                if(is_null($antecedente)){
                    \Yii::$app->session->setFlash('error', 'No existe el antecedente para el profesionista');
                    return $this->redirect(['titulos/firmarxml']);
                }



                $xw = xmlwriter_open_memory();
                xmlwriter_set_indent($xw, 1);
                $res = xmlwriter_set_indent_string($xw, ' ');
                xmlwriter_start_document($xw, '1.0', 'UTF-8');
                // A first element
                xmlwriter_start_element($xw, 'TituloElectronico');
                xmlwriter_start_attribute($xw, 'xmlns');
                    xmlwriter_text($xw,$titulo_electronico->xlmns);
                xmlwriter_end_attribute($xw);
                xmlwriter_start_attribute($xw, 'version');
                    xmlwriter_text($xw,$titulo_electronico->version);
                xmlwriter_end_attribute($xw);
                xmlwriter_start_attribute($xw, 'folioControl');
                    xmlwriter_text($xw,$titulo_electronico->folioControl);
                xmlwriter_end_attribute($xw);
                    // Start a child element
                    xmlwriter_start_element($xw, 'FirmaResponsables'); //INICA RESPONSABLES
                    foreach ($responsables as $r => $responsable) {
                        if($r == 0){
                            $cadena_original = "||1.0|$titulo_electronico->folioControl|$responsable->curp|$responsable->idCargo|$responsable->curp||$institucion->cveInstitucion|$institucion->nombreInstitucion|$carrera->cveCarrera|$carrera->nombreCarrera||$profesionista->fechaTerminacion|$carrera->idAutorizacionReconocimiento|$carrera->autorizacionReconocimiento||$profesionista->curp|$profesionista->nombre|$profesionista->primerApellido||$profesionista->correoElectronico|$expedicion->fechaExpedicion|$expedicion->idModalidadTitulacion|$expedicion->modalidadTitulacion|||$expedicion->cumplioServicioSocial|$expedicion->idFundamentoLegalServicioSocial|$expedicion->fundamentoLegalServicioSocial|$expedicion->idEntidadFederativa|$expedicion->entidadFederativa|$antecedente->institucionProcedencia|$antecedente->idTipoEstudioAntecedente|$antecedente->tipoEstudioAntecedente|$antecedente->idEntidadFederativa|$antecedente->entidadFederativa||$antecedente->fechaTerminacion|||";

                            $formulario->archivo_cer1 = UploadedFile::getInstance($formulario, 'archivo_cer1');
                            $formulario->archivo_key1 = UploadedFile::getInstance($formulario, 'archivo_key1');
                            $tmp_file1_cer = $formulario->archivo_cer1->tempName;
                            $tmp_file1_key = $formulario->archivo_key1->tempName;
                            $nombre_key = $formulario->archivo_key1->name;
                            $cer_file = file_get_contents($tmp_file1_cer);
                            $cer_base64 = base64_encode($cer_file);
                            $respuesta = UtilidadesHelper::generarFirma($tmp_file1_key,$nombre_key,$formulario->password1,$cadena_original);
                            if($respuesta['code'] != 200 | !isset($respuesta['response']->data)){
                                \Yii::$app->session->setFlash('error', 'Ocurrió un error al generar la firma, Posible causa: Contraseña de .key incorrecta');
                                return $this->redirect(['titulos/firmarxml']);
                            }
                            $data = $respuesta['response']->data;
                            if(!isset($data['firma'])){
                                \Yii::$app->session->setFlash('error', 'Ocurrió un error al generar la firma, Posible causa: Contraseña de .key incorrecta');
                                return $this->redirect(['titulos/firmarxml']);
                            }

                            xmlwriter_start_element($xw, 'FirmaResponsable');
                                xmlwriter_start_attribute($xw, 'nombre');
                                    xmlwriter_text($xw,$responsable->nombre);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'primerApellido');
                                    xmlwriter_text($xw,$responsable->primerApellido);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'segundoApellido');
                                    xmlwriter_text($xw,$responsable->segundoApellido);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'curp');
                                    xmlwriter_text($xw,$responsable->curp);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'idCargo');
                                    xmlwriter_text($xw,$responsable->idCargo);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'cargo');
                                    xmlwriter_text($xw,$responsable->cargo);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'abrTitulo');
                                    xmlwriter_text($xw,$responsable->abrTitulo);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'sello');
                                    xmlwriter_text($xw,$data['firma']);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'certificadoResponsable');
                                    xmlwriter_text($xw,$cer_base64);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'noCertificadoResponsable');
                                    xmlwriter_text($xw,$responsable->noCertificadoResponsable);
                                xmlwriter_end_attribute($xw);
                            xmlwriter_end_element($xw);

                        }else if($r == 1){
                            $cadena_original = "||1.0|$titulo_electronico->folioControl|$responsable->curp|$responsable->idCargo|$responsable->curp||$institucion->cveInstitucion|$institucion->nombreInstitucion|$carrera->cveCarrera|$carrera->nombreCarrera||$profesionista->fechaTerminacion|$carrera->idAutorizacionReconocimiento|$carrera->autorizacionReconocimiento||$profesionista->curp|$profesionista->nombre|$profesionista->primerApellido||$profesionista->correoElectronico|$expedicion->fechaExpedicion|$expedicion->idModalidadTitulacion|$expedicion->modalidadTitulacion|||$expedicion->cumplioServicioSocial|$expedicion->idFundamentoLegalServicioSocial|$expedicion->fundamentoLegalServicioSocial|$expedicion->idEntidadFederativa|$expedicion->entidadFederativa|$antecedente->institucionProcedencia|$antecedente->idTipoEstudioAntecedente|$antecedente->tipoEstudioAntecedente|$antecedente->idEntidadFederativa|$antecedente->entidadFederativa||$antecedente->fechaTerminacion|||";
                            $formulario->archivo_cer2 = UploadedFile::getInstance($formulario, 'archivo_cer2');
                            $formulario->archivo_key2 = UploadedFile::getInstance($formulario, 'archivo_key2');
                            $tmp_file2_cer = $formulario->archivo_cer2->tempName;
                            $tmp_file2_key = $formulario->archivo_key2->tempName;
                            $nombre_key = $formulario->archivo_key2->name;
                            $cer_file = file_get_contents($tmp_file2_cer);
                            $cer_base64 = base64_encode($cer_file);
                            $respuesta = UtilidadesHelper::generarFirma($tmp_file2_key,$nombre_key,$formulario->password2,$cadena_original);
                            if($respuesta['code'] != 200 | !isset($respuesta['response']->data)){
                                \Yii::$app->session->setFlash('error', 'Ocurrió un error al generar la firma, Posible causa: Contraseña de .key incorrecta');
                                return $this->redirect(['titulos/firmarxml']);
                            }
                            $data = $respuesta['response']->data;
                            if(!isset($data['firma'])){
                                \Yii::$app->session->setFlash('error', 'Ocurrió un error al generar la firma, Posible causa: Contraseña de .key incorrecta');
                                return $this->redirect(['titulos/firmarxml']);
                            }

                            xmlwriter_start_element($xw, 'FirmaResponsable');
                                xmlwriter_start_attribute($xw, 'nombre');
                                    xmlwriter_text($xw,$responsable->nombre);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'primerApellido');
                                    xmlwriter_text($xw,$responsable->primerApellido);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'segundoApellido');
                                    xmlwriter_text($xw,$responsable->segundoApellido);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'curp');
                                    xmlwriter_text($xw,$responsable->curp);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'idCargo');
                                    xmlwriter_text($xw,$responsable->idCargo);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'cargo');
                                    xmlwriter_text($xw,$responsable->cargo);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'abrTitulo');
                                    xmlwriter_text($xw,$responsable->abrTitulo);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'sello');
                                    xmlwriter_text($xw,$data['firma']);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'certificadoResponsable');
                                    xmlwriter_text($xw,$cer_base64);
                                xmlwriter_end_attribute($xw);
                                xmlwriter_start_attribute($xw, 'noCertificadoResponsable');
                                    xmlwriter_text($xw,$responsable->noCertificadoResponsable);
                                xmlwriter_end_attribute($xw);
                            xmlwriter_end_element($xw);
                        }
                    }
                    xmlwriter_end_element($xw); // TERMINA FIRMARESPONSABLES

                    xmlwriter_start_element($xw, 'Institucion'); //INICIA INSTITUCION
                        xmlwriter_start_attribute($xw, 'cveInstitucion');
                            xmlwriter_text($xw,$institucion->cveInstitucion);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'nombreInstitucion');
                            xmlwriter_text($xw,$institucion->nombreInstitucion);
                        xmlwriter_end_attribute($xw);
                    xmlwriter_end_element($xw);//TERMINA INSTITUCION

                    xmlwriter_start_element($xw, 'Carrera'); //INICIA CARRERA
                        xmlwriter_start_attribute($xw, 'cveCarrera');
                            xmlwriter_text($xw,$carrera->cveCarrera);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'nombreCarrera');
                            xmlwriter_text($xw,$carrera->nombreCarrera);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'fechaInicio');
                            xmlwriter_text($xw,$profesionista->fechaInicio);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'fechaTerminacion');
                            xmlwriter_text($xw,$profesionista->fechaTerminacion);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'idAutorizacionReconocimiento');
                            xmlwriter_text($xw,$carrera->idAutorizacionReconocimiento);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'autorizacionReconocimiento');
                            xmlwriter_text($xw,$carrera->autorizacionReconocimiento);
                        xmlwriter_end_attribute($xw);
                    xmlwriter_end_element($xw);//TERMINA CARRERA   

                    xmlwriter_start_element($xw, 'Profesionista'); //INICIA Profesionista
                        xmlwriter_start_attribute($xw, 'curp');
                            xmlwriter_text($xw,$profesionista->curp);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'nombre');
                            xmlwriter_text($xw,$profesionista->nombre);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'primerApellido');
                            xmlwriter_text($xw,$profesionista->primerApellido);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'segundoApellido');
                            xmlwriter_text($xw,$profesionista->segundoApellido);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'correoElectronico');
                            xmlwriter_text($xw,$profesionista->correoElectronico);
                        xmlwriter_end_attribute($xw);
                    xmlwriter_end_element($xw);//TERMINA Profesionista   

                    xmlwriter_start_element($xw, 'Expedicion'); //INICIA Expedicion
                        xmlwriter_start_attribute($xw, 'fechaExpedicion');
                            xmlwriter_text($xw,$expedicion->fechaExpedicion);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'idModalidadTitulacion');
                            xmlwriter_text($xw,$expedicion->idModalidadTitulacion);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'modalidadTitulacion');
                            xmlwriter_text($xw,$expedicion->modalidadTitulacion);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'fechaExamenProfesional');
                            xmlwriter_text($xw,$expedicion->fechaExamenProfesional);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'cumplioServicioSocial');
                            xmlwriter_text($xw,$expedicion->cumplioServicioSocial);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'idFundamentoLegalServicioSocial');
                            xmlwriter_text($xw,$expedicion->idFundamentoLegalServicioSocial);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'fundamentoLegalServicioSocial');
                            xmlwriter_text($xw,$expedicion->fundamentoLegalServicioSocial);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'idEntidadFederativa');
                            xmlwriter_text($xw,$expedicion->idEntidadFederativa);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'entidadFederativa');
                            xmlwriter_text($xw,$expedicion->entidadFederativa);
                        xmlwriter_end_attribute($xw);
                    xmlwriter_end_element($xw);//TERMINA Expedicion   

                    xmlwriter_start_element($xw, 'Antecedente'); //INICIA Antecedente
                        xmlwriter_start_attribute($xw, 'institucionProcedencia');
                            xmlwriter_text($xw,$antecedente->institucionProcedencia);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'idTipoEstudioAntecedente');
                            xmlwriter_text($xw,$antecedente->idTipoEstudioAntecedente);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'tipoEstudioAntecedente');
                            xmlwriter_text($xw,$antecedente->tipoEstudioAntecedente);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'idEntidadFederativa');
                            xmlwriter_text($xw,$antecedente->idEntidadFederativa);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'entidadFederativa');
                            xmlwriter_text($xw,$antecedente->entidadFederativa);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'fechaInicio');
                            xmlwriter_text($xw,$antecedente->fechaInicio);
                        xmlwriter_end_attribute($xw);
                        xmlwriter_start_attribute($xw, 'fechaTerminacion');
                            xmlwriter_text($xw,$antecedente->fechaTerminacion);
                        xmlwriter_end_attribute($xw);
                    xmlwriter_end_element($xw);//TERMINA Antecedente   

                xmlwriter_end_element($xw); // TERMINA TITULO
                xmlwriter_end_document($xw);
                //xmlwriter_text($xw, 'This is a sample text, ä');

                file_put_contents("xml/".$curp.'.xml',xmlwriter_output_memory($xw));
                if(count($arr_alumnos) == 1){
                    return Yii::$app->response->sendFile("xml/".$curp.'.xml');
                }else{
                    $zip->addFile("xml/".$curp.'.xml');
                }
            }
            $zip->close();
            return Yii::$app->response->sendFile($nombre_zip);
        }
    }

    public function actionBuscacarreras($id)
    {
        $carreras = Carrera::find()
            ->where(['cveInstitucion' => $id])
            ->asArray()
            ->all();   
        if(!empty($carreras) ){
            echo "<option value=''>Selecciona Carrera ...</option>";
            foreach($carreras as $model){
                echo "<option value='".$model['cveCarrera']."'>".$model['nombreCarrera']."</option>";
            }
        }
        else{
            echo "<option value=''> Selecciona Carrera ...</option>";
        }
    }

    public function actionBuscaprofesionistas($id)
    {
        $profesionista = Profesionista::find()
            ->where(['cveCarrera' => $id])
            ->asArray()
            ->all();   
        if(!empty($profesionista) ){
            echo "<option value=''>Selecciona Profesionista ...</option>";
            foreach($profesionista as $model){
                echo "<option value='".$model['curp']."-".$model['nombre']." ".$model['primerApellido']." ".$model['segundoApellido']."'>".$model['nombre']." ".$model['primerApellido']." ".$model['segundoApellido']."</option>";
            }
        }
        else{
            echo "<option value=''> Selecciona Profesionista ...</option>";
        }
    }

    /**
     * Revisa archivo antes de cargarlo.
     * @return mixed
     */
    public function actionGenerarxml()
    {
        $formulario = new GenerarXmlForm();
        $formulario->id_importacion = 0;
        $importacion = new Importaciones();
        $array_revision = [];
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $formulario->load($data);
            if( $formulario->id_importacion == 0){
                $formulario->archivo = UploadedFile::getInstance($formulario, 'archivo');
                $ruta_archivo = self::guarda($formulario->archivo);
            }else{
                $busca_importacion = Importaciones::findOne($formulario->id_importacion);
                $ruta_archivo = (!is_null($busca_importacion))?$busca_importacion->ruta_archivo:null;
            }
            
            if(!is_null($ruta_archivo) | $formulario->id_importacion > 0){
                ini_set('memory_limit', -1);
                ini_set('max_execution_time', 9000);
                date_default_timezone_set("America/Mexico_City");
               
                if($formulario->id_importacion == 0){
                    $importacion->importado = 0;
                    $importacion->ruta_archivo = $ruta_archivo;
                    $importacion->save(false);

                    $reader = ReaderFactory::create(Type::XLSX);
                    $reader->open($ruta_archivo);
                    $error = '';
                    $modelReferences = [];
                    $rows = [];
                    $data = [];
                    $array_instituciones = [];
                    $array_carreras = [];
                    $array_profesionistas = [];

                    foreach ($reader->getSheetIterator() as $hoja => $sheet) {
                        foreach ($sheet->getRowIterator() as $row => $value) {
                            if ($row > 1 && $value[0] != ""  ) {
                                if($hoja == 2){
                                //institucion
                                    $cveInstitucion = trim(ArrayHelper::getValue($value, [0], ''));
                                    $nombreInstitucion = trim(ArrayHelper::getValue($value, [1], ''));
                                    $array_instituciones[] = [
                                        'cveInstitucion' => $cveInstitucion, 
                                        'nombreInstitucion' => $nombreInstitucion
                                    ];
                                    

                                }else if($hoja == 5){
                                //carrera
                                    $cveCarrera = trim(ArrayHelper::getValue($value, [0], ''));
                                    $nombreCarrera = trim(ArrayHelper::getValue($value, [1], ''));
                                    $cveInstitucion = trim(ArrayHelper::getValue($value, [5], ''));
                                    $array_carreras[] = [
                                        'cveInstitucion' => $cveInstitucion, 
                                        'cveCarrera' => $cveCarrera,
                                        'nombreCarrera' => $nombreCarrera,
                                    ];
                                }else if($hoja == 6){
                                //profesionista
                                    $curp = trim(ArrayHelper::getValue($value, [0], ''));
                                    $nombre = trim(ArrayHelper::getValue($value, [1], ''));
                                    $cveCarrera = trim(ArrayHelper::getValue($value, [5], ''));
                                    $array_profesionistas[] = [
                                        'cveCarrera' => $cveCarrera,
                                        'curp' => $curp,
                                        'nombre' => $nombre,
                                    ];
                                    
                                }
                            }
                        }
                    }
                    
                    foreach ($array_instituciones as $key => $institucion) {
                        $array_revision[$institucion['cveInstitucion']]['institucion'] = $institucion['nombreInstitucion'];
                        foreach ($array_carreras as $key1 => $carrera) {
                            if($carrera['cveInstitucion'] == $institucion['cveInstitucion']){
                                $array_revision[$institucion['cveInstitucion']]['carreras'][]= $carrera['nombreCarrera'];
                            }
                        }
                    }
                    foreach ($array_carreras as $key => $carrera) {
                        foreach ($array_profesionistas as $key1 => $profesionista) {
                            if($profesionista['cveCarrera'] == $carrera['cveCarrera']){
                                $array_revision[$carrera['cveInstitucion']]['profesionistas'][]= $profesionista['curp']." - ".$profesionista['nombre'];
                            }
                        }
                    }
                }else{
                    $col_institucion = [
                        'cveInstitucion',
                        'nombreInstitucion',
                    ];
                    $col_carrera = [
                        'cveCarrera',
                        'nombreCarrera',
                        'idAutorizacionReconocimiento',
                        'autorizacionReconocimiento',
                        'numeroRvoe',
                        'cveInstitucion'
                    ];
                    $col_titulo_electronico = [
                        'cveInstitucion',
                        'folioControl',
                        'curpProfesionista'
                    ];
                    $col_profesionista = [
                        'curp',
                        'nombre',
                        'primerApellido',
                        'segundoApellido',
                        'correoElectronico',
                        'cveCarrera',
                        'folioControl',
                        'idExpedicion',
                        'fechaInicio',
                        'fechaTerminacion'
                    ];
                    $col_expedicion = [
                        'idExpedicion',
                        'fechaExpedicion',
                        'idModalidadTitulacion',
                        'modalidadTitulacion',
                        'fechaExamenProfesional',
                        'fechaExencionExamenProfesional',
                        'cumplioServicioSocial',
                        'idFundamentoLegalServicioSocial',
                        'fundamentoLegalServicioSocial',
                        'idEntidadFederativa',
                        'entidadFederativa',
                        'curpProfesionista'
                    ];
                    $col_antecedentes = [
                        'institucionProcedencia',
                        'idTipoEstudioAntecedente',
                        'tipoEstudioAntecedente',
                        'idEntidadFederativa',
                        'entidadFederativa',
                        'fechaInicio',
                        'fechaTerminacion',
                        'noCedula',
                        'folioControl',
                        'curpProfesionista'
                    ];
                    $col_responsable1 = [
                        'nombre',
                        'primerApellido',
                        'segundoApellido',
                        'curp',
                        'idCargo',
                        'cargo',
                        'abrTitulo',
                        'sello',
                        'certificadoResponsable',
                        'noCertificadoResponsable',
                        'cveInstitucion'
                    ];
                    $col_responsable2 = [
                        'nombre',
                        'primerApellido',
                        'segundoApellido',
                        'curp',
                        'idCargo',
                        'cargo',
                        'abrTitulo',
                        'sello',
                        'certificadoResponsable',
                        'noCertificadoResponsable',
                        'cveInstitucion'
                    ];
                    $reader = ReaderFactory::create(Type::XLSX);
                    $reader->open($ruta_archivo);

                    $rows_titulo_electronico = [];
                    $data_titulo_electronico = [];
                    $references_titulo_electronico = [];

                    $rows_institucion = [];
                    $data_institucion = [];
                    $references_institucion = [];

                    $rows_responsables = [];
                    $data_responsables = [];
                    $references_responsables1 = [];
                    $references_responsables2 = [];

                    $rows_carrera = [];
                    $data_carrera = [];
                    $references_carrera = [];

                    $rows_profesionista = [];
                    $data_profesionista = [];
                    $references_profesionista = [];

                    $rows_expedicion = [];
                    $data_expedicion = [];
                    $references_expedicion = [];

                    $rows_antecedentes = [];
                    $data_antecedentes = [];
                    $references_antecedentes = [];

                    foreach ($reader->getSheetIterator() as $hoja => $sheet) {
                        foreach ($sheet->getRowIterator() as $row => $value) {
                            if ($row > 1 && $value[0] != ""  ) {
                                if($hoja == 1){
                                //titulo electronico
                                    $cveInstitucion = trim(ArrayHelper::getValue($value, [0], ''));
                                    $folioControl = trim(ArrayHelper::getValue($value, [1], ''));
                                    $curpProfesionista = trim(ArrayHelper::getValue($value, [2], ''));
                                    $data_titulo_electronico[] = [
                                        'cveInstitucion' => $cveInstitucion,
                                        'folioControl' => $folioControl,
                                        'curpProfesionista' => $curpProfesionista
                                    ];

                                    $rows_titulo_electronico['TituloElectronico'] = [
                                        'cveInstitucion' => $cveInstitucion,
                                        'folioControl' => $folioControl,
                                        'curpProfesionista' => $curpProfesionista
                                    ];
                                    $newModel = new TituloElectronico();
                                    $newModel->load($rows_titulo_electronico);
                                    $references_titulo_electronico[$row] = $newModel;
                                }else if($hoja == 2){
                                //institucion
                                    $cveInstitucion = trim(ArrayHelper::getValue($value, [0], ''));
                                    $nombreInstitucion = trim(ArrayHelper::getValue($value, [1], ''));
                                    $data_institucion[] = [
                                        'cveInstitucion' => $cveInstitucion,
                                        'nombreInstitucion' => $nombreInstitucion
                                    ];

                                    $rows_institucion['Institucion'] = [
                                        'cveInstitucion' => $cveInstitucion,
                                        'nombreInstitucion' => $nombreInstitucion
                                    ];
                                    $newModel = new Institucion();
                                    $newModel->load($rows_institucion);
                                    $references_institucion[$row] = $newModel;

                                }else if($hoja == 3){
                                //responsable 1
                                    $nombre = trim(ArrayHelper::getValue($value, [0], ''));
                                    $primerApellido = trim(ArrayHelper::getValue($value, [1], ''));
                                    $segundoApellido = trim(ArrayHelper::getValue($value, [2], ''));
                                    $curp = trim(ArrayHelper::getValue($value, [3], ''));
                                    $idCargo = trim(ArrayHelper::getValue($value, [4], ''));
                                    $cargo = trim(ArrayHelper::getValue($value, [5], ''));
                                    $abrTitulo = trim(ArrayHelper::getValue($value, [6], ''));
                                    $sello = trim(ArrayHelper::getValue($value, [7], ''));
                                    $certificadoResponsable = trim(ArrayHelper::getValue($value, [8], ''));
                                    $noCertificadoResponsable = trim(ArrayHelper::getValue($value, [9], ''));
                                    $cveInstitucion = trim(ArrayHelper::getValue($value, [10], ''));

                                    $data_responsables[] = [
                                        'nombre' => $nombre,
                                        'primerApellido' => $primerApellido,
                                        'segundoApellido' => $segundoApellido,
                                        'curp' => $curp,
                                        'idCargo' => $idCargo,
                                        'cargo' => $cargo,
                                        'abrTitulo' => $abrTitulo,
                                        'sello' => $sello,
                                        'certificadoResponsable' => $certificadoResponsable,
                                        'noCertificadoResponsable' => $noCertificadoResponsable,
                                        'cveInstitucion' => $cveInstitucion
                                    ];

                                    $rows_responsables['Responsables'] = [
                                        'nombre' => $nombre,
                                        'primerApellido' => $primerApellido,
                                        'segundoApellido' => $segundoApellido,
                                        'curp' => $curp,
                                        'idCargo' => $idCargo,
                                        'cargo' => $cargo,
                                        'abrTitulo' => $abrTitulo,
                                        'sello' => $sello,
                                        'certificadoResponsable' => $certificadoResponsable,
                                        'noCertificadoResponsable' => $noCertificadoResponsable,
                                        'cveInstitucion' => $cveInstitucion
                                    ];
                                    $newModel = new Responsables();
                                    $newModel->load($rows_responsables);
                                    $references_responsables1[$row] = $newModel;

                                }else if($hoja == 4){
                                //responsable 2
                                    $nombre = trim(ArrayHelper::getValue($value, [0], ''));
                                    $primerApellido = trim(ArrayHelper::getValue($value, [1], ''));
                                    $segundoApellido = trim(ArrayHelper::getValue($value, [2], ''));
                                    $curp = trim(ArrayHelper::getValue($value, [3], ''));
                                    $idCargo = trim(ArrayHelper::getValue($value, [4], ''));
                                    $cargo = trim(ArrayHelper::getValue($value, [5], ''));
                                    $abrTitulo = trim(ArrayHelper::getValue($value, [6], ''));
                                    $sello = trim(ArrayHelper::getValue($value, [7], ''));
                                    $certificadoResponsable = trim(ArrayHelper::getValue($value, [8], ''));
                                    $noCertificadoResponsable = trim(ArrayHelper::getValue($value, [9], ''));
                                    $cveInstitucion = trim(ArrayHelper::getValue($value, [10], ''));

                                    $data_responsables[] = [
                                        'nombre' => $nombre,
                                        'primerApellido' => $primerApellido,
                                        'segundoApellido' => $segundoApellido,
                                        'curp' => $curp,
                                        'idCargo' => $idCargo,
                                        'cargo' => $cargo,
                                        'abrTitulo' => $abrTitulo,
                                        'sello' => $sello,
                                        'certificadoResponsable' => $certificadoResponsable,
                                        'noCertificadoResponsable' => $noCertificadoResponsable,
                                        'cveInstitucion' => $cveInstitucion
                                    ];

                                    $rows_responsables['Responsables'] = [
                                        'nombre' => $nombre,
                                        'primerApellido' => $primerApellido,
                                        'segundoApellido' => $segundoApellido,
                                        'curp' => $curp,
                                        'idCargo' => $idCargo,
                                        'cargo' => $cargo,
                                        'abrTitulo' => $abrTitulo,
                                        'sello' => $sello,
                                        'certificadoResponsable' => $certificadoResponsable,
                                        'noCertificadoResponsable' => $noCertificadoResponsable,
                                        'cveInstitucion' => $cveInstitucion
                                    ];
                                    $newModel = new Responsables();
                                    $newModel->load($rows_responsables);
                                    $references_responsables2[$row] = $newModel;

                                }else if($hoja == 5){
                                //carrera
                                    $cveCarrera = trim(ArrayHelper::getValue($value, [0], ''));
                                    $nombreCarrera = trim(ArrayHelper::getValue($value, [1], ''));
                                    $idAutorizacionReconocimiento = trim(ArrayHelper::getValue($value, [2], ''));
                                    $autorizacionReconocimiento = trim(ArrayHelper::getValue($value, [3], ''));
                                    $numeroRvoe = trim(ArrayHelper::getValue($value, [4], ''));
                                    $cveInstitucion = trim(ArrayHelper::getValue($value, [5], ''));
                                    $data_carrera[] = [
                                        'cveCarrera' => $cveCarrera,
                                        'nombreCarrera' => $nombreCarrera,
                                        'idAutorizacionReconocimiento' => $idAutorizacionReconocimiento,
                                        'autorizacionReconocimiento' => $autorizacionReconocimiento,
                                        'numeroRvoe' => $numeroRvoe,
                                        'cveInstitucion' => $cveInstitucion
                                    ];

                                    $rows_carrera['Carrera'] = [
                                        'cveCarrera' => $cveCarrera,
                                        'nombreCarrera' => $nombreCarrera,
                                        'idAutorizacionReconocimiento' => $idAutorizacionReconocimiento,
                                        'autorizacionReconocimiento' => $autorizacionReconocimiento,
                                        'numeroRvoe' => $numeroRvoe,
                                        'cveInstitucion' => $cveInstitucion
                                    ];
                                    $newModel = new Carrera();
                                    $newModel->load($rows_carrera);
                                    $references_carrera[$row] = $newModel;
                                    
                                }else if($hoja == 6){
                                //profesionista
                                    $curp = trim(ArrayHelper::getValue($value, [0], ''));
                                    $nombre = trim(ArrayHelper::getValue($value, [1], ''));
                                    $primerApellido = trim(ArrayHelper::getValue($value, [2], ''));
                                    $segundoApellido = trim(ArrayHelper::getValue($value, [3], ''));
                                    $correoElectronico = trim(ArrayHelper::getValue($value, [4], ''));
                                    $cveCarrera = trim(ArrayHelper::getValue($value, [5], ''));
                                    $folioControl = trim(ArrayHelper::getValue($value, [6], ''));
                                    $idExpedicion = trim(ArrayHelper::getValue($value, [7], ''));
                                    $fechaInicio = ArrayHelper::getValue($value, [8], '');
                                    $fechaTerminacion = ArrayHelper::getValue($value, [9], '');
                                    $fechaInicio = (is_object($fechaInicio))?$fechaInicio->format('Y-m-d'):trim($fechaInicio);
                                    $fechaTerminacion = (is_object($fechaTerminacion))?$fechaTerminacion->format('Y-m-d'):trim($fechaTerminacion);
                                    $fechaInicio = (strlen($fechaInicio) > 1)?$fechaInicio:null;
                                    

                                    $data_profesionista[] = [
                                        'curp' => $curp,
                                        'nombre' => $nombre,
                                        'primerApellido' => $primerApellido,
                                        'segundoApellido' => $segundoApellido,
                                        'correoElectronico' => $correoElectronico,
                                        'cveCarrera' => $cveCarrera,
                                        'folioControl' => $folioControl,
                                        'idExpedicion' => $idExpedicion,
                                        'fechaInicio' => $fechaInicio,
                                        'fechaTerminacion' => $fechaTerminacion
                                    ];

                                    $rows_profesionista['Profesionista'] = [
                                        'curp' => $curp,
                                        'nombre' => $nombre,
                                        'primerApellido' => $primerApellido,
                                        'segundoApellido' => $segundoApellido,
                                        'correoElectronico' => $correoElectronico,
                                        'cveCarrera' => $cveCarrera,
                                        'folioControl' => $folioControl,
                                        'idExpedicion' => $idExpedicion,
                                        'fechaInicio' => $fechaInicio,
                                        'fechaTerminacion' => $fechaTerminacion
                                    ];
                                    $newModel = new Profesionista();
                                    $newModel->load($rows_profesionista);
                                    $references_profesionista[$row] = $newModel;
                                    
                                }else if($hoja == 7){
                                //expedicion
                                    $idExpedicion = trim(ArrayHelper::getValue($value, [0], ''));
                                    $fechaExpedicion = ArrayHelper::getValue($value, [1], '');
                                    $idModalidadTitulacion = trim(ArrayHelper::getValue($value, [2], ''));
                                    $modalidadTitulacion = trim(ArrayHelper::getValue($value, [3], ''));
                                    $fechaExamenProfesional = ArrayHelper::getValue($value, [4], '');
                                    $fechaExencionExamenProfesional = ArrayHelper::getValue($value,[5],'');
                                    $cumplioServicioSocial = trim(ArrayHelper::getValue($value, [6], ''));
                                    $idFundamentoLegalServicioSocial = trim(ArrayHelper::getValue($value,[7],''));
                                    $fundamentoLegalServicioSocial = trim(ArrayHelper::getValue($value, [8],''));
                                    $idEntidadFederativa = trim(ArrayHelper::getValue($value, [9], ''));
                                    $entidadFederativa = trim(ArrayHelper::getValue($value, [10], ''));
                                    $curpProfesionista = trim(ArrayHelper::getValue($value, [11], ''));
                                    $fechaExpedicion = (is_object($fechaExpedicion))?$fechaExpedicion->format('Y-m-d'):trim($fechaExpedicion);
                                    $fechaExamenProfesional = (is_object($fechaExamenProfesional))?$fechaExamenProfesional->format('Y-m-d'):trim($fechaExamenProfesional);
                                    $fechaExencionExamenProfesional = (is_object($fechaExencionExamenProfesional))?$fechaExencionExamenProfesional->format('Y-m-d'):trim($fechaExencionExamenProfesional);
                                    $fechaExencionExamenProfesional = (strlen($fechaExencionExamenProfesional) > 1)?$fechaExencionExamenProfesional:null;

                                    $data_expedicion[] = [
                                        'idExpedicion' => $idExpedicion,
                                        'fechaExpedicion' => $fechaExpedicion,
                                        'idModalidadTitulacion' => $idModalidadTitulacion,
                                        'modalidadTitulacion' => $modalidadTitulacion,
                                        'fechaExamenProfesional' => $fechaExamenProfesional,
                                        'fechaExencionExamenProfesional' => $fechaExencionExamenProfesional,
                                        'cumplioServicioSocial' => $cumplioServicioSocial,
                                        'idFundamentoLegalServicioSocial' => $idFundamentoLegalServicioSocial,
                                        'fundamentoLegalServicioSocial' => $fundamentoLegalServicioSocial,
                                        'idEntidadFederativa' => $idEntidadFederativa,
                                        'entidadFederativa' => $entidadFederativa,
                                        'curpProfesionista' => $curpProfesionista
                                    ];

                                    $rows_expedicion['Expedicion'] = [
                                        'idExpedicion' => $idExpedicion,
                                        'fechaExpedicion' => $fechaExpedicion,
                                        'idModalidadTitulacion' => $idModalidadTitulacion,
                                        'modalidadTitulacion' => $modalidadTitulacion,
                                        'fechaExamenProfesional' => $fechaExamenProfesional,
                                        'fechaExencionExamenProfesional' => $fechaExencionExamenProfesional,
                                        'cumplioServicioSocial' => $cumplioServicioSocial,
                                        'idFundamentoLegalServicioSocial' => $idFundamentoLegalServicioSocial,
                                        'fundamentoLegalServicioSocial' => $fundamentoLegalServicioSocial,
                                        'idEntidadFederativa' => $idEntidadFederativa,
                                        'entidadFederativa' => $entidadFederativa,
                                        'curpProfesionista' => $curpProfesionista
                                    ];
                                    $newModel = new Expedicion();
                                    $newModel->load($rows_expedicion);
                                    $references_expedicion[$row] = $newModel;

                                }else if($hoja == 8){
                                //antecedente
                                    $institucionProcedencia = trim(ArrayHelper::getValue($value, [0], ''));
                                    $idTipoEstudioAntecedente = trim(ArrayHelper::getValue($value, [1], ''));
                                    $tipoEstudioAntecedente = trim(ArrayHelper::getValue($value, [2], ''));
                                    $idEntidadFederativa = trim(ArrayHelper::getValue($value, [3], ''));
                                    $entidadFederativa = trim(ArrayHelper::getValue($value, [4], ''));
                                    $fechaInicio = ArrayHelper::getValue($value, [5], '');
                                    $fechaTerminacion = ArrayHelper::getValue($value, [6], '');
                                    $noCedula = trim(ArrayHelper::getValue($value, [7], ''));
                                    $folioControl = trim(ArrayHelper::getValue($value, [8], ''));
                                    $fechaInicio = (is_object($fechaInicio))?$fechaInicio->format('Y-m-d'):trim($fechaInicio);
                                    $fechaTerminacion = (is_object($fechaTerminacion))?$fechaTerminacion->format('Y-m-d'):trim($fechaTerminacion);
                                    $curpProfesionista = trim(ArrayHelper::getValue($value, [9], ''));
                                    $fechaInicio = (strlen($fechaInicio) > 1)?$fechaInicio:null;


                                    $data_antecedentes[] = [
                                        'institucionProcedencia' => $institucionProcedencia,
                                        'idTipoEstudioAntecedente' => $idTipoEstudioAntecedente,
                                        'tipoEstudioAntecedente' => $tipoEstudioAntecedente,
                                        'idEntidadFederativa' => $idEntidadFederativa,
                                        'entidadFederativa' => $entidadFederativa,
                                        'fechaInicio' => $fechaInicio,
                                        'fechaTerminacion' => $fechaTerminacion,
                                        'noCedula' => $noCedula,
                                        'folioControl' => $folioControl,
                                        'curpProfesionista' => $curpProfesionista
                                    ];

                                    $rows_antecedentes['Antecedente'] = [
                                        'institucionProcedencia' => $institucionProcedencia,
                                        'idTipoEstudioAntecedente' => $idTipoEstudioAntecedente,
                                        'tipoEstudioAntecedente' => $tipoEstudioAntecedente,
                                        'idEntidadFederativa' => $idEntidadFederativa,
                                        'entidadFederativa' => $entidadFederativa,
                                        'fechaInicio' => $fechaInicio,
                                        'fechaTerminacion' => $fechaTerminacion,
                                        'noCedula' => $noCedula,
                                        'folioControl' => $folioControl,
                                        'curpProfesionista' => $curpProfesionista
                                    ];
                                    $newModel = new Antecedente();
                                    $newModel->load($rows_antecedentes);
                                    $references_antecedentes[$row] = $newModel;
                                }
                            }
                        }
                    }
                    $error = "";
                    $bandera_error = false;
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($references_institucion as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Institución: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        if(!$bandera_error){
                            Yii::$app->db->createCommand()->batchInsert(
                                'institucion', 
                                $col_institucion, 
                                $data_institucion
                            )->execute();
                        }
                        
                        foreach ($references_carrera as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Carrera: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        if(!$bandera_error){
                            Yii::$app->db->createCommand()->batchInsert(
                                'carrera', 
                                $col_carrera, 
                                $data_carrera
                            )->execute();
                        }

                        foreach ($references_profesionista as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Profesionista: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        if(!$bandera_error){
                            Yii::$app->db->createCommand()->batchInsert(
                                'profesionista', 
                                $col_profesionista, 
                                $data_profesionista
                            )->execute();
                        }
                        
                        foreach ($references_expedicion as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Expedición: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        if(!$bandera_error){
                            Yii::$app->db->createCommand()->batchInsert(
                                'expedicion', 
                                $col_expedicion, 
                                $data_expedicion
                            )->execute();
                        }
                        
                        foreach ($references_antecedentes as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Antecedentes: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        if(!$bandera_error){
                            Yii::$app->db->createCommand()->batchInsert(
                                'antecedente', 
                                $col_antecedentes, 
                                $data_antecedentes
                            )->execute();
                        }
                        
                        foreach ($references_responsables1 as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Responsable 1: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        foreach ($references_responsables2 as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Responsable 2: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        if(!$bandera_error){
                            Yii::$app->db->createCommand()->batchInsert(
                                'responsables', 
                                $col_responsable1, 
                                $data_responsables
                            )->execute();
                        }

                        foreach ($references_titulo_electronico as $newModel) {
                            if(!$newModel->validate()){
                                $error .= "<br>Errores en Título electrónico: <br>";
                                foreach ($newModel->getFirstErrors() as $key => $value) {
                                    $error .= "<li>".$value.'</li>';
                                }
                                $bandera_error = true;
                            }
                        }

                        if(!$bandera_error){
                            Yii::$app->db->createCommand()->batchInsert(
                                'titulo_electronico', 
                                $col_titulo_electronico, 
                                $data_titulo_electronico
                            )->execute();
                        }
                        if(!$bandera_error){
                            $transaction->commit();
                            \Yii::$app->session->setFlash('success', "Información guardada con éxito.");
                            unlink($ruta_archivo);
                            if(!is_null($busca_importacion)){
                                $busca_importacion->delete();
                            }
                        }else{
                            $transaction->rollBack();
                            \Yii::$app->session->setFlash('error', $error);
                            unlink($ruta_archivo);
                            if(!is_null($busca_importacion)){
                                $busca_importacion->delete();
                            }
                            return $this->redirect(['titulos/generarxml']);
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                        unlink($ruta_archivo);
                        if(!is_null($busca_importacion)){
                            $busca_importacion->delete();
                        }
                    } catch (\Throwable $e) {
                        $transaction->rollBack();
                        throw $e;
                        unlink($ruta_archivo);
                        if(!is_null($busca_importacion)){
                            $busca_importacion->delete();
                        }
                    }
                }
                if(isset($importacion->id)){
                    $formulario->id_importacion = $importacion->id;
                }
            }else{
                \Yii::$app->session->setFlash('error', 'Ocurrió un error, por favor verifique la extención del archivo');
            }
        }
        return $this->render('generarxml',[
            'formulario' => $formulario,
            'importacion' => $importacion,
            'array_revision' => $array_revision
        ]);
    }

    /**
     * Funcion que guarda un archivo en una carpeta en especifico, en caso de 
     * que la carpeta no exista, la crea
     * @param array $model Estructura del modelos Archivos
     * @param integer $tipo Tipo de archivo a guardar, ver la lista de tipos en
     * el modelo de Archivos
     * @return string $ruta Ruta del archivo guardado en caso de exito, en caso
     * de error, texto del error
     */
    public function guarda($file) {

        if(!is_null($file)){
            $ruta_carpeta = Yii::getAlias("@webroot/titulos");

            if (!file_exists($ruta_carpeta)) {
                if (!mkdir($ruta_carpeta, 0777, true)) {
                    return "ERROR INTERNO. Fallo al crear las carpeta...";
                }
            }
            $tmp = explode(".", $file->name);
            $extension = end($tmp);
            $unique_name = Yii::$app->security->generateRandomString().".{$extension}";
            $path_save = $ruta_carpeta."/".$unique_name."";
            if ($file->saveAs($path_save)) {
                return $path_save;
            }else{
                return null;
            }   
        }else{
            return null;
        }
        
    }

   

}
