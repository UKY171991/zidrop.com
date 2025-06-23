<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use App\Agent;
use Maatwebsite\Excel\Concerns\FromCollection;

class AgentsCommissionExport extends StringValueBinder implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.agent_payment', [
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
