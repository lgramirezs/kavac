<?php

namespace Modules\Accounting\Imports;

use Modules\Accounting\Models\AccountingAccount;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccountingAccountImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $code = explode('.', $row['codigo']);

        /** Información de cuenta padre */
        $parent = AccountingAccount::where('group', $code[0]);

        if ($parent) {
            for ($i=1; $i <= 6; $i++) { 
                if ($i == 1 && $code[$i] == 0){
                    $parent->where('subgroup', '0')->first();
                    break;
                }
                else if ($i == 2 && $code[$i] == 0){
                    $parent->where('subgroup', '0')
                        ->where('item', '0')->first();
                    break;
                }
                else if ($i == 3 && $code[$i] == 0){
                    $parent->where('subgroup', $code[1])
                        ->where('item', '0')
                        ->where('generic', '00')->first();
                    break;
                }
                else if ($i == 4 && $code[$i] == 0){
                    $parent->where('subgroup', $code[1])
                        ->where('item', $code[2])
                        ->where('generic', '00')
                        ->where('specific', '00')->first();
                    break;
                }
                else if ($i == 5 && $code[$i] == 0){
                    $parent->where('subgroup', $code[1])
                        ->where('item', $code[2])
                        ->where('generic', $code[3])
                        ->where('specific', '00')
                        ->where('subspecific', '00')->first();
                    break;
                }
                else if ($i == 6 && $code[$i] == 0){
                    $parent->where('subgroup', $code[1])
                        ->where('item', $code[2])
                        ->where('generic', $code[3])
                        ->where('specific', $code[4])
                        ->where('subspecific', '00')
                        ->where('institutional', '000')->first();
                    break;
                }
            }
        }

        $parent_id = $parent ? $parent->id : null;

        if (count($code) == 7) {
            return AccountingAccount::updateOrCreate(
                [
                    'group'         => $code[0],
                    'subgroup'      => $code[1],
                    'item'          => $code[2],
                    'generic'       => $code[3],
                    'specific'      => $code[4],
                    'subspecific'   => $code[5],
                    'institutional' => $code[6],
                ],
                [
                    'parent_id' => $parent_id,
                    'denomination' => $row['denominacion'],
                    'status' => ($row['activa'] == 'SI')?true:false,
                ]
            );
        }
    }

    /**
     * Reglas de validación
     *
     * @method     rules
     *
     * @author  Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @return     Array           Devuelve un arreglo con las reglas de validación
     */
    public function rules(): array
    {
        return [
            '*.codigo'       => ['required'],
            '*.denominacion' => ['required'],
            '*.activa'       => ['required','max:2'],
        ];
    }

    /**
     * Mensajes de validación personalizados
     *
     * @method     customValidationMessages
     *
     * @author  Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
     *
     * @return     array                      Devuelve un arreglo con los mensajes de validación personalizados
     */
    public function customValidationMessages(): array
    {
        return [
            'codigo.required'       => 'Error en la fila :row. El campo :attribute es obligatorio',
            'denominacion.required' => 'Error en la fila :row. El campo :attribute es obligatorio',
            'activa.required'       => 'Error en la fila :row. El campo :attribute es obligatorio',
            'activa.max'            => 'Error en la fila :row. El campo :attribute debe ser de maximo 2 caracteres.',
        ];
    }
}
