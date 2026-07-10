<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'report_date',
        'quantity_sold',
        'selling_price',
        'total_revenue',
        'notes',
        'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date:Y-m-d',
            'quantity_sold' => 'integer',
            'selling_price' => 'decimal:2',
            'total_revenue' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
