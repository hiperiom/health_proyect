<?php

namespace App\Services\Medic;

use App\Models\Medic;

class StoreService
{
    /**
     * Create a new Medic
     *
     * @param array $data
     * @return Medic
     */
    public function execute(array $data): Medic
    {
        return Medic::create($data);
    }
}