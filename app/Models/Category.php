<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function getResults($filter = null)
    {
        if(!$filter)
            return $this->get();

        return $this->where('name', 'LIKE', "%{$filter}%")
                ->get();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
