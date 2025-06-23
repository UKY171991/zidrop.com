<?php

namespace App\Exports;

use App\Deliveryman;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use App\Agent;
use Maatwebsite\Excel\Concerns\FromCollection;

class DeliverymanCommissionExport extends StringValueBinder implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.deliveryman_payment', [
            'show_data' => $this->data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'A' => DataType::TYPE_STRING,
            'B' => DataType::TYPE_STRING,
        ];
    }
}
