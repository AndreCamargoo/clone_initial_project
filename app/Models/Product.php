<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public $fillable = ["name", "url", "price", "description", "category_id"];
    
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByPrice', function(Builder $builder) {
            $builder->orderBy('price', 'asc');
        });
    }

    /**
     * Get the category that owns the Product
     * Obtenha a categoria que possui o produto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
