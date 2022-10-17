<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function getCustomerById(int $id): Customer
    {
        return Customer::find($id);
    }
}
