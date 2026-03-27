<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VoucherUserGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'voucher_user_group_members')
            ->withTimestamps();
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'target_user_group', 'name');
    }
}
