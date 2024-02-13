<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getResults($data, $totalPage)
    {
        if(!isset($data['filter']) && !isset($data['name']) && !isset($data['description']))
            return $this->paginate($totalPage);

        return $this->where(function($query) use ($data) {
            if(isset($data['filter'])) {
                $query->where('name', 'LIKE', "%{$data['filter']}%");
                $query->orWhere('description', 'LIKE', "%{$data['filter']}%");
            }

            if(isset($data['name']))
                $query->where('name', $data['name']);

            if(isset($data['description']))
                $query->where('description', 'LIKE', "%{$data['description']}%");
        })->paginate($totalPage);
    }
}
