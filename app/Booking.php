namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Define the table associated with the model (optional if table name is plural of the model name)
    protected $table = 'p2p';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Enable or disable timestamps (true if 'created_at' and 'updated_at' are used)
    public $timestamps = true;

    // Specify the fillable fields for mass assignment
    protected $fillable = [
        'sender_name',
        'sender_address',
        'sender_city',
        'sender_state',
        'sender_pincode',
        'sender_phone',
        'receiver_name',
        'receiver_address',
        'receiver_city',
        'receiver_state',
        'receiver_pincode',
        'receiver_phone',
        'parcel_type',
        'parcel_weight',
        'parcel_value',
        'parcel_quantity',
        'parcel_color',
        'cod_amount',
        'note',
        'created_at',
        'updated_at',
    ];

    // Optionally, you can define any relationships here
    // Example: public function sender() { return $this->belongsTo(User::class); }
}

