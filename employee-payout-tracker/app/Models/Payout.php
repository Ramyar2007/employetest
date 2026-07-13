<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'task',
        'amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function formattedAmount(): string
    {
        return number_format($this->amount).' IQD';
    }
}
