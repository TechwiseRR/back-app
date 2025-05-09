<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoProfile extends Model
{
    use HasFactory;

    protected $table = 'info_profiles';

    protected $fillable = [
        'userId',
        'firstName',
        'lastName',
        'address',
        'postalCode',
        'city',
        'country',
        'updateDate',
    ];

    protected $casts = [
        'updateDate' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @var int
     */
    private int $id;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var string
     */
    private string $address;

    /**
     * @var string
     */
    private string $postalCode;

    /**
     * @var string
     */
    private string $city;

    /**
     * @var string
     */
    private string $country;

    /**
     * @var \DateTime
     */
    private \DateTime $updateDate;
}
