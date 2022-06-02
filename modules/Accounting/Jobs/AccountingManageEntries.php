<?php

namespace Modules\Accounting\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Nwidart\Modules\Facades\Module;

use Modules\Accounting\Models\AccountingEntry;
use Modules\Accounting\Models\AccountingEntryable;
use Modules\Accounting\Models\AccountingEntryAccount;
use Modules\Accounting\Models\AccountingEntryCategory;
use Modules\Accounting\Models\Institution;

use App\Models\CodeSetting;
use App\Rules\CodeSetting as CodeSettingRule;

class AccountingManageEntries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Arreglo que contiene la información asociada a la solicitud
     *
     * @var Array $data
     */
    protected $data;

    /**
     * int que contiene la información asociada a la solicitud
     *
     * @var int $institution
     */
    protected $institution_id;

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
    public function __construct(array $data, int $institution_id)
    {
        $this->data = $data;
        $this->institution_id = $institution_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $created_at = now();
        $newEntries = AccountingEntry::where('reference', $this->data['reference'])->first();

        /**
         * Para actualizar
         */
        if ($newEntries) {
            $newEntries->concept                        = $this->data['concept'];
            $newEntries->observations                   = $this->data['observations'];
            $newEntries->accounting_entry_category_id = $this->data['category'];
            $newEntries->institution_id                 = $this->institution_id;
            $newEntries->currency_id                    = $this->data['currency_id'];
            $newEntries->tot_debit                      = $this->data['totDebit'];
            $newEntries->tot_assets                     = $this->data['totAssets'];
            $newEntries->save();
        } else {
            /**
             * Para crear
             */
            $newEntries = AccountingEntry::create([
                    'from_date'                      => $this->data['date'],
                    'reference'                      => ($this->data['reference'])??
                        $this->generateCodeAvailable(),
                    'concept'                        => $this->data['concept'],
                    'observations'                   => $this->data['observations'],
                    'accounting_entry_category_id'   => $this->data['category'],
                    'institution_id'                 => $this->institution_id,
                    'currency_id'                    => $this->data['currency_id'],
                    'tot_debit'                      => $this->data['totDebit'],
                    'tot_assets'                     => $this->data['totAssets'],
                    'approved'                       => false,
                    'created_at'                     => $created_at
                ]);
        }

        $entryAccounts = AccountingEntryAccount::where('accounting_entry_id', $newEntries->id)->get();
        foreach($entryAccounts as $entryAccount){
            $entryAccount->delete();
        }

        foreach ($this->data['accountingAccounts'] as $account) {
            /**
             * Se crea la relación de cuenta a ese asiento si ya existe existe lo actualiza,
             * de lo contrario crea el nuevo registro de cuenta
             */
            AccountingEntryAccount::create([
                    'accounting_entry_id' => $newEntries->id,
                    'accounting_account_id' => $account['id'],
                    'debit' => $account['debit'],
                    'assets' => $account['assets'],
                ]);
        }

        // 
        // Crea relacion morfologica N-M hacia un asiento contable
        // Si no se pasan estos datos no se registra
        // 
        if (array_key_exists('module', $this->data) && array_key_exists('model', $this->data) &&
            array_key_exists('relatable_id', $this->data) && 
            $this->data['module'] && $this->data['model'] && $this->data['relatable_id']) {

            if ((Module::has($this->data['module']))) {
                AccountingEntryable::create([
                    'accounting_entry_id'       => $newEntries->id,
                    'accounting_entryable_type' => $this->data['model'],
                    'accounting_entryable_id'   => $this->data['relatable_id'],
                ]);
            }

        }
    }

    /**
     * [generateCodeAvailable genera el código disponible]
     * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     * @return string [código que se asignara]
     */
    public function generateCodeAvailable()
    {
        $codeSetting = CodeSetting::where('table', 'accounting_entries')
                                    ->first();

        if (!$codeSetting) {
            $codeSetting = CodeSetting::where('table', 'accounting_entries')
                                    ->first();
        }

        if ($codeSetting) {
            $code  = generate_registration_code(
                $codeSetting->format_prefix,
                strlen($codeSetting->format_digits),
                (strlen($codeSetting->format_year) == 2) ? date('y') : date('Y'),
                AccountingEntry::class,
                $codeSetting->field
            );
        } else {
            $code = 'error al generar código de referencia';
        }

        return $code;
    }
}
