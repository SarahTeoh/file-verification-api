<?php

namespace App\Http\Controllers;

use App\Factories\FileVerificationServiceFactory;
use App\Http\Requests\VerifyFileRequest;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function __construct(private FileVerificationServiceFactory $fileVerificationServiceFactory)
    {
    }

    public function verify(VerifyFileRequest $request)
    {
        // factory class to create service class based on file type 
        $file = $request->file('file');
        $fileVerificationService = $this->fileVerificationServiceFactory->create($file);
        // pass to verify 
        $verificationHistoryDTO = $fileVerificationService->verify($file, Auth::id());

        // return json with status code 200 (use constant or enum for the status code)
    }
}
