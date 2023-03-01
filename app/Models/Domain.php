<?php

namespace App\Models;

use App\Domains\Auth\Models\User;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use HasFactory;
    use Timestamp;
    use SoftDeletes;

    protected $fillable = [
        'domain',
        'niche',
        'lang',
        'title',
        'ahrefs_dr',
        'ahrefs_traffic',
        'linked_domains',
        'ref_domains',
        'num_organic_keywords_top_10',
        'article_by',
        'price',
        'sponsored_label',
        'type_of_publication',
        'type_of_link',
        'contact_email',
        'contact_form_link',
        'contact_name',
        'additional_notes',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeWithUserAccess(Builder $builder, User $user)
    {
        $builder->with('users', function ($builder) use ($user) {
            $builder->where('user_id', '=', $user->id);
        });
    }

    public function scopeHasUserAccess(Builder $builder, User $user)
    {
        $builder->whereHas('users', function ($builder) use ($user) {
            $builder->where('user_id', '=', $user->id);
        });
    }

    public function scopeApproved(Builder $builder)
    {
        return $builder;
    }

    public function scopeExcludeTest(Builder  $builder)
    {
        $builder->where('domain', 'not like', 'domain%');
    }
}
