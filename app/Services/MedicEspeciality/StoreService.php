<?php

namespace App\Services\MedicEspeciality;

use App\Models\MedicEspeciality;

class StoreService
{
    /**
     * Create a new MedicEspeciality
     *
     * @param array $data
     * @return MedicEspeciality
     */
    public function execute(array $data): MedicEspeciality
    {
        return MedicEspeciality::create($data);
    }
}