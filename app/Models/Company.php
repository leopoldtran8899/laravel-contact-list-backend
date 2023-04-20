<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id', 'group_id');
    }

    public function contacts(): HasMany {
        return $this->hasMany(Contact::class, 'company_id', 'id');
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Contact::class, 'company_id', 'email', 'id', 'email');
    }

}