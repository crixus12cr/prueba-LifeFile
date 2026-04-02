<?php

namespace App\Http\Repositories;

use App\Models\Medication;

class MedicationRepository
{
    /**
     * Find a medication by its lot number.
     *
     * @param string $lotNumber The lot number of the medication.
     * @return Medication|null Returns the Medication instance if found, or null otherwise.
     */
    public function findByLotNumber(string $lotNumber): ?Medication {
        return Medication::where('lot_number', $lotNumber)->first();
    }
}
