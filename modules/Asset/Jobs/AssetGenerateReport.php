<?php

namespace Modules\Asset\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Modules\Asset\Pdf\AssetReport as ReportRepository;
use Modules\Asset\Models\AssetReport;
use Modules\Asset\Models\Asset;
use App\Models\Institution;
use App\Models\Parameter;
use Carbon\Carbon;

class AssetGenerateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Objeto que contiene la información asociada a la solicitud
     *
     * @var Object $asset
     */
    protected $data;
    
    /**
     * Plantilla o texto a incluir en el cuerpo del reporte
     *
     * @var String $body
     */
    protected $body;

    /**
     * Título del reporte
     *
     * @var String $title
     */
    protected $title;

    /**
     * Subtítulo o descripción del reporte
     *
     * @var String $subtitle
     */
    protected $subtitle;

    /**
     * Operación a realizar al finalizar el trabajo
     *
     * @var String $operation
     */
    protected $operation;

    /**
     * Variable que contiene el tiempo de espera para la ejecución del trabajo,
     * si no se quiere limite de tiempo, se define en 0
     *
     * @var Integer $timeout
     */
    public $timeout = 0;

    /**
     * Crea una nueva instancia del trabajo
     *
     * @return void
     */
    public function __construct(AssetReport $data, String $body, String $title = null, String $subtitle = '')
    {
        $this->data     = $data;
        $this->body     = $body;
        $this->title    = $title ?? 'Reporte de Bienes';
        $this->subtitle = $subtitle;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->data->type_report == 'general') {
            $assets = Asset::where('institution_id', $this->data->institution_id)->with('institution', 'assetCondition', 'assetStatus');

            /* filtro por periodo de tiempo */
            if ($this->data->start_date || $this->data->end_date) {
                if ($this->data->start_date != '' && !is_null($this->data->start_date)) {
                    if ($this->data->end_date != '' && !is_null($this->data->end_date)) {
                        $assets = $assets->whereBetween("created_at", [$this->data->start_date,$this->data->end_date]);
                    } else {
                        $assets = $assets->whereBetween("created_at", [$this->data->start_date,now()]);
                    }
                }
                if ($this->data->asset_status_id > 0) {
                    $assets = $assets->where('asset_status_id', $this->data->asset_status_id);
                }
            }
            /* filtro por mes y año */
            elseif ($this->data->year || $this->data->mes) {
                if ($this->data->mes != '' && !is_null($this->data->mes)) {
                    if ($this->data->year != '' && !is_null($this->data->year)) {
                        $assets = $assets->whereMonth('created_at', $this->data->mes)
                                     ->whereYear('created_at', $this->data->year);
                    } else {
                        $assets = $assets->whereMonth('created_at', $this->data->mes);
                    }
                }

                if ($this->data->year != '' && !is_null($this->data->year) && $this->data->mes == '') {
                    $assets = $assets->whereYear('created_at', $this->data->year);
                } else {
                    $assets = $assets;
                }

                if ($this->data->asset_status_id > 0) {
                    $assets = $assets->where('asset_status_id', $this->data->asset_status_id);
                }
            } else {
                if ($this->data->asset_status_id > 0) {
                    $assets = $assets->where('asset_status_id', $this->data->asset_status_id);
                } else {
                    $assets = $assets;
                }
            }
            $assets = $assets->get();
        } elseif ($this->data->type_report == 'clasification') {
            $assets = Asset::where('institution_id', $this->data->institution_id)->with('institution', 'assetCondition', 'assetStatus');
            if ($this->data->type_search != '') {
                $assets = $assets->dateclasification(
                    $this->data->start_date,
                    $this->data->end_date,
                    $this->data->mes,
                    $this->data->year_id
                )->get();
            } else if ($this->data->asset_type_id != '') {
                $assets = $assets->where('institution_id', $this->data->institution_id)->CodeClasification(
                    $this->data->asset_type_id,
                    $this->data->asset_category_id,
                    $this->data->asset_subcategory_id,
                    $this->data->asset_specific_category_id
                );

                if ($this->data->asset_status_id > 0) {
                    $assets = $assets->where('asset_status_id', $this->data->asset_status_id);
                }
            } else {
                if ($this->data->asset_status_id > 0) {
                    $assets = $assets->where('asset_status_id', $this->data->asset_status_id);
                } else {
                    $assets = $assets;
                }
            }
            $assets = $assets->get();
        }

        $multi_inst =  Parameter::where('p_key', 'multi_institution')
            ->where('active', true)->first();
        $institution = Institution::where('default', true)
            ->where('active', true)->first();
        $pdf = new ReportRepository();
        
        /*
         *  Definicion de las caracteristicas generales de la página
         */
        $pdf->setConfig(
            [
                'institution' => $institution,
                'urlVerify'   => url(''),
                'orientation' => 'L',
                'filename'    => $this->data->code ? 'asset-report-' . $this->data->code . '.pdf' : 'asset-report-' . Carbon::now() . '.pdf'
            ]
        );

        $pdf->setHeader($this->title, $this->subtitle);
        $pdf->setFooter();
        $pdf->setBody(
            $this->body,
            true,
            [
                'pdf'    => $pdf,
                'assets' => $assets
            ]
        );
    }

    /**
     * Failed the job.
     *
     * @return void
     */
    public function failed()
    {
        $report = AssetReport::find($this->data->id);
        $report->delete();
    }
}
