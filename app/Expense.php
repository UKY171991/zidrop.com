<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\ExpenseType;
use App\Agent;

class Expense extends Model
{
    use HasFactory;

    public function expensetype()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id', 'id');

    }
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id')->withDefault(['name' => 'N/A']);

    }
}
