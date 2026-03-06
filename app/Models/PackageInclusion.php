<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageInclusion extends Model
{
    use HasFactory;

    protected $fillable = ['package_id', 'item', 'is_included'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
