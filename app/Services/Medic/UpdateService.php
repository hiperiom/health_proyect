<?php

namespace App\Services\Medic;

use App\Models\Medic;

class UpdateService
{
    /**
     * Update the specified Medic
     *
     * @param Medic $modelInstance
     * @param array $data
     * @return Medic
     */
    public function execute(Medic $modelInstance, array $data): Medic
    {
        $modelInstance->update($data);
        return $modelInstance->fresh();
    }
}