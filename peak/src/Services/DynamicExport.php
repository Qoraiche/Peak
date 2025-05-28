<?php

namespace Qoraiche\Peak\Services;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DynamicExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $columns;

    public function __construct($data, $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return collect($this->columns)->mapWithKeys(function ($callback, $column) use ($item) {
                return [$column => $callback($item)];
            });
        });
    }

    public function headings(): array
    {
        return array_keys($this->columns);
    }
}
