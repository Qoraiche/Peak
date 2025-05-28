<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Exports;

use Qoraiche\Peak\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Models\Export;
use Qoraiche\Peak\Services\Admin\ExportService;

class ExportManagementController extends Controller
{
    /**
     * @param ExportService $exportService
     */
    public function __construct(private ExportService $exportService)
    {
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $exports = $this->exportService->getFrontSearchablePaginatedExports($request);

        return Inertia::render('Admin/Exports/Index',
            [
                'items' => Inertia::defer(fn() => $exports),
                'exportableData' => Export::exportableDataColumnNames()
            ]
        );
    }

    /**
     * @param Export $export
     * @return void
     */
    public function destroy(Export $export)
    {
        Storage::delete($export->file_path);
        $export->delete();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function bulkDestroy(Request $request)
    {
        Export::query()
            ->whereIn('id', $request->get('ids'))
            ->chunkById(50, function ($exports) {
                foreach ($exports as $export) {
                    Storage::delete($export->file_path);
                    $export->delete();
                }
            });
    }
}
