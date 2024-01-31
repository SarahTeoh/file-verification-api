<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FileType;
use App\Enums\VerificationResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationHistory extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'file_type',
        'result',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'file_type' => FileType::class,
        'result' => VerificationResult::class,
    ];

    /**
     * Get the user that verified the file.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
