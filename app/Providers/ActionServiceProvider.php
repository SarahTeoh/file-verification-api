<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\CreateVerificationHistory;
use App\Actions\FetchDnsRecord;
use App\Actions\HandleFileVerification;
use App\Actions\ProcessJsonFile;
use App\Actions\Rules\ValidateIssuer;
use App\Actions\Rules\ValidateRecipient;
use App\Actions\Rules\ValidateSignatureHash;
use App\Actions\VerifyFile;
use App\Contracts\Actions\CreatesVerificationHistory;
use App\Contracts\Actions\HandlesFileVerification;
use App\Contracts\Actions\ProcessesFile;
use App\Contracts\Actions\VerifiesFile;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     */
    public array $bindings = [
        ProcessesFile::class => ProcessJsonFile::class,
        HandlesFileVerification::class => HandleFileVerification::class,
        CreatesVerificationHistory::class => CreateVerificationHistory::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $fetchDnsRecord = new FetchDnsRecord();
        $rulesToValidate = [
            new ValidateRecipient(),
            new ValidateIssuer($fetchDnsRecord),
            new ValidateSignatureHash(),
        ];
        $this->app->bind(VerifiesFile::class, function ($app) use ($rulesToValidate) {
            return new VerifyFile($rulesToValidate);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
