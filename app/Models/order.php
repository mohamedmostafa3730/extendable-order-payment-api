<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Order extends Model
{
    use HasFactory;
    use HasUuids;
    public $incrementing = false;

    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'order_status',
        'total',
    ];

    #[Override]
    protected function casts(): array
    {
        return [
            'order_status' => OrderStatus::class,
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
