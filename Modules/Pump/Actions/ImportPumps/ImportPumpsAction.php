<?php

namespace Modules\Pump\Actions\ImportPumps;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpFile;
use Modules\Pump\Entities\PumpSeries;
use Modules\Selection\Support\PumpPerformance\PPumpPerformance;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class ImportPumpsAction
{
    use UsesTenantModel;

    private int $MAX_EXECUTION_TIME = 180;
    private bool $createCoefs = true;

    public function execute($files): RedirectResponse
    {
        ini_set('max_execution_time', $this->MAX_EXECUTION_TIME);
        $errorBag = [];
        try {
            $sheets = [];
            foreach ($files as $file) {
                $sheets = (new PumpsExcelImporter())
                    ->withoutHeaders()
                    ->startRow(2)
                    ->importPumpSheets($file, $errorBag);
            }
        } catch (ReaderNotOpenedException $exception) {
            Log::error($exception->getTraceAsString());
            return $this->redirectWithErrors("Ошибка загрузки. Не удалось открыть файл");
        }
        if (!empty($errorBag)) {
            return Redirect::back()->with('errorBag', array_splice($errorBag, 0, 50));
        }

        try {
            foreach ($sheets as $sheet) {
                $database = $this->getTenantModel()::current()->database;
                foreach (array_chunk($sheet, 100) as $chunkedSheet) {
                    DB::table($database . '.pumps')->upsert(array_map(fn($sheetInfo) => $sheetInfo['pump'],
                        $chunkedSheet), ['article_num_main']);
                }
                $seriesId = $sheet[0]['pump']['series_id'];
                $pumpsBySeries = Pump::whereSeriesId($seriesId);
                PumpSeries::whereId($seriesId)->update([
                    'temps_min' => $pumpsBySeries->min('fluid_temp_min') . ',' . $pumpsBySeries->max('fluid_temp_min'),
                    'temps_max' => $pumpsBySeries->min('fluid_temp_max') . ',' . $pumpsBySeries->max('fluid_temp_max'),
                ]);

                // pump that were inserted just now (from current sheet)
                $upsertedPumps = $pumpsBySeries->get(['id', 'pumpable_type', 'article_num_main']);
                PumpFile::whereIn('pump_id', $upsertedPumps->pluck('id')->all())->delete();

                foreach (array_chunk($sheet, 100) as $chunkedSheet) {
                    $pumpFiles = [];
                    foreach ($chunkedSheet as $sheetRow) {
                        $pumpId = $upsertedPumps->firstWhere('article_num_main', $sheetRow['pump']['article_num_main'])->id;
                        foreach ($sheetRow['files'] as $fileName) {
                            if (!empty($fileName))
                                $pumpFiles[] = [
                                    'pump_id' => $pumpId,
                                    'file_name' => trim($fileName),
                                ];
                        }
                    }
                    DB::table($database . '.pump_files')->insert($pumpFiles);
                }
                if ($this->createCoefs) {
                    $pumpsBySeries->with('coefficients')
                        ->select('id', 'pumpable_type')
                        ->performanceData($seriesId)
                        ->chunk(100, function ($pumps) use ($database) {
                            DB::table($database . '.pumps_and_coefficients')
                                ->whereIn('pump_id', array_map(fn($pump) => $pump['id'], $pumps->toArray()))
                                ->delete();
                            $pumpsAndCoefficients = [];
                            foreach ($pumps as $pump) {
                                if ($pump->coefficients->isEmpty()) {
                                    $count = $pump->coefficientsCount();
                                    $pumpPerformance = PPumpPerformance::construct($pump);
                                    for ($pos = 1; $pos <= $count; ++$pos) {
                                        $pumpsAndCoefficients[] = $pumpPerformance->coefficientsToCreate($pos);
                                    }
                                }
                            }
                            if (!empty($pumpsAndCoefficients)) {
                                DB::table($database . '.pumps_and_coefficients')->insert($pumpsAndCoefficients);
                            }
                        });
                }
            }
        } catch (UnsupportedTypeException | Exception $exception) {
            Log::error($exception->getTraceAsString());
            return $this->redirectWithErrors("Ошибка загрузки. Не удалось заполнить базу данных.");
        }
        return Redirect::route('pumps.index')->with('success', __('flash.pumps.imported'));
    }

    private function redirectWithErrors($errors): RedirectResponse
    {
        return Redirect::route("pumps.index")
            ->withErrors($errors);
    }
}
