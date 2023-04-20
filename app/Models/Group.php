<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';
    /**
     * Get all companies belong to this group
     */
    public function companies(): HasMany{
        return $this->hasMany(Company::class, 'group_id', 'id');
    }
}
