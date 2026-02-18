<?php

namespace App\Models;

use App\Support\MoneyParser;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use InvalidArgumentException;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'date',
        'amount',
        'user_id',
        'card_id',
        'category_id',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the card that owns the transaction.
     *
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the category that owns the transaction.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that owns the transaction.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the "amount" attribute by converting a formatted money value
     *
     * @param  string $value
     * @return void
     */
    public function setAmountAttribute(mixed $value): void
    {
        $parsed = MoneyParser::toDecimal($value);

        if ($parsed === null) {
            throw new InvalidArgumentException('Invalid money amount.');
        }

        $this->attributes['amount'] = $parsed;
    }
}
