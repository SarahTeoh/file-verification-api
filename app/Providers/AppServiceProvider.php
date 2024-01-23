<?php

namespace App\Providers;

use App\Factories\FileVerificationServiceFactory;
use App\Repositories\VerificationHistoryRepository;
use App\Rules\ValidRecipientRule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $fileVerificationRules = [
            new ValidRecipientRule(),
            // new ValidIssuerRule(),
            // new ValidSignatureRule(),
        ];
        $this->app->bind(FileVerificationServiceFactory::class, function ($app) use ($fileVerificationRules) {
            return new FileVerificationServiceFactory(
                $app->make(VerificationHistoryRepository::class),
                $fileVerificationRules
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
