<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * Class Contact
 * @package App\Contact
 *
 * @property string $street_number
 * @property int $post_code
 * @property string $country
 * @property string $city
 */

class Address extends Model
{
    use Uuids, HasFactory, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'street_number',
        'post_code',
        'country',
        'city',
    ];


    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
