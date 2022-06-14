<?php

namespace Modules\DigitalSignature\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Modules\DigitalSignature\Models\Signprofile;
use Modules\DigitalSignature\Models\User;
use Modules\DigitalSignature\Helpers\Helper;

use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @class DigitalSignatureController
 * @brief Controlador para la gestión de firma electrónica
 *
 * Clase que gestiona la firma electrónica
 *
 * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
 * @license <a href='http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/'>
 *              LICENCIA DE SOFTWARE CENDITEL
 *          </a>
 */
class DigitalSignatureController extends Controller
{
    use ValidatesRequests;

    /**
     * Define la configuración inicial de la clase.
     *
     * @author Ing. Argenis Osorio <aosorio@cenditel.gob.ve>
     */
    public function __construct()
    {
        /**
         * Establece permisos de acceso para cada método del controlador
         */
        $this->middleware('permission:digitalsignature.index', ['only' => 'index']);
        $this->middleware('permission:digitalsignature.store', ['only' => 'store']);
        $this->middleware('permission:digitalsignature.update', ['only' => 'update']);
        $this->middleware('permission:digitalsignature.destroy', ['only' => 'destroy']);
        $this->middleware('permission:digitalsignature.sign', ['only' => 'signFileApi']);
        $this->middleware('permission:digitalsignature.verify', ['only' => 'verifySignApi']);
    }

    /**
     * Muestra la ventana principal del módulo Digital signature
     *
     * @method    index
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     *
     * @return  \Illuminate\Http\JsonResponse           Objeto con los registros a mostrar
     */
    public function index()
    {
        /**
         * @var userprofile: datos del usuario con certificado firmante
         * @var certuser: certificado firmante del usuario
         * @var cert: certficado firmante del usuario en una matriz para acceder a sus campos
         * @var fecha: fecha de expiración del certificado firmante del usuario
         */

        if (User::find(auth()->user()->id)->signprofiles) {
            $userprofile = User::find(auth()->user()->id)->signprofiles;
            $certuser = Crypt::decryptString($userprofile['cert']);
            $cert = openssl_x509_parse($certuser);
            $fecha = date('d-m-y H:i:s', $cert['validFrom_time_t']);

            return view('digitalsignature::index', [
                'Identidad' => $cert['subject']['CN'],
                'Verificado' => $cert['issuer']['CN'],
                'Caduca' => $fecha,
                'cert' => 'true',
                'certdetail' => 'false'
            ]);
        }
        return view('digitalsignature::index',[
            'informacion' => 'No posee un certificado firmante',
            'cert' => 'false',
            'certdetail' => 'false'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('digitalsignature::create');
    }

    /**
     * Almacena el certificado del firmante
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return
     */
    public function store(Request $request)
    {
        /**
         * @var filename: nombre del archivo .p12
         * @var path: dirección de almacenaminto del archivo -p12
         * @var certStore: archivo .p12
         * @var passphrase: frase de paso del archivo .p12
         * @var passphraseCrypt: frase de paso encritada
         * @var pkcs12: objeto del certificado firmante
         * @var cert: Certificado del firmante
         * @var pkey: clave privada
         * @var profile: objetivo tipo Signprofile
         */

        $filename = Str::random(10) . '.p12';
        $path = $request->file('pkcs12')->storeAs('',$filename, 'temporary');
        $certStore = file_get_contents(storage_path('temporary') . '/' . $filename);
        $passphrase = $request->get('password');
        if (!$certStore) {
            print_r("Error: No se puede leer el fichero del certificado\n") ;
            exit;
        }

        $pkcs12 = openssl_pkcs12_read($certStore, $certInfo, $passphrase );
        if (!isset($certInfo['cert'])) {
            $request->session()->flash(
                'message',
                [                    
                    'type' => 'other',
                    'title' => 'Alerta',
                    'icon' => 'screen-error',
                    'class' => 'growl-danger',
                    'text' => 'Contraseña incorrecta'

                ]
            );
            return redirect()->route('digitalsignature');
        }
        $cert = Crypt::encryptString($certInfo['cert']);
        $pkey = Crypt::encryptString($certInfo['pkey']);
        $passphraseCrypt = Crypt::encryptString($passphrase);


        $profile = new Signprofile();
        $profile->cert = $cert;
        $profile->pkey = $pkey;
        $profile->passphrase = $passphraseCrypt;
        $profile->user_id = Auth::user()->id;
        $profile->save();
        Storage::disk('temporary')->delete($filename);

        $request->session()->flash(
            'message',
            [                    
                'type'     => 'store',
            ]
        );

        return redirect()->route('digitalsignature');
    }

    /**
     * Show the specified resource.
     * @return Renderable
     */
    public function show()
    {
        return view('digitalsignature::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Renderable
     */
    public function edit()
    {
        return view('digitalsignature::edit');
    }

    /**
     * Actualiza el certificado del firmante
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return
     */
    public function update(Request $request)
    {
        /**
         * @var filename: nombre del archivo .p12
         * @var path: dirección de almacenaminto del archivo -p12
         * @var certStore: archivo .p12
         * @var passphrase: frase de paso del archivo .p12
         * @var passphraseCrypt: frase de paso encritada
         * @var pkcs12: objeto del certificado firmante
         * @var cert: Certificado del firmante
         * @var pkey: clave privada
         * @var profile: objetivo tipo Signprofile
         */

        if(User::find(auth()->user()->id)->signprofiles) {
            $userprofile = User::find(auth()->user()->id)->signprofiles;
            $userprofile->delete();
        }

        $filename = Str::random(10) . '.p12';
        $path = $request->file('pkcs12')->storeAs('',$filename, 'temporary');
        $certStore = file_get_contents(storage_path('temporary') . '/' . $filename);
        $passphrase = $request->get('password');
        if (!$certStore) {
            echo "Error: No se puede leer el fichero del certificado\n";
            exit;
        }

        $pkcs12 = openssl_pkcs12_read($certStore, $certInfo, $passphrase );
        if (!isset($certInfo['cert'])) {
            $request->session()->flash(
                'message',
                [                    
                    'type' => 'other',
                    'title' => 'Alerta',
                    'icon' => 'screen-error',
                    'class' => 'growl-danger',
                    'text' => 'Contraseña incorrecta'

                ]
            );
            return redirect()->route('digitalsignature');
        }
        $cert = Crypt::encryptString($certInfo['cert']);
        $pkey = Crypt::encryptString($certInfo['pkey']);
        $passphraseCrypt = Crypt::encryptString($passphrase);

        $profile = new Signprofile();
        $profile->cert = $cert;
        $profile->pkey = $pkey;
        $profile->passphrase = $passphraseCrypt;
        $profile->user_id = Auth::user()->id;
        $profile->save();
        Storage::disk('temporary')->delete($filename);
        $request->session()->flash(
            'message',
            [                    
                'type'     => 'store',
            ]
        );

        return redirect()->route('digitalsignature');
    }

    /**
     * Elimina el certificado del firmante
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return
     */
    public function destroy()
    {
        /**
         * @var userprofile: usuario con certificado firmante
         */
        if(User::find(auth()->user()->id)->signprofiles) {
            $userprofile = User::find(auth()->user()->id)->signprofiles;
            $userprofile->delete();
            session()->flash(
                    'msg',
                    [
                        'autohide' => 'true',
                        'type'     => 'success',
                        'title'    => 'Éxito',
                        'text'     => 'Registro eliminado con éxito.'
                    ]
                );
            return redirect()->route('digitalsignature');
        }

        session()->flash(
            'msg',
            [
                'autohide' => 'true',
                'type'     => 'error',
                'title'    => 'Alerta',
                'text'     => 'El registro fue eliminado previamente.'
            ]
        );
        return redirect()->route('digitalsignature');
    }

    /**
     * Obtiene la información detallada del certificado del firmante
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return array con la informacion del certificado firmante
     */
    public function getCertificate()
    {

        /**
         * @var userprofile: usuario con certificado firmante
         * @var certuser: certificado del usuario
         * @var cert: certficado firmante del usuario en una matriz para acceder a sus campos
         * @var fecha: fecha de expiración del certificado firmante del usuario
         */

        if(User::find(auth()->user()->id)->signprofiles) {

            $userprofile = User::find(auth()->user()->id)->signprofiles;
            $certuser = Crypt::decryptString($userprofile['cert']);
            $cert = openssl_x509_parse($certuser);

            $certificateDetails = (object) [
                'subjCountry' => $cert['subject']['C'],
                'subjState' => $cert['subject']['ST'],
                'subjLocality' => $cert['subject']['L'],
                'subjOrganization' => $cert['subject']['O'],
                'subjUnitOrganization' => $cert['subject']['OU'],
                'subjName' => $cert['subject']['CN'],
                'subjMail' => $cert['subject']['emailAddress'],
                'issCountry' => $cert['issuer']['C'],
                'issState' => $cert['issuer']['ST'],
                'issLocality' => $cert['issuer']['L'],
                'issOrganization' => $cert['issuer']['O'],
                'issUnitOrganization' => $cert['issuer']['OU'],
                'issName' => $cert['issuer']['CN'],
                'issMail' => $cert['issuer']['emailAddress'],
                'version' => $cert['version'],
                'serialNumber' => $cert['serialNumber'],
                'validFrom' => date('d-m-y H:i:s', $cert['validFrom_time_t']),
                'validTo' => date('d-m-y H:i:s', $cert['validTo_time_t']),
                'signatureTypeSN' => $cert['signatureTypeSN'],
                'signatureTypeLN' => $cert['signatureTypeLN'],
                'signatureTypeNID' => $cert['signatureTypeNID'],
            ];
            $fecha = date('d-m-y H:i:s', $cert['validFrom_time_t']);
            //print_r($certificateDetails);
            return response()->json(['records' =>
                                        [
                                            'certificateDetail' => $certificateDetails,
                                            'cert' => 'true',
                                            'certdetail' => 'true',
                                            'Identidad' => $cert['subject']['CN'],
                                            'Verificado' => $cert['issuer']['CN'],
                                            'Caduca' => $fecha
                                        ]
                                    ], 200);
        }
    }

    /**
     * Realiza la firma electrónica de un documento
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return Nombre del documento pdf firmado
     */
    public function signFile(Request $request)
    {
        /**
         * @var filename: nombre aleatoria para asignar al documentos pdf
         * @var filenamepdf: nombre del documento pdf a firmar
         * @var path: certficado firmante del usuario en una matriz para acceder a sus campos
         * @var filenamepdfsign: nombre del documento pdf firmado
         * @var getpath: objeto de tipo Helper
         * @var storePdfSign: ruta del documento pdf firmado obtenido de una función del Helper
         * @var storePdf: ruta del documento pdf a firmar obtenido de una función del Helper
         * @var cert: certificado del firmante
         * @var pkey: clave privada para firmar
         * @var passphrase: frase de paso del archivo .p12
         * @var filenamep12: nombre del archivo .p12
         * @var storeCertificated: ruta del certificado firmante
         * @var createpkcs12: archivo .p12 creado para la firma
         * @var pathPortableSigner: ruta del ejecutable PortableSigner para realizar la firma
         * @var comand: comando para realizar el proceso de firma
         * @var run: respuesta del proceso de firma electrónica
         */

        $this->validate($request, [
            'pdf' => ['required','mimes:pdf']
        ]);

        if(Auth::user()) {
            if(User::find(auth()->user()->id)->signprofiles) {

                //Documento pdf
                $filename = Str::random(10);
                $filenamepdf = $filename . '.pdf';
                $path = $request->file('pdf')->storeAs('',$filenamepdf, 'temporary');
                $filenamepdfsign = $filename . '-sign.pdf';
                $getpath = new Helper();
                $storePdfSign = $getpath->getPathSign($filenamepdfsign);
                $storePdf = $getpath->getPathSign($filenamepdf);


                //Crear archivo pkcs#12
                $cert = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['cert']);
                $pkey = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['pkey']);
                //$passphrase = Str::random(10);
                $passphrase = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['passphrase']);

                //Datos para la firma
                $filenamep12 = Str::random(10) . '.p12';
                $storeCertificated = $getpath->getPathSign($filenamep12);
                $createpkcs12 = openssl_pkcs12_export_to_file($cert,$storeCertificated,$pkey,$passphrase);
                $pathPortableSigner = $getpath->getPathSign('PortableSigner');

                //ejecución del comando para firmar
                $comand = 'java -jar ' . $pathPortableSigner . ' -n -t ' . $storePdf . ' -o ' . $storePdfSign . ' -s ' . $storeCertificated . ' -p ' . $passphrase;
                $run = exec($comand, $output);

                //enlace para descargar el documento PDF
                $pathDownload = asset('storage/temporary/'.$filenamepdfsign);
                $headers = array(
                     'Content-Type: application/pdf',
                   );

                //elimina el certficado .p12
                Storage::disk('temporary')->delete($filenamep12);

                //elimina el documento pdf
                Storage::disk('temporary')->delete($filenamepdf);

                $previousUrl = app('url')->previous(); //obtiene el nombre de la ruta

                $routeAction = $request->route()->getName();

                return view('digitalsignature::viewSignfile', ['msg' => "El documento fue firmado exitosamente",
                                            'namefile' => $filenamepdfsign,
                                            'signfile' => 'true']);
            }
            return redirect()->route('fileprofile');
        }
        return redirect()->route('login');
    }

    /**
     * Verifica la firma electrónica de un documento
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return json con el detalle de la verificación de la firma
     */
    public function verifySign(Request $request) {

        /**
         * @var filename: nombre aleatoria para asignar al documentos pdf
         * @var namepdfsign: nombre del documento pdf a verificar firma
         * @var path: ruta del documento a verificar firma
         * @var getpath: objeto de tipo Helper
         * @var storePdfSign: ruta del documento a verificar obtenido de una función del Helper
         * @var comand: comando para realizar el proceso de firma
         * @var run: respuesta del proceso de firma electrónica
         */

        $this->validate($request, [
            'pdf' => ['required','mimes:pdf']
        ]);
        //Documento pdf
        $filename = Str::random(10);
        $namepdfsign = $filename . '.pdf';
        $path = $request->file('pdf')->storeAs('',$namepdfsign, 'temporary');

        $getpath = new Helper();
        $storePdfSign = $getpath->getPathSign($namepdfsign);

        //ejecución del comando para firmar
        $comand = 'pdfsig ' . $storePdfSign;
        $run = exec($comand, $output);

        //elimina el documento pdf a verificar la firma electrónica
        Storage::disk('temporary')->delete($namepdfsign);


        if(count($output) == 1 ) {
            $infoVerify = array();
            array_push($infoVerify, "El documento seleccionado no contiene firma electrónica");
            $json_test = json_encode($infoVerify);
            return view( 'digitalsignature::viewVerifySignfile', ['verifyFile' => "true", 'json_test' => $json_test]);
        }
        else {
            $respVerify = new Helper();
            $json_test = json_encode($respVerify->getRespVerify($output));

            return view( 'digitalsignature::viewVerifySignfile', ['verifyFile' => "true", 'json_test' => $json_test]);
        }
     }

    /**
     * Lista los usuarios que asociado certificado firmante
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return lista de los certificado
     */
    public function listCertificate() {

        $users = User::all();
        $userlist = [];

        foreach ($users as $user) {
            $profile = User::find($user->id);
            if ($profile->signprofiles) {
                print_r('############');
                print_r($user->name);
                print_r('############');
                print_r($user->email);
                print_r('############');
                print_r(Crypt::decryptString($profile->signprofiles['cert']));
            }
        }
    }

    /**
     * Funcion que descargar documentos firmado
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return enlace para descargar la documento PDF firmado
     */
    function getFile($filename)
    {
        return response()->download(storage_path("temporary/{$filename}"));
    }

    /**
     * Funcion para el API
    */

   /**
     * Realiza la firma electrónica de un documento para los componentes
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return json
     */
    public function signFileApi(Request $request)
    {
        /**
         * @var filename: nombre aleatoria para asignar al documentos pdf
         * @var filenamepdf: nombre del documento pdf a firmar
         * @var path: certficado firmante del usuario en una matriz para acceder a sus campos
         * @var filenamepdfsign: nombre del documento pdf firmado
         * @var getpath: objeto de tipo Helper
         * @var storePdfSign: ruta del documento pdf firmado obtenido de una función del Helper
         * @var storePdf: ruta del documento pdf a firmar obtenido de una función del Helper
         * @var cert: certificado del firmante
         * @var pkey: clave privada para firmar
         * @var passphrase: frase de paso del archivo .p12
         * @var filenamep12: nombre del archivo .p12
         * @var storeCertificated: ruta del certificado firmante
         * @var createpkcs12: archivo .p12 creado para la firma
         * @var pathPortableSigner: ruta del ejecutable PortableSigner para realizar la firma
         * @var comand: comando para realizar el proceso de firma
         * @var run: respuesta del proceso de firma electrónica
         */

        if(Auth::user()) { //usuario autenticado

            // Si tiene un certificado firmante almacenado
            if(User::find(auth()->user()->id)->signprofiles) {

                if ($request->file('pdf')) {
                    //Documento pdf
                    $filename = Str::random(10);
                    $filenamepdf = $filename . '.pdf';
                    $path = $request->file('pdf')->storeAs('',$filenamepdf, 'temporary');
                    $filenamepdfsign = $filename . '-sign.pdf';
                    $getpath = new Helper();
                    $storePdfSign = $getpath->getPathSign($filenamepdfsign);
                    $storePdf = $getpath->getPathSign($filenamepdf);


                    //Crear archivo pkcs#12
                    $cert = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['cert']);
                    $pkey = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['pkey']);
                    //$passphrase = Str::random(10);
                    $passphrase = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['passphrase']);

                    //Datos para la firma
                    $filenamep12 = Str::random(10) . '.p12';
                    $storeCertificated = $getpath->getPathSign($filenamep12);
                    $createpkcs12 = openssl_pkcs12_export_to_file($cert,$storeCertificated,$pkey,$passphrase);
                    $pathPortableSigner = $getpath->getPathSign('PortableSigner');

                    //ejecución del comando para firmar
                    $comand = 'java -jar ' . $pathPortableSigner . ' -n -t ' . $storePdf . ' -o ' . $storePdfSign . ' -s ' . $storeCertificated . ' -p ' . $passphrase;
                    $run = exec($comand, $output);

                    //enlace para descargar el documento PDF
                    $pathDownload = asset('storage/temporary/'.$filenamepdfsign);
                    $headers = array(
                         'Content-Type: application/pdf',
                       );

                    //elimina el certficado .p12
                    Storage::disk('temporary')->delete($filenamep12);

                    //elimina el documento pdf
                    Storage::disk('temporary')->delete($filenamepdf);

                    $previousUrl = app('url')->previous(); //obtiene el nombre de la ruta

                    $routeAction = $request->route()->getName();

                    return response()->json(['msg' => "El documento fue firmado exitosamente",
                                            'namefile' => $filenamepdfsign,
                                            'signfile' => 'true']);
                }
                return response()->json(['msg' => "Seleccione un documento PDF"]);

            }
            return redirect()->route('fileprofile');
        }

        return redirect()->route('login');
    }

    /**
     * Verifica la firma electrónica de un documento pdf para los componentes
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return json con el detalle de la verificación de la firma
     */
    public function verifySignApi(Request $request) {

        /**
         * @var filename: nombre aleatoria para asignar al documentos pdf
         * @var namepdfsign: nombre del documento pdf a verificar firma
         * @var path: ruta del documento a verificar firma
         * @var getpath: objeto de tipo Helper
         * @var storePdfSign: ruta del documento a verificar obtenido de una función del Helper
         * @var comand: comando para realizar el proceso de firma
         * @var run: respuesta del proceso de firma electrónica
         */

        $this->validate($request, [
            'pdf' => ['required','mimes:pdf']
        ]);

        //Documento pdf
        $filename = Str::random(10);
        $namepdfsign = $filename . '.pdf';
        $path = $request->file('pdf')->storeAs('', $namepdfsign, 'temporary');

        $getpath = new Helper();
        $storePdfSign = $getpath->getPathSign($namepdfsign);

        //ejecución del comando para firmar
        $comand = 'pdfsig ' . $storePdfSign;
        $run = exec($comand, $output);

        //elimina el documento pdf a verificar la firma electrónica
        Storage::disk('temporary')->delete($namepdfsign);


        if(count($output) == 1 ) {
            $infoVerify = array();
            array_push($infoVerify, 'El documento seleccionado no contiene firma electrónica');
            $records = json_encode($infoVerify, JSON_UNESCAPED_UNICODE);

            return response()->json(['verifyFile' => "false", 'records' => $records]);
        }

        $respVerify = new Helper();
        $records = json_encode($respVerify->getRespVerify($output), JSON_UNESCAPED_UNICODE);

        return response()->json(['verifyFile' => "true", 'records' => $records]);
    }

    /**
     * Metodo para validar la autenticación del usuario y autorizar la ejecución
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return json
     */
    public function validateAuth($passphrase) {
        if(Auth::user()) {
            if(User::find(auth()->user()->id)->signprofiles) {
                //$passphrase = 123456;
                //$passphraseCompare = Crypt::encryptString($passphrase);  
                $passphraseOrigin = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['passphrase']);

                if($passphrase == $passphraseOrigin) {
                    return response()->json([
                        'authorization' => 'true',
                        'msg' => "Autenticación validada"]);
                }
                else {
                    return response()->json([
                        'validate' => 'false',
                        'msg' => "Autenticación invalidada"]);
                }
            }
            return redirect()->route('fileprofile');
        }
        return redirect()->route('login');
    }

    /**
     * Metodo para validar la autenticación del usuario
     *
     * @author Pedro Buitrago <pbuitrago@cenditel.gob.ve> | <pedrobui@gmail.com>
     * @return json
     */
    public function validateAuthApi(Request $request) {
        if(Auth::user()) {
            if(User::find(auth()->user()->id)->signprofiles) {

                $passphrase = $request->get('passphrase');
                $passphraseOrigin = Crypt::decryptString(User::find(auth()->user()->id)->signprofiles['passphrase']);

                if($passphrase == $passphraseOrigin) {
                    return response()->json([
                        'auth' => true,
                        'msg' => "Autenticación validada"
                    ]);
                } 
                return response()->json([
                    'auth' => false,
                    'msg' => "La contraseña del certificado no concuerda con la guardada"
                ]);
            }
            return redirect()->route('fileprofile');
        }
        return redirect()->route('login');
    }
}

