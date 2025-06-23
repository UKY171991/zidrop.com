<?php

namespace App\Imports;

use App\Town;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TownImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'cities_id' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'towncharge' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            // Handle validation errors
            // For example: Log errors, notify user, etc.
            return null;
        }

        // Generate slug from title
        $slug = Str::slug($row['title']);

        // Check if the town already exists based on cities_id
        $existingTown = Town::where('cities_id', $row['cities_id'])->where('slug', $slug)->where('title', $row['title'])->first();

        if ($existingTown) {
            // If the town already exists, update its attributes
            $existingTown->update([
                'title' => $row['title'],
                'slug' => $slug,
                'towncharge' => $row['towncharge'],
                'updated_at' => now(),
            ]);

            return null; // Returning null since we don't want to insert a new record
        } else {
            // If the town does not exist, create a new Town instance
            return new Town([
                'cities_id' => $row['cities_id'],
                'title' => $row['title'],
                'slug' => $slug,
                'towncharge' => $row['towncharge'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
