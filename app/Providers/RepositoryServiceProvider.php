<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    CreditRepositoryInterface,
    CustomerRepositoryInterface
};
use App\Repositories\{
    CreditRepository,
    CustomerRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * The application's service providers.
     *
     * @var array
     */
    protected array $providers = [
        CustomerRepositoryInterface::class => CustomerRepository::class,
        CreditRepositoryInterface::class => CreditRepository::class,
    ];

    /**
     * Register the application's service providers.
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->providers as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }
}
