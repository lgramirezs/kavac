<?php

namespace Modules\Budget\Models\Base;

use Illuminate\Database\Eloquent\Model;
use App\Models\Institution as BaseInstitution;

class Institution extends BaseInstitution
{
    /**
     * Institution has many BudgetSubSpecificFormulations.
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function budget_sub_specific_formulations()
    {
        return $this->hasMany(\Modules\Budget\Models\BudgetSubSpecificFormulation::class);
    }

    /**
     * Institution has many BudgetModification.
     *
     * @author  Ing. Roldan Vargas <rvargas@cenditel.gob.ve | roldandvg@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function budget_modifications()
    {
        return $this->hasMany(\Modules\Budget\Models\BudgetModification::class);
    }
}
