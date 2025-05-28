<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Exports;

use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Jobs\ProcessExport;
use Qoraiche\Peak\Models\Export;
use Qoraiche\Peak\Services\DynamicExport;
use Qoraiche\Peak\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public string $classModel;
    public string $tableName;
    private string $fileFormat;

    public function __construct()
    {
        $this->middleware(['can:export']);
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'format' => ['nullable', Rule::in(['xlsx', 'csv', 'tsv', 'ods', 'xls', 'pdf'])],
            'selectedIds' => 'array|nullable',
            'name' => 'string|required',
            'selectedExportableData' => 'array|nullable',
            'queue' => 'boolean|nullable',
        ]);

        $exportableColumns = app($this->classModel)->getExportableColumns();

        $allowedFormats = ['xlsx', 'csv', 'tsv', 'ods', 'xls', 'pdf'];

        $this->fileFormat = in_array($request->get('format'), $allowedFormats, true)
            ? $request->get('format')
            : 'xlsx'; // Default to xlsx if missing or invalid.

        $columns = array_intersect_key(
            $exportableColumns,
            array_flip(
                $request->get('selectedExportableData', array_keys($exportableColumns))
            ));

        $query = $this->classModel::query();

        if ($request->has('selectedIds')) {
            $query->whereIn('id', $request->get('selectedIds'));
        }

        $data = $query->get();

        if ($request->has('queue') && $request->queue) {
            $export = Export::query()->create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'model' => $this->classModel,
                'file_name' => $this->fileName(),
                'file_path' => null,
                'locale' => Helpers::getLocale(),
                'columns' => collect($exportableColumns)->keys()->toArray(),
                'ids' => $request->selectedIds,
                'format' => $request->get('format'),
                'status' => Export::PENDING_STATUS
            ]);

            ProcessExport::dispatch($export, $this->classModel, $exportableColumns);

        } else {

            return Excel::download(new DynamicExport($data, $columns), $this->fileName());
        }
    }

    /**
     * @return string
     */
    public function fileName(): string
    {
        return sprintf(
            '%s-%s-%s.%s',
            $this->tableName, now()->format('Y-m-d'),
            Helpers::getLocale(),
            $this->fileFormat
        );
    }
}
