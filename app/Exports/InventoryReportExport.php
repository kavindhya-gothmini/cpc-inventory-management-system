<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\Branch;
use App\Models\Department;
use App\Models\ItemType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class InventoryReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * ============================================================
     * INVENTORY DATA
     * ============================================================
     */
    public function collection(): Collection
    {
        $query = Item::query()
            ->with([
                'itemType',
                'branch',
                'department',
                'currentEmployee',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Branch
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['branch_id'])) {
            $query->where(
                'branch_id',
                $this->filters['branch_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['department_id'])) {
            $query->where(
                'department_id',
                $this->filters['department_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Item Type
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['item_type_id'])) {
            $query->where(
                'item_type_id',
                $this->filters['item_type_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['status'])) {
            $query->where(
                'status',
                $this->filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['from_date'])) {
            $query->whereDate(
                'created_at',
                '>=',
                $this->filters['from_date']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['to_date'])) {
            $query->whereDate(
                'created_at',
                '<=',
                $this->filters['to_date']
            );
        }

        return $query
            ->latest('id')
            ->get();
    }

    /**
     * ============================================================
     * TABLE HEADINGS
     * ============================================================
     */
    public function headings(): array
    {
        return [
            '#',
            'Asset Number',
            'Item Type',
            'Make',
            'Model',
            'Serial Number',
            'Branch',
            'Department',
            'Employee',
            'Status',
        ];
    }

    /**
     * ============================================================
     * TABLE MAPPING
     * ============================================================
     */
    public function map($item): array
    {
        static $rowNumber = 0;

        $rowNumber++;

        return [
            $rowNumber,

            $item->asset_number ?: '—',

            optional($item->itemType)->name ?: '—',

            $item->make ?: '—',

            $item->model ?: '—',

            $item->serial_number ?: '—',

            optional($item->branch)->name ?: '—',

            optional($item->department)->name ?: '—',

            optional($item->currentEmployee)->name ?: 'Unassigned',

            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $item->status ?? '—'
                )
            ),
        ];
    }

    /**
     * ============================================================
     * EXCEL EVENTS
     * ============================================================
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | Report dimensions before inserting header rows
                |--------------------------------------------------------------------------
                */

                $originalHighestRow = $sheet->getHighestRow();

                /*
                |--------------------------------------------------------------------------
                | Insert official CPC report header
                |--------------------------------------------------------------------------
                |
                | Existing headings begin at row 1.
                | Insert 10 rows so the table begins at row 11.
                |
                */

                $sheet->insertNewRowBefore(1, 10);

                $headingRow = 11;
                $dataStartRow = 12;

                /*
                |--------------------------------------------------------------------------
                | Last data row
                |--------------------------------------------------------------------------
                */

                $lastRow = $headingRow + ($originalHighestRow - 1);

                if ($originalHighestRow <= 1) {
                    $lastRow = $headingRow;
                }

                /*
                |--------------------------------------------------------------------------
                | CPC LOGO
                |--------------------------------------------------------------------------
                */

                $logoPath = public_path(
                    'images/cpc-logo.png'
                );

                if (file_exists($logoPath)) {

                    $drawing = new Drawing();

                    $drawing->setName(
                        'Ceylon Petroleum Corporation Logo'
                    );

                    $drawing->setDescription(
                        'Ceylon Petroleum Corporation Logo'
                    );

                    $drawing->setPath($logoPath);

                    $drawing->setHeight(65);

                    $drawing->setCoordinates('A1');

                    $drawing->setOffsetX(8);

                    $drawing->setOffsetY(5);

                    $drawing->setWorksheet($sheet);
                }

                /*
                |--------------------------------------------------------------------------
                | CPC OFFICIAL HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('B1:J1');

                $sheet->setCellValue(
                    'B1',
                    'CEYLON PETROLEUM CORPORATION'
                );

                $sheet->mergeCells('B2:J2');

                $sheet->setCellValue(
                    'B2',
                    'INFORMATION TECHNOLOGY FUNCTION'
                );

                $sheet->mergeCells('B3:J3');

                $sheet->setCellValue(
                    'B3',
                    'CPC INVENTORY MANAGEMENT SYSTEM'
                );

                $sheet->mergeCells('B4:J4');

                $sheet->setCellValue(
                    'B4',
                    'OFFICIAL DOCUMENT'
                );

                $sheet->mergeCells('B5:J5');

                $sheet->setCellValue(
                    'B5',
                    'Inventory Summary Report'
                );

                $sheet->mergeCells('B6:J6');

                $sheet->setCellValue(
                    'B6',
                    'Inventory Assets, Allocation, Status and Location Summary'
                );

                $sheet->mergeCells('B7:J7');

                $sheet->setCellValue(
                    'B7',
                    'Generated: ' .
                    now()->format('Y-m-d H:i:s')
                );

                /*
                |--------------------------------------------------------------------------
                | CPC HEADER STYLING
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('B1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B2:J2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B3:J3')->applyFromArray([
                    'font' => [
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B4:J4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B5:J5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 15,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B6:J6')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B7:J7')->applyFromArray([
                    'font' => [
                        'size' => 9,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_RIGHT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | REPORT CRITERIA
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A8:J8');

                $sheet->setCellValue(
                    'A8',
                    'REPORT CRITERIA'
                );

                $sheet->getStyle('A8:J8')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],
                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,
                            'color' => [
                                'rgb' => 'CED4DA',
                            ],
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Filter row labels
                |--------------------------------------------------------------------------
                */

                $criteria = [
                    'Branch' =>
                        $this->getBranchName(
                            $this->filters['branch_id'] ?? null
                        ),

                    'Department' =>
                        $this->getDepartmentName(
                            $this->filters['department_id'] ?? null
                        ),

                    'Item Type' =>
                        $this->getItemTypeName(
                            $this->filters['item_type_id'] ?? null
                        ),

                    'Status' =>
                        $this->getStatusName(
                            $this->filters['status'] ?? null
                        ),

                    'From Date' =>
                        $this->formatDate(
                            $this->filters['from_date'] ?? null
                        ),

                    'To Date' =>
                        $this->formatDate(
                            $this->filters['to_date'] ?? null
                        ),
                ];

                /*
                |--------------------------------------------------------------------------
                | Criteria layout
                |--------------------------------------------------------------------------
                |
                | Row 9: Branch / Department
                | Row 10: Item Type / Status
                |
                | The table starts at row 11.
                |
                */

                $sheet->setCellValue(
                    'A9',
                    'Branch'
                );

                $sheet->setCellValue(
                    'B9',
                    $criteria['Branch']
                );

                $sheet->setCellValue(
                    'D9',
                    'Department'
                );

                $sheet->setCellValue(
                    'E9',
                    $criteria['Department']
                );

                $sheet->mergeCells('E9:J9');

                $sheet->setCellValue(
                    'A10',
                    'Item Type'
                );

                $sheet->setCellValue(
                    'B10',
                    $criteria['Item Type']
                );

                $sheet->setCellValue(
                    'D10',
                    'Status'
                );

                $sheet->setCellValue(
                    'E10',
                    $criteria['Status']
                );

                $sheet->mergeCells('E10:G10');

                $sheet->setCellValue(
                    'H10',
                    'Date Range'
                );

                $sheet->setCellValue(
                    'I10',
                    $criteria['From Date'] .
                    ' → ' .
                    $criteria['To Date']
                );

                $sheet->mergeCells('I10:J10');

                /*
                |--------------------------------------------------------------------------
                | Criteria labels
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A9:A10')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 9,
                    ],
                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F1F3F5',
                        ],
                    ],
                    'alignment' => [
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('D9:D10')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 9,
                    ],
                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F1F3F5',
                        ],
                    ],
                    'alignment' => [
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('H10')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 9,
                    ],
                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F1F3F5',
                        ],
                    ],
                    'alignment' => [
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Criteria values
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('B9:J10')->applyFromArray([
                    'font' => [
                        'size' => 9,
                    ],
                    'alignment' => [
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,
                            'color' => [
                                'rgb' => 'DEE2E6',
                            ],
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | SUMMARY
                |--------------------------------------------------------------------------
                |
                | Summary is placed after the table header area in a compact
                | professional layout without interfering with Excel filters.
                |
                */

                $sheet->mergeCells('A9:J9');

                /*
                |--------------------------------------------------------------------------
                | TABLE HEADER
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | The table must start at row 11 so the AutoFilter applies
                | only to the actual inventory table.
                |
                */

                $sheet->getStyle(
                    'A11:J11'
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '212529',
                        ],
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,
                            'color' => [
                                'rgb' => 'ADB5BD',
                            ],
                        ],
                    ],

                ]);

                /*
                |--------------------------------------------------------------------------
                | DATA TABLE
                |--------------------------------------------------------------------------
                */

                if ($originalHighestRow > 1) {

                    $dataRange =
                        'A12:J' .
                        $lastRow;

                    $sheet->getStyle(
                        $dataRange
                    )->applyFromArray([

                        'font' => [
                            'size' => 9,
                        ],

                        'alignment' => [
                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => 'DEE2E6',
                                ],
                            ],
                        ],

                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Center specific columns
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle(
                        "A12:A{$lastRow}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    $sheet->getStyle(
                        "J12:J{$lastRow}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Alternating row formatting
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $row = 12;
                        $row <= $lastRow;
                        $row++
                    ) {

                        if ($row % 2 === 0) {

                            $sheet
                                ->getStyle(
                                    "A{$row}:J{$row}"
                                )
                                ->applyFromArray([
                                    'fill' => [
                                        'fillType' =>
                                            Fill::FILL_SOLID,
                                        'startColor' => [
                                            'rgb' => 'F8F9FA',
                                        ],
                                    ],
                                ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Status emphasis
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $row = 12;
                        $row <= $lastRow;
                        $row++
                    ) {

                        $status = strtolower(
                            trim(
                                (string) $sheet
                                    ->getCell(
                                        "J{$row}"
                                    )
                                    ->getValue()
                            )
                        );

                        if (
                            in_array(
                                $status,
                                [
                                    'available',
                                    'in use',
                                    'under repair',
                                    'rdd',
                                ],
                                true
                            )
                        ) {

                            $sheet->getStyle(
                                "J{$row}"
                            )->getFont()
                                ->setBold(true);
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | SUMMARY SECTION
                |--------------------------------------------------------------------------
                |
                | Placed immediately above the signature area.
                |
                */

                $summaryRow =
                    $lastRow + 2;

                $sheet->mergeCells(
                    "A{$summaryRow}:J{$summaryRow}"
                );

                $sheet->setCellValue(
                    "A{$summaryRow}",
                    'INVENTORY SUMMARY'
                );

                $sheet->getStyle(
                    "A{$summaryRow}:J{$summaryRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);

                /*
                |--------------------------------------------------------------------------
                | Calculate summary directly from exported data
                |--------------------------------------------------------------------------
                */

                $totalAssets =
                    max(
                        0,
                        $originalHighestRow - 1
                    );

                $inUse = 0;
                $available = 0;
                $repair = 0;
                $rdd = 0;

                if ($totalAssets > 0) {

                    for (
                        $row = 12;
                        $row <= $lastRow;
                        $row++
                    ) {

                        $status = strtolower(
                            trim(
                                (string) $sheet
                                    ->getCell(
                                        "J{$row}"
                                    )
                                    ->getValue()
                            )
                        );

                        switch ($status) {

                            case 'in use':
                                $inUse++;
                                break;

                            case 'available':
                                $available++;
                                break;

                            case 'under repair':
                                $repair++;
                                break;

                            case 'rdd':
                                $rdd++;
                                break;
                        }
                    }
                }

                $summaryData = [
                    ['Total Assets', $totalAssets],
                    ['In Use', $inUse],
                    ['Available', $available],
                    ['Under Repair', $repair],
                    ['RDD', $rdd],
                ];

                /*
                |--------------------------------------------------------------------------
                | Summary cells
                |--------------------------------------------------------------------------
                */

                $summaryValueRow =
                    $summaryRow + 1;

                $summaryColumns = [
                    ['A', 'B'],
                    ['C', 'D'],
                    ['E', 'F'],
                    ['G', 'H'],
                    ['I', 'J'],
                ];

                foreach (
                    $summaryData as $index => $data
                ) {

                    [$labelColumn, $valueColumn] =
                        $summaryColumns[$index];

                    $sheet->setCellValue(
                        $labelColumn . $summaryValueRow,
                        $data[0]
                    );

                    $sheet->setCellValue(
                        $valueColumn . $summaryValueRow,
                        $data[1]
                    );
                }

                $sheet->getStyle(
                    "A{$summaryValueRow}:J{$summaryValueRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 9,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,
                            'color' => [
                                'rgb' => 'D9DEE3',
                            ],
                        ],
                    ],

                ]);

                /*
                |--------------------------------------------------------------------------
                | DOCUMENT CERTIFICATION
                |--------------------------------------------------------------------------
                */

                $signatureStart =
                    $summaryValueRow + 3;

                $sheet->mergeCells(
                    "A{$signatureStart}:J{$signatureStart}"
                );

                $sheet->setCellValue(
                    "A{$signatureStart}",
                    'DOCUMENT CERTIFICATION & AUTHORIZATION'
                );

                $sheet->getStyle(
                    "A{$signatureStart}:J{$signatureStart}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);

                $signatureRow =
                    $signatureStart + 2;

                /*
                |--------------------------------------------------------------------------
                | Prepared By
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "A{$signatureRow}:C{$signatureRow}"
                );

                $sheet->setCellValue(
                    "A{$signatureRow}",
                    'Prepared By'
                );

                $sheet->mergeCells(
                    "A" . ($signatureRow + 1) .
                    ":C" . ($signatureRow + 1)
                );

                $sheet->setCellValue(
                    "A" . ($signatureRow + 1),
                    '____________________________'
                );

                $sheet->mergeCells(
                    "A" . ($signatureRow + 2) .
                    ":C" . ($signatureRow + 2)
                );

                $sheet->setCellValue(
                    "A" . ($signatureRow + 2),
                    'Name / Signature / Date'
                );

                /*
                |--------------------------------------------------------------------------
                | Checked By
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "E{$signatureRow}:G{$signatureRow}"
                );

                $sheet->setCellValue(
                    "E{$signatureRow}",
                    'Checked By'
                );

                $sheet->mergeCells(
                    "E" . ($signatureRow + 1) .
                    ":G" . ($signatureRow + 1)
                );

                $sheet->setCellValue(
                    "E" . ($signatureRow + 1),
                    '____________________________'
                );

                $sheet->mergeCells(
                    "E" . ($signatureRow + 2) .
                    ":G" . ($signatureRow + 2)
                );

                $sheet->setCellValue(
                    "E" . ($signatureRow + 2),
                    'Name / Signature / Date'
                );

                /*
                |--------------------------------------------------------------------------
                | Authorized By
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "I{$signatureRow}:J{$signatureRow}"
                );

                $sheet->setCellValue(
                    "I{$signatureRow}",
                    'Authorized By'
                );

                $sheet->mergeCells(
                    "I" . ($signatureRow + 1) .
                    ":J" . ($signatureRow + 1)
                );

                $sheet->setCellValue(
                    "I" . ($signatureRow + 1),
                    '____________________________'
                );

                $sheet->mergeCells(
                    "I" . ($signatureRow + 2) .
                    ":J" . ($signatureRow + 2)
                );

                $sheet->setCellValue(
                    "I" . ($signatureRow + 2),
                    'Name / Signature / Date'
                );

                /*
                |--------------------------------------------------------------------------
                | Signature Styling
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$signatureRow}:J" .
                    ($signatureRow + 2)
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],

                ]);

                $sheet->getStyle(
                    "A{$signatureRow}:J{$signatureRow}"
                )->getFont()
                    ->setBold(true);

                /*
                |--------------------------------------------------------------------------
                | FOOTER
                |--------------------------------------------------------------------------
                */

                $footerRow =
                    $signatureRow + 5;

                $sheet->mergeCells(
                    "A{$footerRow}:J{$footerRow}"
                );

                $sheet->setCellValue(
                    "A{$footerRow}",
                    'CPC Inventory Management System | ' .
                    'Information Technology Function | ' .
                    'Ceylon Petroleum Corporation'
                );

                $sheet->getStyle(
                    "A{$footerRow}:J{$footerRow}"
                )->applyFromArray([

                    'font' => [
                        'italic' => true,
                        'size' => 9,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);

                /*
                |--------------------------------------------------------------------------
                | COLUMN WIDTHS
                |--------------------------------------------------------------------------
                */

                $widths = [
                    'A' => 7,
                    'B' => 18,
                    'C' => 20,
                    'D' => 18,
                    'E' => 20,
                    'F' => 22,
                    'G' => 24,
                    'H' => 25,
                    'I' => 25,
                    'J' => 16,
                ];

                foreach (
                    $widths as $column => $width
                ) {

                    $sheet
                        ->getColumnDimension($column)
                        ->setWidth($width);
                }

                /*
                |--------------------------------------------------------------------------
                | ROW HEIGHTS
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(30);

                $sheet
                    ->getRowDimension(2)
                    ->setRowHeight(22);

                $sheet
                    ->getRowDimension(3)
                    ->setRowHeight(20);

                $sheet
                    ->getRowDimension(5)
                    ->setRowHeight(24);

                $sheet
                    ->getRowDimension(6)
                    ->setRowHeight(20);

                $sheet
                    ->getRowDimension(8)
                    ->setRowHeight(22);

                $sheet
                    ->getRowDimension(9)
                    ->setRowHeight(24);

                $sheet
                    ->getRowDimension(10)
                    ->setRowHeight(24);

                $sheet
                    ->getRowDimension(11)
                    ->setRowHeight(30);

                $sheet
                    ->getRowDimension(
                        $summaryRow
                    )
                    ->setRowHeight(22);

                /*
                |--------------------------------------------------------------------------
                | FREEZE TABLE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A12');

                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */

                if ($originalHighestRow > 1) {

                    $sheet->setAutoFilter(
                        "A11:J{$lastRow}"
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | PRINT SETTINGS
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setOrientation(
                        PageSetup::ORIENTATION_LANDSCAPE
                    );

                $sheet->getPageSetup()
                    ->setPaperSize(
                        PageSetup::PAPERSIZE_A4
                    );

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);

                $sheet->getPageMargins()
                    ->setTop(0.4);

                $sheet->getPageMargins()
                    ->setRight(0.3);

                $sheet->getPageMargins()
                    ->setBottom(0.4);

                $sheet->getPageMargins()
                    ->setLeft(0.3);

                /*
                |--------------------------------------------------------------------------
                | PRINT AREA
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setPrintArea(
                        "A1:J{$footerRow}"
                    );

                /*
                |--------------------------------------------------------------------------
                | REPEAT TABLE HEADER WHEN PRINTING
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setRowsToRepeatAtTopByStartAndEnd(
                        11,
                        11
                    );

                /*
                |--------------------------------------------------------------------------
                | DOCUMENT PROPERTIES
                |--------------------------------------------------------------------------
                */

                $properties = $sheet
                    ->getParent()
                    ->getProperties();

                $properties->setCreator(
                    'CPC Inventory Management System'
                );

                $properties->setTitle(
                    'CPC Inventory Summary Report'
                );

                $properties->setSubject(
                    'Inventory Assets, Status and Allocation Report'
                );

                $properties->setDescription(
                    'Official system-generated inventory summary report of the Ceylon Petroleum Corporation.'
                );

                /*
                |--------------------------------------------------------------------------
                | ACTIVE CELL
                |--------------------------------------------------------------------------
                */

                $sheet->setSelectedCell('A1');
            },
        ];
    }

    /**
     * ============================================================
     * FILTER HELPERS
     * ============================================================
     */

    protected function getBranchName($id): string
    {
        if (!$id) {
            return 'All Branches';
        }

        return optional(
            Branch::find($id)
        )->name ?? 'Selected Branch';
    }

    protected function getDepartmentName($id): string
    {
        if (!$id) {
            return 'All Departments';
        }

        return optional(
            Department::find($id)
        )->name ?? 'Selected Department';
    }

    protected function getItemTypeName($id): string
    {
        if (!$id) {
            return 'All Item Types';
        }

        return optional(
            ItemType::find($id)
        )->name ?? 'Selected Item Type';
    }

    protected function getStatusName($status): string
    {
        if (!$status) {
            return 'All Statuses';
        }

        return ucwords(
            str_replace(
                '_',
                ' ',
                $status
            )
        );
    }

    protected function formatDate($date): string
    {
        if (!$date) {
            return 'All Dates';
        }

        return date(
            'd M Y',
            strtotime($date)
        );
    }
}

