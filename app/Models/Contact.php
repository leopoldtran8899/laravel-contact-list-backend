<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $fillable = [
        'first_name',
        'last_name',
        'emergency_name',
        'emergency_phone',
        'phone',
        'address',
        'supervisor_id',
        'company_id',
        'email',
    ];
    /**
     * Get User associated with this contact
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
    /**
     * Get notes maded for this contacts
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'contact_id', 'id');
    }
    /**
     * Get the company that this contact belongs to
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the contact's supervisor
     */
    public function supervisor(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'supervisor_id');
    }
}