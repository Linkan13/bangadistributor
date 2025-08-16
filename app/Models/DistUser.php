<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistUser extends Model
{
    use HasFactory;

    // Table name (optional if Laravel can pluralize correctly)
    protected $table = 'dist_users';

    // Primary key
    protected $primaryKey = 'id';

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
    ];

    /**
     * Relationship: DistUser belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
