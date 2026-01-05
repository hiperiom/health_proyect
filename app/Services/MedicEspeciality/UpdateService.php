<?php

namespace App\Services\MedicEspeciality;

use App\Models\MedicEspeciality;

class UpdateService
{
    /**
     * Update the specified MedicEspeciality
     *
     * @param MedicEspeciality $modelInstance
     * @param array $data
     * @return MedicEspeciality
     */
    public function execute(MedicEspeciality $modelInstance, array $data): MedicEspeciality
    {
        $modelInstance->update($data);
        return $modelInstance->fresh();
    }
}