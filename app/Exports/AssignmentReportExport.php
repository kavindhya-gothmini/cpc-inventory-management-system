<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\ItemAssignment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AssignmentReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
{
    protected array $filters;

    protected string $reportType;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;

        $this->reportType = $filters['report_type'] ?? 'current';
    }

    /**
     * Get report data.
     */
    public function collection(): Collection
    {
        /*
        |--------------------------------------------------------------------------
        | Unassigned Assets
        |--------------------------------------------------------------------------
        */

        if ($this->reportType === 'unassigned') {

            $query = Item::query()
                ->with([
                    'itemType',
                    'branch',
                    'department',
                    'currentEmployee',
                ])
                ->whereDoesntHave('assignments', function ($query) {
                    $query->whereNull('returned_date');
                });

            $this->applyUnassignedFilters($query);

            return $query
                ->latest('id')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Assignment Records
        |--------------------------------------------------------------------------
        */

        $query = ItemAssignment::query()
            ->with([
                'item.itemType',
                'employee',
                'branch',
                'department',
                'functionUnit',
            ]);

        $this->applyAssignmentFilters($query);

        return $query
            ->latest('id')
            ->get();
    }

    /**
     * Excel column headings.
     */
    public function headings(): array
    {
        if ($this->reportType === 'unassigned') {
            return [
                '#',
                'Asset Number',
                'Item Type',
                'Make / Model',
                'Serial Number',
                'Branch',
                'Department',
                'Current Employee',
                'Status',
            ];
        }

        return [
            '#',
            'Asset Number',
            'Item Type',
            'Employee',
            'Branch',
            'Department',
            'Function Unit',
            'Workstation',
            'Assigned Date',
            'Returned Date',
            'Status',
        ];
    }

    /**
     * Map each record to an Excel row.
     */
    public function map($row): array
    {
        if ($this->reportType === 'unassigned') {

            return [
                $row->id,

                $row->asset_number ?? '—',

                optional($row->itemType)->name ?? '—',

                trim(
                    ($row->make ?? '') . ' ' . ($row->model ?? '')
                ) ?: '—',

                $row->serial_number ?? '—',

                optional($row->branch)->name ?? '—',

                optional($row->department)->name ?? '—',

                optional($row->currentEmployee)->name ?? 'Unassigned',

                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $row->status ?? '—'
                    )
                ),
            ];
        }

        $isIssued = is_null($row->returned_date);

        return [
            $row->id,

            optional($row->item)->asset_number ?? '—',

            optional(
                optional($row->item)->itemType
            )->name ?? '—',

            optional($row->employee)->name ?? '—',

            optional($row->branch)->name ?? '—',

            optional($row->department)->name ?? '—',

            optional($row->functionUnit)->name ?? '—',

            $row->workstation_id ?? '—',

            optional($row->assigned_date)->format('Y-m-d') ?? '—',

            optional($row->returned_date)->format('Y-m-d') ?? '—',

            $isIssued ? 'Issued' : 'Returned',
        ];
    }

    /**
     * Apply assignment report filters.
     */
    protected function applyAssignmentFilters($query)
    {
        $employeeId = $this->filters['employee_id'] ?? null;
        $status = $this->filters['status'] ?? null;
        $branchId = $this->filters['branch_id'] ?? null;
        $departmentId = $this->filters['department_id'] ?? null;
        $functionUnitId = $this->filters['function_unit_id'] ?? null;
        $itemTypeId = $this->filters['item_type_id'] ?? null;
        $fromDate = $this->filters['from_date'] ?? null;
        $toDate = $this->filters['to_date'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Current Assignments
        |--------------------------------------------------------------------------
        */

        if ($this->reportType === 'current') {
            $query->whereNull('returned_date');
        }

        /*
        |--------------------------------------------------------------------------
        | Employee Assets
        |--------------------------------------------------------------------------
        */

        if ($this->reportType === 'employee' && $employeeId) {
            $query->where('employee_id', $employeeId);
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($status === 'issued') {
            $query->whereNull('returned_date');
        }

        if ($status === 'returned') {
            $query->whereNotNull('returned_date');
        }

        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        /*
        |--------------------------------------------------------------------------
        | Branch
        |--------------------------------------------------------------------------
        */

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        /*
        |--------------------------------------------------------------------------
        | Function Unit
        |--------------------------------------------------------------------------
        */

        if ($functionUnitId) {
            $query->where('function_unit_id', $functionUnitId);
        }

        /*
        |--------------------------------------------------------------------------
        | Item Type
        |--------------------------------------------------------------------------
        */

        if ($itemTypeId) {

            $query->whereHas('item', function ($itemQuery) use ($itemTypeId) {

                $itemQuery->where(
                    'item_type_id',
                    $itemTypeId
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Assigned Date Range
        |--------------------------------------------------------------------------
        */

        if ($fromDate) {

            $query->whereDate(
                'assigned_date',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {

            $query->whereDate(
                'assigned_date',
                '<=',
                $toDate
            );
        }

        return $query;
    }

    /**
     * Apply unassigned asset filters.
     */
    protected function applyUnassignedFilters($query)
    {
        $branchId = $this->filters['branch_id'] ?? null;
        $departmentId = $this->filters['department_id'] ?? null;
        $itemTypeId = $this->filters['item_type_id'] ?? null;
        $fromDate = $this->filters['from_date'] ?? null;
        $toDate = $this->filters['to_date'] ?? null;

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($itemTypeId) {
            $query->where(
                'item_type_id',
                $itemTypeId
            );
        }

        if ($fromDate) {
            $query->whereDate(
                'created_at',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {
            $query->whereDate(
                'created_at',
                '<=',
                $toDate
            );
        }

        return $query;
    }

    /**
     * Register Excel formatting events.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | Insert Professional CPC Header
                |--------------------------------------------------------------------------
                |
                | WithHeadings writes the actual table headings starting
                | at row 1. We insert 10 rows so the table begins at row 11.
                |
                */

                $sheet->insertNewRowBefore(1, 10);

                /*
                |--------------------------------------------------------------------------
                | CPC Logo
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
                | CPC Header
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('B1:K1');

                $sheet->setCellValue(
                    'B1',
                    'CEYLON PETROLEUM CORPORATION'
                );

                $sheet->mergeCells('B2:K2');

                $sheet->setCellValue(
                    'B2',
                    'INFORMATION TECHNOLOGY FUNCTION'
                );

                $sheet->mergeCells('B3:K3');

                $sheet->setCellValue(
                    'B3',
                    'CPC INVENTORY MANAGEMENT SYSTEM'
                );

                $sheet->mergeCells('B4:K4');

                $sheet->setCellValue(
                    'B4',
                    'OFFICIAL DOCUMENT'
                );

                $sheet->mergeCells('B5:K5');

                $sheet->setCellValue(
                    'B5',
                    'Assignment Report'
                );

                $sheet->mergeCells('B6:K6');

                $sheet->setCellValue(
                    'B6',
                    'Asset Assignment, Employee Allocation and Assignment History'
                );

                /*
                |--------------------------------------------------------------------------
                | Generated Date
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('B7:K7');

                $sheet->setCellValue(
                    'B7',
                    'Generated: ' . now()->format('Y-m-d H:i:s')
                );

                /*
                |--------------------------------------------------------------------------
                | Report Criteria
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A8:K8');

                $sheet->setCellValue(
                    'A8',
                    'REPORT CRITERIA'
                );

                $criteria = $this->getCriteriaText();

                $sheet->mergeCells('A9:K9');

                $sheet->setCellValue(
                    'A9',
                    $criteria
                );

                /*
                |--------------------------------------------------------------------------
                | Summary Heading
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A10:K10');

                $sheet->setCellValue(
                    'A10',
                    'ASSIGNMENT RECORDS'
                );

                /*
                |--------------------------------------------------------------------------
                | Header Styling
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('B1:K1')->applyFromArray([
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

                $sheet->getStyle('B2:K2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                    ],
                ]);

                $sheet->getStyle('B3:K3')->applyFromArray([
                    'font' => [
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                    ],
                ]);

                $sheet->getStyle('B4:K4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                    ],
                ]);

                $sheet->getStyle('B5:K5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 15,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B6:K6')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B7:K7')->applyFromArray([
                    'font' => [
                        'size' => 9,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Criteria Styling
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A8:K8')->applyFromArray([
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
                    ],
                ]);

                $sheet->getStyle('A9:K9')->applyFromArray([
                    'font' => [
                        'size' => 9,
                    ],
                    'alignment' => [
                        'wrapText' => true,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A10:K10')->applyFromArray([
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
                ]);

                /*
                |--------------------------------------------------------------------------
                | Actual Table Header
                |--------------------------------------------------------------------------
                */

                $lastColumn = $this->reportType === 'unassigned'
                    ? 'I'
                    : 'K';

                $headerRange = 'A11:' . $lastColumn . '11';

                $sheet->getStyle($headerRange)->applyFromArray([
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
                | Data Borders
                |--------------------------------------------------------------------------
                */

                $highestRow = $sheet->getHighestRow();

                if ($highestRow >= 12) {

                    $dataRange = 'A12:' .
                        $lastColumn .
                        $highestRow;

                    $sheet->getStyle($dataRange)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => 'DEE2E6',
                                ],
                            ],
                        ],
                        'alignment' => [
                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Alternating Row Formatting
                    |--------------------------------------------------------------------------
                    */

                    for ($row = 12; $row <= $highestRow; $row++) {

                        if ($row % 2 === 0) {

                            $sheet
                                ->getStyle(
                                    'A' . $row . ':' .
                                    $lastColumn . $row
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
                }

                /*
                |--------------------------------------------------------------------------
                | Column Widths
                |--------------------------------------------------------------------------
                */

                $widths = [
                    'A' => 7,
                    'B' => 18,
                    'C' => 20,
                    'D' => 25,
                    'E' => 20,
                    'F' => 25,
                    'G' => 25,
                    'H' => 18,
                    'I' => 16,
                    'J' => 16,
                    'K' => 14,
                ];

                foreach ($widths as $column => $width) {

                    $sheet
                        ->getColumnDimension($column)
                        ->setWidth($width);
                }

                /*
                |--------------------------------------------------------------------------
                | Row Heights
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(22);
                $sheet->getRowDimension(3)->setRowHeight(20);
                $sheet->getRowDimension(5)->setRowHeight(24);
                $sheet->getRowDimension(6)->setRowHeight(20);
                $sheet->getRowDimension(9)->setRowHeight(30);
                $sheet->getRowDimension(11)->setRowHeight(30);

                /*
                |--------------------------------------------------------------------------
                | Freeze Table Header
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A12');

                /*
                |--------------------------------------------------------------------------
                | Auto Filter
                |--------------------------------------------------------------------------
                */

                if ($highestRow >= 11) {

                    $sheet->setAutoFilter(
                        'A11:' .
                        $lastColumn .
                        $highestRow
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Signature Section
                |--------------------------------------------------------------------------
                */

                $signatureStartRow = $highestRow + 3;

                $sheet->mergeCells(
                    'A' . $signatureStartRow .
                    ':' .
                    $lastColumn .
                    $signatureStartRow
                );

                $sheet->setCellValue(
                    'A' . $signatureStartRow,
                    'DOCUMENT CERTIFICATION & AUTHORIZATION'
                );

                $sheet->getStyle(
                    'A' . $signatureStartRow .
                    ':' .
                    $lastColumn .
                    $signatureStartRow
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
                ]);

                $signatureRow = $signatureStartRow + 2;

                /*
                |--------------------------------------------------------------------------
                | Prepared By
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    'A' . $signatureRow .
                    ':C' . $signatureRow
                );

                $sheet->setCellValue(
                    'A' . $signatureRow,
                    'Prepared By'
                );

                $sheet->mergeCells(
                    'A' . ($signatureRow + 1) .
                    ':C' . ($signatureRow + 1)
                );

                $sheet->setCellValue(
                    'A' . ($signatureRow + 1),
                    '____________________________'
                );

                $sheet->mergeCells(
                    'A' . ($signatureRow + 2) .
                    ':C' . ($signatureRow + 2)
                );

                $sheet->setCellValue(
                    'A' . ($signatureRow + 2),
                    'Name / Signature / Date'
                );

                /*
                |--------------------------------------------------------------------------
                | Checked By
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    'E' . $signatureRow .
                    ':G' . $signatureRow
                );

                $sheet->setCellValue(
                    'E' . $signatureRow,
                    'Checked By'
                );

                $sheet->mergeCells(
                    'E' . ($signatureRow + 1) .
                    ':G' . ($signatureRow + 1)
                );

                $sheet->setCellValue(
                    'E' . ($signatureRow + 1),
                    '____________________________'
                );

                $sheet->mergeCells(
                    'E' . ($signatureRow + 2) .
                    ':G' . ($signatureRow + 2)
                );

                $sheet->setCellValue(
                    'E' . ($signatureRow + 2),
                    'Name / Signature / Date'
                );

                /*
                |--------------------------------------------------------------------------
                | Authorized By
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    'I' . $signatureRow .
                    ':' .
                    $lastColumn .
                    $signatureRow
                );

                $sheet->setCellValue(
                    'I' . $signatureRow,
                    'Authorized By'
                );

                $sheet->mergeCells(
                    'I' . ($signatureRow + 1) .
                    ':' .
                    $lastColumn .
                    ($signatureRow + 1)
                );

                $sheet->setCellValue(
                    'I' . ($signatureRow + 1),
                    '____________________________'
                );

                $sheet->mergeCells(
                    'I' . ($signatureRow + 2) .
                    ':' .
                    $lastColumn .
                    ($signatureRow + 2)
                );

                $sheet->setCellValue(
                    'I' . ($signatureRow + 2),
                    'Name / Signature / Date'
                );

                /*
                |--------------------------------------------------------------------------
                | Signature Styling
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    'A' . $signatureRow .
                    ':' .
                    $lastColumn .
                    ($signatureRow + 2)
                )->applyFromArray([
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle(
                    'A' . $signatureRow .
                    ':' .
                    $lastColumn . $signatureRow
                )->getFont()->setBold(true);

                /*
                |--------------------------------------------------------------------------
                | Footer
                |--------------------------------------------------------------------------
                */

                $footerRow = $signatureRow + 5;

                $sheet->mergeCells(
                    'A' . $footerRow .
                    ':' .
                    $lastColumn . $footerRow
                );

                $sheet->setCellValue(
                    'A' . $footerRow,
                    'CPC Inventory Management System | Information Technology Function | Ceylon Petroleum Corporation'
                );

                $sheet->getStyle(
                    'A' . $footerRow .
                    ':' .
                    $lastColumn . $footerRow
                )->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 9,
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Page Setup
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setOrientation('landscape');

                $sheet->getPageSetup()
                    ->setPaperSize(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
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
                | Print Title Row
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setRowsToRepeatAtTopByStartAndEnd(11, 11);

                /*
                |--------------------------------------------------------------------------
                | Document Properties
                |--------------------------------------------------------------------------
                */

                $properties = $sheet
                    ->getParent()
                    ->getProperties();

                $properties->setCreator(
                    'CPC Inventory Management System'
                );

                $properties->setTitle(
                    'CPC Assignment Report'
                );

                $properties->setSubject(
                    'Asset Assignment and Employee Allocation Report'
                );

                $properties->setDescription(
                    'System-generated CPC Assignment Report'
                );
            },
        ];
    }

    /**
     * Generate report criteria text.
     */
    protected function getCriteriaText(): string
    {
        $reportType = match ($this->reportType) {

            'current' =>
                'Current Assignments',

            'history' =>
                'Assignment History',

            'employee' =>
                'Employee Assets',

            'unassigned' =>
                'Unassigned Assets',

            default =>
                'All Assignments',
        };

        $criteria = [
            'Report Type: ' . $reportType,
            'Employee: ' .
                $this->getEmployeeName(
                    $this->filters['employee_id'] ?? null
                ),
            'Status: ' .
                $this->getStatusName(
                    $this->filters['status'] ?? null
                ),
            'Branch: ' .
                $this->getBranchName(
                    $this->filters['branch_id'] ?? null
                ),
            'Department: ' .
                $this->getDepartmentName(
                    $this->filters['department_id'] ?? null
                ),
            'Function Unit: ' .
                $this->getFunctionUnitName(
                    $this->filters['function_unit_id'] ?? null
                ),
            'Item Type: ' .
                $this->getItemTypeName(
                    $this->filters['item_type_id'] ?? null
                ),
            'From Date: ' .
                ($this->filters['from_date'] ?? 'All Dates'),
            'To Date: ' .
                ($this->filters['to_date'] ?? 'All Dates'),
        ];

        return implode(' | ', $criteria);
    }

    protected function getEmployeeName($id): string
    {
        if (!$id) {
            return 'All Employees';
        }

        return optional(
            \App\Models\Employee::find($id)
        )->name ?? 'Selected Employee';
    }

    protected function getBranchName($id): string
    {
        if (!$id) {
            return 'All Branches';
        }

        return optional(
            \App\Models\Branch::find($id)
        )->name ?? 'Selected Branch';
    }

    protected function getDepartmentName($id): string
    {
        if (!$id) {
            return 'All Departments';
        }

        return optional(
            \App\Models\Department::find($id)
        )->name ?? 'Selected Department';
    }

    protected function getFunctionUnitName($id): string
    {
        if (!$id) {
            return 'All Function Units';
        }

        return optional(
            \App\Models\FunctionUnit::find($id)
        )->name ?? 'Selected Function Unit';
    }

    protected function getItemTypeName($id): string
    {
        if (!$id) {
            return 'All Item Types';
        }

        return optional(
            \App\Models\ItemType::find($id)
        )->name ?? 'Selected Item Type';
    }

    protected function getStatusName($status): string
    {
        return match ($status) {

            'issued' =>
                'Issued',

            'returned' =>
                'Returned',

            default =>
                'All Statuses',
        };
    }
}

