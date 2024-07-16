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

    public function isLedBy(Person $person): bool
    {
        return $this->responsible?->is($person) ?? false;
    }

    public function hasMember(Person $person)
    {
     return $this->members->contains($person);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Person::class,
        )->withPivot('role')
            ->withTimestamps();
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(
            related: Person::class,
            foreignKey: 'responsible_id',
        );
    }
}
