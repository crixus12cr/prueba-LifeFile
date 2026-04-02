<?php

namespace App\Http\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    /**
     * Find a customer by their ID.
     *
     * @param int $id The unique identifier of the customer.
     * @return Customer|null Returns the Customer instance if found, or null otherwise.
     */
    public function findById(int $id): ?Customer {
        return Customer::find($id);
    }
}
