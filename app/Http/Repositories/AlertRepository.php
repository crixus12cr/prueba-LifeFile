<?php

namespace App\Http\Repositories;

use App\Models\Alert;

class AlertRepository
{
    /**
     * Create a new alert record.
     *
     * @param array $data The data to create the alert with.
     * @return Alert Returns the newly created Alert instance.
     */
    public function create(array $data): Alert {
        return Alert::create($data);
    }
}
