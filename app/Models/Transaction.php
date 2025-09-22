<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function setAmountAttribute(string $value): void
    {
        $clean = preg_replace('/[^\d,.-]/', '', $value);
        $clean = str_replace(['.', ','], ['', '.'], $clean);

        $this->attributes['amount'] = number_format((float) $clean, 2, '.', '');
    }
}
