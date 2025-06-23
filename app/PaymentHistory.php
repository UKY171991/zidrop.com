<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_type', 
        'payment_purpose', 
        'transactionId', 
        'refference_no', 
        'paid_at', 
        'amount', 
        'fees', 
        'card_holder_name', 
        'card_type', 
        'card_auth_code', 
        'card_bin', 
        'card_last4', 
        'card_no', 
        'card_expirity', 
        'bank_name', 
        'reference', 
        'status', 
        'channel', 
        'currency', 
        'metadata', 
        'user_ip', 
        'transaction_date', 
        'relational_type', 
        'relational_id'
    ];
}
