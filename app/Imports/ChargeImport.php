<?php

namespace App\Imports;

use App\ChargeTarif;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChargeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        $validator = Validator::make($row, [
            'pickup_cities_id' => 'required',
            'delivery_cities_id' => 'required',
            'deliverycharge' => 'required|numeric',
            'extradeliverycharge' => 'required|numeric',
            'codcharge' => 'required|numeric',
            'tax' => 'required|numeric',
            'insurance' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        // excape blank row
        if (!isset($row['pickup_cities_id']) && !isset($row['delivery_cities_id']) && !isset($row['deliverycharge']) && !isset($row['extradeliverycharge']) && !isset($row['codcharge']) && !isset($row['tax']) && !isset($row['insurance'])) {
            return null;
        }
        if ($validator->fails()) {
            Session::forget('importError');
            Session::put('importError', 'Validation error found. Please check your file and try again.');
            return null;
        }

        // Check if the combination of pickup_cities_id and delivery_cities_id already exists
        $existingRecord = ChargeTarif::where('pickup_cities_id', $row['pickup_cities_id'])
            ->where('delivery_cities_id', $row['delivery_cities_id'])
            ->first();

        if ($existingRecord) {
            // If the record exists, update its attributes
            $existingRecord->update([
                'deliverycharge' => $row['deliverycharge'],
                'extradeliverycharge' => $row['extradeliverycharge'],
                'codcharge' => $row['codcharge'],
                'tax' => $row['tax'],
                'insurance' => $row['insurance'],
                'description' => $row['description'],
                'updated_at' => now(),
            ]);

            return null; // Returning null since we don't want to insert a new record
        } else {
            // If the record does not exist, create a new ChargeTarif instance
            return new ChargeTarif([
                'pickup_cities_id' => $row['pickup_cities_id'],
                'delivery_cities_id' => $row['delivery_cities_id'],
                'deliverycharge' => $row['deliverycharge'],
                'extradeliverycharge' => $row['extradeliverycharge'],
                'codcharge' => $row['codcharge'],
                'tax' => $row['tax'],
                'insurance' => $row['insurance'],
                'description' => $row['description'],
                'created_at' => now(), // Using Laravel helper to get current datetime
                'updated_at' => now(),
            ]);
        }
    }
}
