<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    use HasFactory;

    protected $table = 'package_categories';
    public $timestamps = false; // Pivot table doesn't have timestamps by default in my migration unless added

    protected $fillable = ['package_id', 'category_id'];
}
