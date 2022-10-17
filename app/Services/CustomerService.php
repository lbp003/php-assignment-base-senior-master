<?php

namespace App\Services;

use App\Repositories\CustomerRepository;

class CustomerService
{
    /**
     * The repair shop repository instance.
     *
     * @var \App\Repositories\CustomerRepository
     */
    protected $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository,
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Handle GET specific customer by id.
     *
     * @param int $id
     * @return \App\Models\Customer $customer
     */
    public function handleGetCustomer(int $id)
    {
        return $this->customerRepository->getCustomerById($id);
    }
}
