<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\HandlesFileVerification;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileVerificationRequest;
use App\Http\Resources\VerificationResultResource;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Verify file
     */
    public function __invoke(
        FileVerificationRequest $request,
        HandlesFileVerification $handleFileVerification
    ): VerificationResultResource {
        $file = $request->file('file');

        $userId = 1;
        // TODO: authentication
        // $userId = Auth::id();

        $verificationResultDto = $handleFileVerification($file->getContent(), (int) $userId);

        return new VerificationResultResource($verificationResultDto);
    }
}
