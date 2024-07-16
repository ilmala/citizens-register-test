<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'first_name',
        'last_name',
        'tax_id',
    ];

    public function isMemberOf(Family $family): bool
    {
        return $family->members->contains($this);
    }

    public function families(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Family::class
        )->withPivot('role')
            ->withTimestamps();
    }
}
