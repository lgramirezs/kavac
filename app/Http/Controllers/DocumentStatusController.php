<?php

/** Controladores base de la aplicación */
namespace App\Http\Controllers;

use App\Models\DocumentStatus;
use Illuminate\Http\Request;

/**
 * @class DocumentStatusController
 * @brief Gestiona información de los estatus de documentos
 *
 * Controlador para gestionar los estatus de documentos
 *
 * @author Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
 * @license
 *     [LICENCIA DE SOFTWARE CENDITEL](http://conocimientolibre.cenditel.gob.ve/licencia-de-software-v-1-3/)
 */
class DocumentStatusController extends Controller
{
    /**
     * Define la configuración de la clase
     *
     * @method  __construct
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     */
    public function __construct()
    {
        /** Establece permisos de acceso para cada método del controlador */
        $this->middleware('permission:document.status.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:document.status.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:document.status.delete', ['only' => 'destroy']);
        $this->middleware('permission:document.status.list', ['only' => 'index']);
    }

    /**
     * Listado con todos los estatus de los documentos
     *
     * @method    index
     *
     * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @return    JsonResponse      JSON con información de todos los estatus de los documentos
     */
    public function index()
    {
        return response()->json(['records' => DocumentStatus::all()], 200);
    }

    /**
     * Registra un nuevo estatus de documento
     *
     * @method    store
     *
     * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     Request    $request    Objeto con información de la petición
     *
     * @return    JsonResponse     JSON con datos de respuesta a la petición
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:20', 'unique:document_status,name'],
            'description' => ['required'],
            'color' => [
                'required', 'min:4', 'max:30', 'unique:document_status,color',
                'not_in:#FFFFFF,#000000'
            ],
            'action' => ['required', 'unique:document_status,action']
        ], [
            'color.not_in' => __('Color inválido, seleccione un color distinto a blanco o negro')
        ]);

        /** @var DocumentStatus Objeto con los datos del estatus de documentos creado */
        $documentStatus = DocumentStatus::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'action' => $request->action
        ]);

        return response()->json(['record' => $documentStatus, 'message' => 'Success'], 200);
    }

    /**
     * Actualiza la información del estatus de documento
     *
     * @method    update
     *
     * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     Request           $request           Objeto con información de la petición
     * @param     DocumentStatus    $documentStatus    Objeto con información del estatus de documento a actualizar
     *
     * @return    JsonResponse            JSON con información de respuesta a la petición
     */
    public function update(Request $request, DocumentStatus $documentStatus)
    {
        $this->validate($request, [
            'name' => ['required', 'max:20', 'unique:document_status,name,' . $documentStatus->id],
            'description' => ['required'],
            'color' => [
                'required', 'min:4', 'max:30', 'not_in:#FFFFFF,#000000',
                'unique:document_status,color,' . $documentStatus->id
            ],
            'action' => ['required', 'unique:document_status,action,' . $documentStatus->id],
        ]);

        $documentStatus->name = $request->name;
        $documentStatus->description = $request->description;
        $documentStatus->color = $request->color;
        $documentStatus->action = $request->action;
        $documentStatus->save();

        return response()->json(['message' => __('Registro actualizado correctamente')], 200);
    }

    /**
     * Elimina el estatus de documento
     *
     * @method    destroy
     *
     * @author     Ing. Roldan Vargas <rvargas@cenditel.gob.ve> | <roldandvg@gmail.com>
     *
     * @param     DocumentStatus    $documentStatus    Objeto con información del estatus de documento a eliminar
     *
     * @return    JsonResponse      JSON con datos de respuesta sobre el proceso de eliminación
     */
    public function destroy(DocumentStatus $documentStatus)
    {
        $documentStatus->delete();
        return response()->json(['record' => $documentStatus, 'message' => 'Success'], 200);
    }
}
