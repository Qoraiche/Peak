<?php

namespace Qoraiche\Peak\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Qoraiche\Peak\Events\Admin\ExportReady;
use Qoraiche\Peak\Models\Export;
use Qoraiche\Peak\Services\DynamicExport;

class ProcessExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param Export $export
     * @param string $model
     */
    public function __construct(public Export $export, public string $model)
    {
    }

    /**
     * @return void
     */
    public function handle()
    {
        try {
            // Fetch the actual data inside the job
            $query = $this->model::query();

            $exportableColumns = app($this->model)->getExportableColumns();

            $columns = array_intersect_key(
                $exportableColumns,
                array_flip(
                    $this->export->columns
                ));

            if (!empty($this->export->ids)) {
                $query->whereIn('id', $this->export->ids);
            }

            $data = $query->get();

            $filePath = $this->exportFilePath($this->export->file_name);

            // Recreate the export instance inside the job
            $dynamicExport = new DynamicExport($data, $columns);

            // Store the export file
            Excel::store($dynamicExport, $filePath);

            // Update the export record in the database
            $this->export->update([
                'file_path' => $filePath,
                'status' => Export::COMPLETED_STATUS
            ]);

            ExportReady::dispatch($this->export, $this->export->user);

        } catch (Exception $e) {
            Log::error('Error storing an export request', [
                'export.id' => $this->export->id,
                'export.file_name' => $this->export->file_name,
                'error.message' => $e->getMessage(),
                'error.date' => now()
            ]);

            $this->export->update(['status' => Export::FAILED_STATUS]);
        }
    }

    /**
     * @param $fileName
     * @return string
     */
    protected function exportFilePath($fileName)
    {
        $directory = '/exports/' . Str::uuid();

        // Ensure the base "exports" directory exists
        if (!Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        // Ensure the UUID-based subdirectory exists
        Storage::makeDirectory($directory);

        return sprintf('%s/%s', $directory, $fileName);
    }
}
