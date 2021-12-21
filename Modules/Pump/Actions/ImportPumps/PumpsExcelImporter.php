<?php

namespace Modules\Pump\Actions\ImportPumps;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Box\Spout\Reader\ReaderInterface;
use Box\Spout\Reader\SheetInterface;
use Box\Spout\Reader\XLSX\Reader;
use Modules\Pump\Actions\ImportPumps\PumpType\DoublePumpImporter;
use Modules\Pump\Actions\ImportPumps\PumpType\SinglePumpImporter;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class PumpsExcelImporter extends FastExcel
{
    /**
     * @param $path
     * @return Reader
     * @throws IOException
     */
    protected function reader($path): Reader
    {
        $reader = ReaderEntityFactory::createXLSXReader();
        $this->setOptions($reader);
        /* @var ReaderInterface $reader */
        $reader->open($path);

        return $reader;
    }

    /**
     * @param $path
     * @param $errorBag
     * @return SheetCollection
     * @throws IOException
     * @throws ReaderNotOpenedException
     */
    public function importPumpSheets($path, &$errorBag): SheetCollection
    {
        $dbBrands = PumpBrand::with(['series' => function ($query) {
            $query->select('category_id', 'name', 'brand_id');
        }])->get();
        $dbSeries = [];
        foreach ($dbBrands as $dbBrand) {
            $_series = [];
            foreach ($dbBrand->series as $series) {
                $_series[$series->name] = $series->category_id;
            }
            $dbSeries[$dbBrand->name] = $_series;
        }
        $reader = $this->reader($path);
        $collections = [];
        foreach ($reader->getSheetIterator() as $key => $sheet) {
            $pumpImporter = $this->pumpImporter($errorBag, $dbSeries, $sheet);
            if ($pumpImporter)
                $collections[] = $this->importSheet(
                    $sheet,
                    $pumpImporter->importCallback($errorBag)
                );
        }
        $reader->close();

        return new SheetCollection($collections);
    }

    private function pumpImporter(&$errorBag, $dbSeries, $sheet): ?PumpImporter
    {
        $sheetName = explode("+", trim($sheet->getName()));
        if (!array_key_exists($sheetName[0], $dbSeries)) {
            $this->sheetNameIsInvalid(
                $errorBag,
                __('validation.attributes.import.pumps.brand'),
                $sheetName[0],
                __('validation.import.sheet_name.brand')
            );
            return null;
        }
        if (!array_key_exists($sheetName[1], $dbSeries[$sheetName[0]])) {
            $this->sheetNameIsInvalid(
                $errorBag,
                __('validation.attributes.import.pumps.series'),
                $sheetName[1],
                __('validation.import.sheet_name.series')
            );
            return null;
        }
        return match ($dbSeries[$sheetName[0]][$sheetName[1]]) {
            PumpCategory::$SINGLE_PUMP => new SinglePumpImporter(),
            PumpCategory::$DOUBLE_PUMP => new DoublePumpImporter(),
        };
    }

    private function sheetNameIsInvalid(&$errorBag, $key, $value, $message)
    {
        $errorBag[] = [
            'head' => [
                'key' => $key,
                'value' => $value
            ],
            'file' => '',
            'message' => $message
        ];
    }

    /**
     * @param SheetInterface $sheet
     * @param callable|null  $callback
     *
     * @return array
     */
    protected function importSheet(SheetInterface $sheet, callable $callback = null): array
    {
        $headers = [];
        $collection = [];

        foreach ($sheet->getRowIterator() as $k => $rowAsObject) {
            $row = $rowAsObject->toArray();
            if ($k >= 2) {
                if ($callback) {
                    if ($result = $callback(empty($headers) ? $row : array_combine($headers, $row))) {
                        $collection[] = $result;
                    }
                } else {
                    $collection[] = empty($headers) ? $row : array_combine($headers, $row);
                }
            }
        }
        return $collection;
    }
}
