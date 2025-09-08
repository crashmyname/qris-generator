<?php
namespace Helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class Importer
{
    protected $sheet;
    protected $rows;
    protected $filepath;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
        $spreadsheet = IOFactory::load($filepath);
        $this->sheet = $spreadsheet->getActiveSheet();
        $this->rows = $this->sheet->toArray(null, true, true, true);
    }

    public function getHeader(): array
    {
        return $this->rows[0] ?? [];
    }

    public function getExampleRow(): array
    {
        return $this->rows[1] ?? [];
    }

    public function getDataRows(): array
    {
        return array_slice($this->rows, 2);
    }

    public function handleAll(callable $mapFunction): array
    {
        $results = [];
        foreach ($this->getDataRows() as $index => $row) {
            $results[] = $this->handle($mapFunction($row, $index));
        }
        return $results;
    }

    abstract public function handle(array $mappedRow): mixed;
}
