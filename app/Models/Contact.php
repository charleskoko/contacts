<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Ramsey\Collection\Collection;

/**
 * Class Contact
 * @package App\Contact
 *
 * @property string $id
 * @property string $last_name
 * @property string $first_name
 * @property string $mobile
 * @property string $email
 * @property User $user
 * @property Collection $address
 */

class Contact extends Model
{
    use Uuids, HasFactory, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
