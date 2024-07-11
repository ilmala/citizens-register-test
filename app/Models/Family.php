<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Family extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'name',
        'responsible_id',
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Member::class,
        );
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(
            related: Member::class,
            foreignKey: 'responsible_id',
        );
    }
}
