<?php

namespace Modules\Asset\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Repositories\UploadImageRepository;
use App\Repositories\UploadDocRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\CodeSetting;
use App\Models\Document;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Modules\Asset\Models\AssetDisincorporationAsset;
use Modules\Asset\Models\AssetDisincorporation;
use Modules\Asset\Models\Asset;
use App\Models\Profile;

use Illuminate\Support\Facades\Log;



/**
 * @class     AssetDisincorporationController
 * @brief     Controlador de las desincorporaciones de bienes institucionales
 *
 * Clase que gestiona las desincorporaciones de bienes institucionales
 *
 * @author     Henry Paredes <hparedes@cenditel.gob.ve>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class AssetDisincorporationController extends Controller
{
    use ValidatesRequests;

    /**
     * Define la configuración de la clase
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->validateRules = [
            'date'                             => ['required'],
            'asset_disincorporation_motive_id' => ['required'],
            'observation'                      => ['required'],
            'files.*'                          => ['required', 'max:5000', 'mimes:jpeg,jpg,png,pdf,docx,doc,odt']
        ];

        /** Define los mensajes de validación para las reglas del formulario */
        $this->messages = [
            'date.required'                              => 'El campo fecha de desincorporación es obligatorio.',
            'asset_disincorporation_motive_id.required'  => 'El campo motivo de la desincorporación es obligatorio.',
            'observation.required'                       => 'El campo observaciones generales es obligatorio.',
            'files.*.required'     => 'El campo adjuntar archivos es obligatorio.',
            'files.*.max'          => 'El campo adjuntar archivos no debe contener más de 5000 caracteres.',
            'files.*.mimes'        => 'El campo adjuntar archivos no permite ese formato.'


           ];
    }

    /**
     * Muestra un listado de las Ddsincorporaciones de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function index()
    {
        return view('asset::disincorporations.list');
    }

    /**
     * Muestra el formulario para registrar una nueva desincorporación de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Renderable
     */
    public function create()
    {
        return view('asset::disincorporations.create');
    }

    /**
    * Valida y registra una nueva desincorporación de bienes institucionales
    *
    * @author    Henry Paredes <hparedes@cenditel.gob.ve>
    * @param     \Illuminate\Http\Request         $request    Datos de la petición
    * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar)
    */
    public function store(Request $request, UploadImageRepository $upImage, UploadDocRepository $upDoc)
    {
        $validateRules  = $this->validateRules;
        $this->validate($request, $validateRules, $this->messages);

        $codeSetting = CodeSetting::where('table', 'asset_disincorporations')->first();
        if (is_null($codeSetting)) {
            $request->session()->flash('message', [
                'type' => 'other', 'title' => 'Alerta', 'icon' => 'screen-error', 'class' => 'growl-danger',
                'text' => 'Debe configurar previamente el formato para el código a generar'
                ]);
            return response()->json(['result' => false, 'redirect' => route('asset.setting.index')], 200);
        }

        $code  = generate_registration_code(
            $codeSetting->format_prefix,
            strlen($codeSetting->format_digits),
            (strlen($codeSetting->format_year) == 2) ? date('y') : date('Y'),
            $codeSetting->model,
            $codeSetting->field
        );

        /**
         * Objeto asociado al modelo AssetDisincorporation
         *
         * @var Object $disincorporation
         */
        $disincorporation = AssetDisincorporation::create([
            'code' => $code,
            'date' => $request->date,
            'asset_disincorporation_motive_id' => $request->asset_disincorporation_motive_id,
            'observation' => $request->observation,
            'user_id' => Auth::id()
        ]);

        $assets = explode(",", $request->assets);
        foreach ($assets as $asset_id) {
            $asset = Asset::find($asset_id);
            $asset->asset_status_id = 11;
            $asset->save();
            $asset_disincorporation = AssetDisincorporationAsset::create([
                'asset_id' => $asset->id,
                'asset_disincorporation_id' => $disincorporation->id,
            ]);
        }

        /** Se guardan los docmentos, según sea el tipo (imágenes y/o documentos)*/
        $documentFormat = ['doc', 'docx', 'pdf', 'odt'];
        $imageFormat    = ['jpeg', 'jpg', 'png'];
        
        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $extensionFile = $file->getClientOriginalExtension();

                if (in_array($extensionFile, $documentFormat)) {
                    $upDoc->uploadDoc(
                        $file,
                        'documents',
                        AssetDisincorporation::class,
                        $disincorporation->id
                    );
                } elseif (in_array($extensionFile, $imageFormat)) {
                    $upImage->uploadImage(
                        $file,
                        'pictures',
                        AssetDisincorporation::class,
                        $disincorporation->id
                    );
                }
            }
        }
        $request->session()->flash('message', ['type' => 'store']);
        return response()->json(['result' => true, 'redirect' => route('asset.disincorporation.index')], 200);
    }


    /**
     * Muestra el formulario para desincorporar un bien institucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Integer                          $id    Identificador único del bien a desincorporar
     * @return    Renderable    Objeto con los registros a mostrar
     */
    public function assetDisassign($id)
    {
        $asset = Asset::find($id);
        return view('asset::disincorporations.create', compact('asset'));
    }

    /**
     * Muestra el formulario para actualizar la información de las desincorporaciones de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Integer                          $id    Identificador único de la desincorporación a editar
     * @return    Renderable    Objeto con los datos a mostrar
     */
    public function edit($id)
    {
        $disincorporation = AssetDisincorporation::find($id);
        return view('asset::disincorporations.create', compact('disincorporation'));
    }
         public function showDocuments($filename)
    {
        if (\Storage::disk('pictures')->exists($filename)) {
            $file = storage_path() . '/pictures/' . $filename;
        } elseif (\Storage::disk('documents')->exists($filename)) {
            $file = storage_path() . '/documents/' . $filename;
        }

        return response()->download($file, $filename, [], 'inline');
    }

       public function getDisincorporationRequestDocuments($id, $all=null)
    {
       
            $AssetDisincorporation = AssetDisincorporation::where(['id' => $id])
            ->with('documents', 'images')->first();
     
        $docs = $AssetDisincorporation->documents ?? null;
        $images = $AssetDisincorporation->images ?? null;
        $records = [];
        if (isset($docs)) {
            if (isset($images)) {
                $records = $docs->merge($images);
            } else {
                $records = $docs;
            }
        } elseif (isset($images)) {
            if (isset($docs)) {
                $records = $images->merge($docs);
            } else {
                $records = $images;
            }
        }
        return response()->json(['records' => $records], 200);
    }
    /**
     * Actualiza la información de las desincorporaciones de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Illuminate\Http\Request         $request    Datos de la petición
     * @param     Integer                          $id         Identificador único de la desincorporación
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function update(Request $request, $id)
    {   
        Log::critical("lol");
        Log::critical($id);

        Log::critical($request);
        $upImage =  new  UploadImageRepository;
      $upDoc = new UploadDocRepository; 
       $disincorporation = AssetDisincorporation::where(['id' => $id])
    ->with('documents', 'images')->first();
        Log::critical($disincorporation);

        //$disincorporation = AssetDisincorporation::find($id);
        $this->validate($request, [
            'date' => ['required'],
            'asset_disincorporation_motive_id' => ['required'],
            'observation' => ['required']

        ],$this->messages);

        $disincorporation->date = $request->date;
        $disincorporation->asset_disincorporation_motive_id = $request->asset_disincorporation_motive_id;
        $disincorporation->observation = $request->observation;
        $disincorporation->save();
          
        /** Se eliminan los demas elementos de la solicitud */
        $assets_disincorporation = AssetDisincorporationAsset::where('asset_disincorporation_id', $disincorporation->id)
            ->get();

        foreach ($assets_disincorporation as $asset_disincorporation) {
            $asset = Asset::find($asset_disincorporation->asset_id);
            $asset->asset_status_id = 10;
            $asset->save();

            $asset_disincorporation->delete();
        }
        /** Se agregan los nuevos elementos a la solicitud */
        /** Se guardan los docmentos, según sea el tipo (imágenes y/o documentos)*/
$documentFormat = ['doc', 'docx', 'pdf', 'odt'];
$imageFormat = ['jpeg', 'jpg', 'png'];

if ($request->has('files')) {
    if(count($disincorporation->documents) > 0 ){
           foreach ($disincorporation->documents as $key) {
               Storage::disk('documents')->delete($key->file);

           }
           Document::where(['documentable_type' => 'Modules\Asset\Models\AssetDisincorporation','documentable_id' => $disincorporation->id])->delete();


    }
    if (count($disincorporation->images) > 0) {
       foreach ($disincorporation->images as $key) {
    Storage::disk('pictures')->delete($key->file);

    }
    Image::where(['imageable_type' => 'Modules\Asset\Models\AssetDisincorporation', 'imageable_id' => $disincorporation->id])->delete();

   }




    foreach ($request->file('files') as $file) {
        $extensionFile = $file->getClientOriginalExtension();
   

        if (in_array($extensionFile, $documentFormat)) {
            $upDoc->uploadDoc(
                $file,
                'documents',
                AssetDisincorporation::class,
                $disincorporation->id
            );
        } elseif (in_array($extensionFile, $imageFormat)) {
            $upImage->uploadImage(
                $file,
                'pictures',
                AssetDisincorporation::class,
                $disincorporation->id
            );
        }
    }
}

        foreach ($request->assets as $asset_id) {
            $asset = Asset::find($asset_id);
            $asset->asset_status_id = 11;
            $asset->save();
            $asset_disincorporation = AssetDisincorporationAsset::Create([
                    'asset_id' => $asset->id,
                    'asset_disincorporation_id' => $disincorporation->id]);

        }

        $request->session()->flash('message', ['type' => 'update']);
        return response()->json(['result' => true, 'redirect' => route('asset.disincorporation.index')], 200);
    }

    /**
     * Elimina una desincorporación de bienes institucionales
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     \Modules\Asset\Models\AssetDisincorporation    $disincorporation    Datos de la desincorporación
     *                                                                                de un bien
     * @return    \Illuminate\Http\JsonResponse                  Objeto con los registros a mostrar
     */
    public function destroy(AssetDisincorporation $disincorporation)
    {
        $assets_disincorporation_assets = AssetDisincorporationAsset::where('asset_disincorporation_id', $disincorporation->id)->get();
        
        foreach($assets_disincorporation_assets as $assets_disincorporation){
            $asset = Asset::find($assets_disincorporation->asset_id);
            $asset->asset_status_id = 10;
            $asset->save();

            $assets_disincorporation->delete();
        }
        $disincorporation->delete();
        return response()->json(['message' => 'destroy'], 200);
    }

    /**
     * Vizualiza la información de la desincorporación de un bien institucional
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @param     Integer                          $id    Identificador único de la desincorporación
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vueInfo($id)
    {
        $disincorporation = AssetDisincorporation::where('id', $id)
            ->with(['assetDisincorporationMotive' ,'assetDisincorporationAssets' =>
            function ($query) {
                $query->with(['asset' =>
                function ($query) {
                    $query->with(
                        'assetType',
                        'assetCategory',
                        'assetSubcategory',
                        'assetSpecificCategory',
                        'assetAcquisitionType',
                        'assetCondition',
                        'assetStatus',
                        'assetUseFunction'
                    );
                }]);
            }])->first();

        return response()->json(['records' => $disincorporation], 200);
    }

    /**
     * Otiene un listado de las desincorporaciones registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    \Illuminate\Http\JsonResponse    Objeto con los registros a mostrar
     */
    public function vueList($perPage = 10, $page = 1)
    {
        $user_profile = Profile::where('user_id', auth()->user()->id)->first();
        $institution_id = isset($user_profile->institution_id)
            ? $user_profile->institution_id
            : null;

        if (Auth()->user()->isAdmin()) {
            $assetDisincorporations = AssetDisincorporation::with('assetDisincorporationMotive');
        } else {
            $assetDisincorporations = AssetDisincorporation::where('institution_id', $institution_id)
                ->with('assetDisincorporationMotive');
        }

        $total = $assetDisincorporations->count();
        $assetDisincorporations = $assetDisincorporations->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $lastPage = max((int) ceil($total / $perPage), 1);
        return response()->json(
            [
                'records'  => $assetDisincorporations,
                'total'    => $total,
                'lastPage' => $lastPage,
            ],
            200
        ); 
    }

    /**
     * Otiene un listado de los motivos de las desincorporaciones registradas
     *
     * @author    Henry Paredes <hparedes@cenditel.gob.ve>
     * @return    Array    Array con los registros a mostrar
     */
    public function getAssetDisincorporationMotives()
    {
        return template_choices('Modules\Asset\Models\AssetDisincorporationMotive', 'name', '', true);
    }
}
