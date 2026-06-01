<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;
    protected $table = 'job_listings';
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'salary',
        'tags',
        'job_type',
        'remote',
        'requirements',
        'benefits',
        'address',
        'city',
        'state',
        'zipcode',
        'company_name',
        'company_description',
        'company_logo',
        'company_website',
        'contact_phone',
        'contact_email'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //relation to bookmarks
    public function bookmarkedByUser(): BelongsToMany{
        return $this->belongsToMany(User::class, 'job_user_bookmarks')->withTimestamps();
    }

    //relation to applicants
    public function applicants(): HasMany{
        return $this->hasMany(Applicant::class, 'job_id');
    }
}
