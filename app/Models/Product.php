<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'name', 'description', 'sku',
        'purchase_price', 'selling_price', 'stock_quantity',
        'low_stock_threshold', 'image_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyProductReport::class);
    }

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
    }
}
