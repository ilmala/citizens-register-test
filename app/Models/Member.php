<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'first_name',
        'last_name',
        'tax_id',
    ];

    public function families(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Family::class
        );
    }
}
