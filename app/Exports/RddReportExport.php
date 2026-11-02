<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionUnit;
use App\Models\ItemType;
use App\Models\RddTransaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class RddReportExport implements
    FromCollection,
    WithEvents,
    WithColumnWidths
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected array $filters;

    protected Collection $rddTransactions;

    protected string $reportType = 'summary';

    protected string $lastColumn = 'L';


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    |
    | Supports both:
    |
    | new RddReportExport($filters)
    |
    | and
    |
    | new RddReportExport($request, $reportType, $data)
    |
    |
    | This prevents the previous:
    |
    | TypeError:
    | Argument #1 ($request) must be of type Request, array given
    |
    |--------------------------------------------------------------------------
    */

    public function __construct(
        array|\Illuminate\Http\Request $requestOrFilters = [],
        string $reportType = 'summary',
        array $data = []
    ) {
        if ($requestOrFilters instanceof \Illuminate\Http\Request) {
            $this->filters = $requestOrFilters->all();

            $this->reportType = $reportType
                ?: $requestOrFilters->input(
                    'report_type',
                    'summary'
                );

            if (!empty($data)) {
                $this->filters = array_merge(
                    $this->filters,
                    $data
                );
            }
        } else {
            $this->filters = $requestOrFilters;

            $this->reportType =
                $this->filters['report_type']
                ?? $reportType
                ?? 'summary';
        }

        $this->reportType = strtolower(
            (string) $this->reportType
        );

        if (
            !in_array(
                $this->reportType,
                [
                    'summary',
                    'history',
                    'active',
                    'location',
                ],
                true
            )
        ) {
            $this->reportType = 'summary';
        }

        /*
        |--------------------------------------------------------------------------
        | Location report uses fewer columns.
        |--------------------------------------------------------------------------
        */

        $this->lastColumn =
            $this->reportType === 'location'
                ? 'J'
                : (
                    $this->reportType === 'history'
                        ? 'K'
                        : 'L'
                );

        $this->rddTransactions = collect();
    }


    /*
    |--------------------------------------------------------------------------
    | Collection
    |--------------------------------------------------------------------------
    */

    public function collection(): Collection
    {
        /*
        |--------------------------------------------------------------------------
        | Location report
        |--------------------------------------------------------------------------
        */

        if ($this->reportType === 'location') {
            $this->rddTransactions =
                $this->buildLocationRows();

            return $this->rddTransactions;
        }


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = RddTransaction::query()
            ->with([
                'item.itemType',
                'item.branch',
                'item.department',
                'item.functionUnit',
                'item.currentEmployee',
                'employee',
                'histories',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Report Type
        |--------------------------------------------------------------------------
        */

        if ($this->reportType === 'active') {
            $query->where('is_active', true);
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['status']
            )
        ) {
            $query->where(
                'status',
                $this->filters['status']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Active / Inactive
        |--------------------------------------------------------------------------
        */

        if (
            isset($this->filters['active'])
            &&
            $this->filters['active'] !== ''
            &&
            $this->filters['active'] !== null
        ) {
            $activeValue = strtolower(
                (string) $this->filters['active']
            );

            if (
                in_array(
                    $activeValue,
                    [
                        '1',
                        'true',
                        'active',
                    ],
                    true
                )
            ) {
                $query->where(
                    'is_active',
                    true
                );
            } elseif (
                in_array(
                    $activeValue,
                    [
                        '0',
                        'false',
                        'inactive',
                    ],
                    true
                )
            ) {
                $query->where(
                    'is_active',
                    false
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['employee_id']
            )
        ) {
            $employeeId =
                $this->filters['employee_id'];

            $query->where(function ($q) use ($employeeId) {
                $q->where(
                    'employee_id',
                    $employeeId
                )->orWhereHas(
                    'item',
                    function ($itemQuery) use ($employeeId) {
                        $itemQuery->where(
                            'current_employee_id',
                            $employeeId
                        );
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Branch
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['branch_id']
            )
        ) {
            $branchId =
                $this->filters['branch_id'];

            $query->whereHas(
                'item',
                function ($itemQuery) use ($branchId) {
                    $itemQuery->where(
                        'branch_id',
                        $branchId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['department_id']
            )
        ) {
            $departmentId =
                $this->filters['department_id'];

            $query->whereHas(
                'item',
                function ($itemQuery) use ($departmentId) {
                    $itemQuery->where(
                        'department_id',
                        $departmentId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Function Unit
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['function_unit_id']
            )
        ) {
            $functionUnitId =
                $this->filters['function_unit_id'];

            $query->whereHas(
                'item',
                function ($itemQuery) use ($functionUnitId) {
                    $itemQuery->where(
                        'function_unit_id',
                        $functionUnitId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Item Type
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['item_type_id']
            )
        ) {
            $itemTypeId =
                $this->filters['item_type_id'];

            $query->whereHas(
                'item',
                function ($itemQuery) use ($itemTypeId) {
                    $itemQuery->where(
                        'item_type_id',
                        $itemTypeId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['from_date']
            )
        ) {
            $query->whereDate(
                'rdd_date',
                '>=',
                $this->filters['from_date']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['to_date']
            )
        ) {
            $query->whereDate(
                'rdd_date',
                '<=',
                $this->filters['to_date']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['search']
            )
        ) {
            $search = trim(
                $this->filters['search']
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'reference_no',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'reason',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'recommendation',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas(
                    'item',
                    function ($itemQuery) use ($search) {

                        $itemQuery
                            ->where(
                                'asset_number',
                                'like',
                                '%' . $search . '%'
                            )

                            ->orWhere(
                                'serial_number',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                )

                ->orWhereHas(
                    'employee',
                    function ($employeeQuery) use ($search) {

                        $employeeQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )

                            ->orWhere(
                                'epf_no',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($this->reportType === 'history') {
            $query->latest('updated_at');
        } else {
            $query->latest('id');
        }


        /*
        |--------------------------------------------------------------------------
        | Execute
        |--------------------------------------------------------------------------
        */

        $this->rddTransactions =
            $query->get();


        return $this->rddTransactions;
    }


    /*
    |--------------------------------------------------------------------------
    | Location Rows
    |--------------------------------------------------------------------------
    */

    protected function buildLocationRows(): Collection
    {
        $query = RddTransaction::query()
            ->with([
                'item.branch',
                'item.department',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Apply common filters
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['status']
            )
        ) {
            $query->where(
                'status',
                $this->filters['status']
            );
        }


        if (
            !empty(
                $this->filters['branch_id']
            )
        ) {
            $branchId =
                $this->filters['branch_id'];

            $query->whereHas(
                'item',
                function ($q) use ($branchId) {
                    $q->where(
                        'branch_id',
                        $branchId
                    );
                }
            );
        }


        if (
            !empty(
                $this->filters['department_id']
            )
        ) {
            $departmentId =
                $this->filters['department_id'];

            $query->whereHas(
                'item',
                function ($q) use ($departmentId) {
                    $q->where(
                        'department_id',
                        $departmentId
                    );
                }
            );
        }


        if (
            !empty(
                $this->filters['item_type_id']
            )
        ) {
            $itemTypeId =
                $this->filters['item_type_id'];

            $query->whereHas(
                'item',
                function ($q) use ($itemTypeId) {
                    $q->where(
                        'item_type_id',
                        $itemTypeId
                    );
                }
            );
        }


        if (
            !empty(
                $this->filters['from_date']
            )
        ) {
            $query->whereDate(
                'rdd_date',
                '>=',
                $this->filters['from_date']
            );
        }


        if (
            !empty(
                $this->filters['to_date']
            )
        ) {
            $query->whereDate(
                'rdd_date',
                '<=',
                $this->filters['to_date']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Active Filter
        |--------------------------------------------------------------------------
        */

        if (
            isset($this->filters['active'])
            &&
            $this->filters['active'] !== ''
        ) {
            $activeValue = strtolower(
                (string) $this->filters['active']
            );

            if (
                in_array(
                    $activeValue,
                    [
                        '1',
                        'true',
                        'active',
                    ],
                    true
                )
            ) {
                $query->where(
                    'is_active',
                    true
                );
            } elseif (
                in_array(
                    $activeValue,
                    [
                        '0',
                        'false',
                        'inactive',
                    ],
                    true
                )
            ) {
                $query->where(
                    'is_active',
                    false
                );
            }
        }


        $records = $query->get();


        /*
        |--------------------------------------------------------------------------
        | Group By Branch
        |--------------------------------------------------------------------------
        */

        return $records
            ->groupBy(
                function ($rdd) {
                    return
                        $rdd->item?->branch?->id
                        ?? 0;
                }
            )
            ->map(
                function ($records) {

                    $branch =
                        $records
                            ->first()
                            ?->item
                            ?->branch;

                    $departments =
                        $records
                            ->map(
                                function ($rdd) {
                                    return
                                        $rdd
                                            ->item
                                            ?->department
                                            ?->name;
                                }
                            )
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values()
                            ->implode(', ');


                    return [
                        'branch_id' =>
                            $branch?->id,

                        'branch_name' =>
                            $branch?->name
                            ?? $branch?->branch_name
                            ?? 'Unknown Location',

                        'total' =>
                            $records->count(),

                        'active' =>
                            $records
                                ->where(
                                    'is_active',
                                    true
                                )
                                ->count(),

                        'pending' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'pending',
                                        'approved',
                                        'dispatched',
                                    ]
                                )
                                ->count(),

                        'received' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'received',
                                        'inspection',
                                        'in_progress',
                                    ]
                                )
                                ->count(),

                        'completed' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'completed',
                                        'returned',
                                    ]
                                )
                                ->count(),

                        'beyond_repair' =>
                            $records
                                ->where(
                                    'status',
                                    'beyond_repair'
                                )
                                ->count(),

                        'rejected' =>
                            $records
                                ->whereIn(
                                    'status',
                                    [
                                        'rejected',
                                        'cancelled',
                                    ]
                                )
                                ->count(),

                        'departments' =>
                            $departments ?: '—',
                    ];
                }
            )
            ->sortBy(
                'branch_name',
                SORT_NATURAL | SORT_FLAG_CASE
            )
            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */

    public function columnWidths(): array
    {
        if ($this->reportType === 'location') {
            return [
                'A' => 6,
                'B' => 25,
                'C' => 12,
                'D' => 12,
                'E' => 13,
                'F' => 13,
                'G' => 14,
                'H' => 15,
                'I' => 17,
                'J' => 38,
            ];
        }


        if ($this->reportType === 'history') {
            return [
                'A' => 6,
                'B' => 20,
                'C' => 18,
                'D' => 18,
                'E' => 20,
                'F' => 22,
                'G' => 15,
                'H' => 21,
                'I' => 14,
                'J' => 18,
                'K' => 16,
            ];
        }


        return [
            'A' => 6,
            'B' => 19,
            'C' => 17,
            'D' => 18,
            'E' => 20,
            'F' => 21,
            'G' => 22,
            'H' => 24,
            'I' => 15,
            'J' => 20,
            'K' => 16,
            'L' => 38,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (
                AfterSheet $event
            ) {

                $sheet =
                    $event
                        ->sheet
                        ->getDelegate();


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | The collection itself is intentionally not rendered by Laravel
                | Excel. Everything is positioned manually below.
                |
                | This avoids duplicate tables and gives us exact control over
                | the CPC report layout.
                |
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | Sheet
                |--------------------------------------------------------------------------
                */

                $sheet->setTitle(
                    'RDD Report'
                );

                $sheet->setShowGridlines(
                    false
                );


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
                        'CPC Logo'
                    );

                    $drawing->setDescription(
                        'Ceylon Petroleum Corporation Logo'
                    );

                    $drawing->setPath(
                        $logoPath
                    );

                    $drawing->setHeight(
                        72
                    );

                    $drawing->setCoordinates(
                        'A1'
                    );

                    $drawing->setOffsetX(
                        10
                    );

                    $drawing->setOffsetY(
                        5
                    );

                    $drawing->setWorksheet(
                        $sheet
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Corporate Header
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "B1:{$this->lastColumn}1"
                );

                $sheet->setCellValue(
                    'B1',
                    'CEYLON PETROLEUM CORPORATION'
                );


                $sheet->mergeCells(
                    "B2:{$this->lastColumn}2"
                );

                $sheet->setCellValue(
                    'B2',
                    'INFORMATION TECHNOLOGY FUNCTION'
                );


                $sheet->mergeCells(
                    "B3:{$this->lastColumn}3"
                );

                $sheet->setCellValue(
                    'B3',
                    'CPC INVENTORY MANAGEMENT SYSTEM'
                );


                $sheet->mergeCells(
                    "B4:{$this->lastColumn}4"
                );

                $sheet->setCellValue(
                    'B4',
                    $this->reportTitle()
                );


                $sheet->mergeCells(
                    "B5:{$this->lastColumn}5"
                );

                $sheet->setCellValue(
                    'B5',
                    'Generated on: ' .
                    now()->format(
                        'd M Y, h:i A'
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | Header Heights
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(31);

                $sheet
                    ->getRowDimension(2)
                    ->setRowHeight(19);

                $sheet
                    ->getRowDimension(3)
                    ->setRowHeight(19);

                $sheet
                    ->getRowDimension(4)
                    ->setRowHeight(26);

                $sheet
                    ->getRowDimension(5)
                    ->setRowHeight(18);


                /*
                |--------------------------------------------------------------------------
                | Header Alignment
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle(
                        "B1:{$this->lastColumn}5"
                    )
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    )
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );


                /*
                |--------------------------------------------------------------------------
                | Header Fonts
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle(
                        "B1:{$this->lastColumn}1"
                    )
                    ->getFont()
                    ->setBold(true)
                    ->setSize(16);


                $sheet
                    ->getStyle(
                        "B2:{$this->lastColumn}3"
                    )
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);


                $sheet
                    ->getStyle(
                        "B4:{$this->lastColumn}4"
                    )
                    ->getFont()
                    ->setBold(true)
                    ->setSize(13);


                $sheet
                    ->getStyle(
                        "B5:{$this->lastColumn}5"
                    )
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(9);


                /*
                |--------------------------------------------------------------------------
                | Header Separator
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle(
                        "A5:{$this->lastColumn}5"
                    )
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(
                        Border::BORDER_MEDIUM
                    )
                    ->getColor()
                    ->setARGB(
                        '0D3B66'
                    );


                /*
                |--------------------------------------------------------------------------
                | Criteria
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "A7:{$this->lastColumn}7"
                );

                $sheet->setCellValue(
                    'A7',
                    'REPORT CRITERIA'
                );

                $sheet
                    ->getStyle(
                        "A7:{$this->lastColumn}7"
                    )
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet
                    ->getStyle(
                        "A7:{$this->lastColumn}7"
                    )
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB(
                        'D9EAF7'
                    );


                $sheet
                    ->getStyle(
                        "A7:{$this->lastColumn}7"
                    )
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );


                /*
                |--------------------------------------------------------------------------
                | Criteria Value
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "A8:{$this->lastColumn}8"
                );

                $sheet->setCellValue(
                    'A8',
                    $this->buildCriteria()
                    ?: 'All RDD Records'
                );

                $sheet
                    ->getStyle(
                        "A8:{$this->lastColumn}8"
                    )
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB(
                        'F8FAFC'
                    );

                $sheet
                    ->getStyle(
                        "A8:{$this->lastColumn}8"
                    )
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );

                $sheet
                    ->getStyle(
                        "A8:{$this->lastColumn}8"
                    )
                    ->getFont()
                    ->setSize(9);

                $sheet
                    ->getRowDimension(8)
                    ->setRowHeight(34);


                /*
                |--------------------------------------------------------------------------
                | Summary Heading
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "A9:{$this->lastColumn}9"
                );

                $sheet->setCellValue(
                    'A9',
                    'REPORT SUMMARY'
                );

                $sheet
                    ->getStyle(
                        "A9:{$this->lastColumn}9"
                    )
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet
                    ->getStyle(
                        "A9:{$this->lastColumn}9"
                    )
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB(
                        'D9EAF7'
                    );


                /*
                |--------------------------------------------------------------------------
                | Summary Cards
                |--------------------------------------------------------------------------
                */

                $this->renderSummaryCards(
                    $sheet
                );


                /*
                |--------------------------------------------------------------------------
                | Table
                |--------------------------------------------------------------------------
                */

                $tableHeaderRow = 11;

                $headings =
                    $this->headings();

                foreach (
                    $headings as $index => $heading
                ) {
                    $column =
                        $this->columnLetter(
                            $index + 1
                        );

                    $sheet->setCellValue(
                        $column .
                        $tableHeaderRow,
                        $heading
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Table Header Styling
                |--------------------------------------------------------------------------
                */

                $headerRange =
                    "A{$tableHeaderRow}:{$this->lastColumn}{$tableHeaderRow}";

                $sheet
                    ->getStyle($headerRange)
                    ->getFont()
                    ->setBold(true)
                    ->setSize(9)
                    ->getColor()
                    ->setARGB(
                        'FFFFFF'
                    );

                $sheet
                    ->getStyle($headerRange)
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    )
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    )
                    ->setWrapText(true);

                $sheet
                    ->getStyle($headerRange)
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB(
                        '0D3B66'
                    );

                $sheet
                    ->getStyle($headerRange)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    )
                    ->getColor()
                    ->setARGB(
                        'FFFFFF'
                    );

                $sheet
                    ->getRowDimension(
                        $tableHeaderRow
                    )
                    ->setRowHeight(36);


                /*
                |--------------------------------------------------------------------------
                | Data
                |--------------------------------------------------------------------------
                */

                $dataStartRow = 12;

                $records =
                    $this->rddTransactions
                        ->values();


                foreach (
                    $records as $index => $record
                ) {

                    $rowNumber =
                        $dataStartRow +
                        $index;

                    $row =
                        $this->mapRecord(
                            $record,
                            $index + 1
                        );


                    foreach (
                        $row as $columnIndex => $value
                    ) {

                        $column =
                            $this->columnLetter(
                                $columnIndex + 1
                            );

                        $sheet->setCellValue(
                            $column .
                            $rowNumber,
                            $value
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Row Height
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getRowDimension(
                            $rowNumber
                        )
                        ->setRowHeight(
                            $this->reportType === 'history'
                                ? 42
                                : 44
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Alternating Row
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $index % 2 === 1
                    ) {
                        $sheet
                            ->getStyle(
                                "A{$rowNumber}:{$this->lastColumn}{$rowNumber}"
                            )
                            ->getFill()
                            ->setFillType(
                                Fill::FILL_SOLID
                            )
                            ->getStartColor()
                            ->setARGB(
                                'F8FAFC'
                            );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Empty State
                |--------------------------------------------------------------------------
                */

                if (
                    $records->isEmpty()
                ) {

                    $sheet->mergeCells(
                        "A{$dataStartRow}:{$this->lastColumn}{$dataStartRow}"
                    );

                    $emptyMessage =
                        $this->reportType === 'location'
                            ? 'No RDD location records found for the selected criteria.'
                            : 'No RDD records found for the selected criteria.';

                    $sheet->setCellValue(
                        'A' . $dataStartRow,
                        $emptyMessage
                    );

                    $sheet
                        ->getStyle(
                            "A{$dataStartRow}:{$this->lastColumn}{$dataStartRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        )
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        )
                        ->setWrapText(true);

                    $sheet
                        ->getStyle(
                            "A{$dataStartRow}:{$this->lastColumn}{$dataStartRow}"
                        )
                        ->getFont()
                        ->setItalic(true)
                        ->setSize(9);

                    $sheet
                        ->getStyle(
                            "A{$dataStartRow}:{$this->lastColumn}{$dataStartRow}"
                        )
                        ->getFill()
                        ->setFillType(
                            Fill::FILL_SOLID
                        )
                        ->getStartColor()
                        ->setARGB(
                            'F8FAFC'
                        );

                    $sheet
                        ->getStyle(
                            "A{$dataStartRow}:{$this->lastColumn}{$dataStartRow}"
                        )
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(
                            Border::BORDER_THIN
                        )
                        ->getColor()
                        ->setARGB(
                            'D9E2F3'
                        );

                    $sheet
                        ->getRowDimension(
                            $dataStartRow
                        )
                        ->setRowHeight(30);
                }


                /*
                |--------------------------------------------------------------------------
                | Data Range Styling
                |--------------------------------------------------------------------------
                */

                $lastDataRow =
                    $records->isNotEmpty()
                        ? $dataStartRow +
                          $records->count() -
                          1
                        : $dataStartRow;


                $dataRange =
                    "A{$dataStartRow}:{$this->lastColumn}{$lastDataRow}";


                $sheet
                    ->getStyle($dataRange)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    )
                    ->getColor()
                    ->setARGB(
                        'D9E2F3'
                    );

                $sheet
                    ->getStyle($dataRange)
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_TOP
                    )
                    ->setWrapText(true);


                /*
                |--------------------------------------------------------------------------
                | Number Column
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle(
                        "A{$dataStartRow}:A{$lastDataRow}"
                    )
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );


                /*
                |--------------------------------------------------------------------------
                | Type-specific Styling
                |--------------------------------------------------------------------------
                */

                if (
                    $this->reportType === 'location'
                ) {

                    $sheet
                        ->getStyle(
                            "C{$dataStartRow}:I{$lastDataRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                } elseif (
                    $this->reportType === 'history'
                ) {

                    $sheet
                        ->getStyle(
                            "G{$dataStartRow}:K{$lastDataRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | Date
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            "I{$dataStartRow}:I{$lastDataRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            "J{$dataStartRow}:J{$lastDataRow}"
                        )
                        ->getFont()
                        ->setBold(true);

                    $sheet
                        ->getStyle(
                            "J{$dataStartRow}:J{$lastDataRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Record State
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            "K{$dataStartRow}:K{$lastDataRow}"
                        )
                        ->getFont()
                        ->setBold(true);

                    $sheet
                        ->getStyle(
                            "K{$dataStartRow}:K{$lastDataRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Status Highlighting
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $records as $index => $record
                    ) {

                        $row =
                            $dataStartRow +
                            $index;

                        $status =
                            is_array($record)
                                ? ($record['status'] ?? null)
                                : ($record->status ?? null);


                        $statusColor =
                            $this->statusColor(
                                $status
                            );


                        $sheet
                            ->getStyle(
                                "J{$row}"
                            )
                            ->getFill()
                            ->setFillType(
                                Fill::FILL_SOLID
                            )
                            ->getStartColor()
                            ->setARGB(
                                $statusColor
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Record State Highlighting
                        |--------------------------------------------------------------------------
                        */

                        $isActive =
                            is_array($record)
                                ? ($record['is_active'] ?? false)
                                : ($record->is_active ?? false);


                        $stateColor =
                            $isActive
                                ? 'D1E7DD'
                                : 'F8D7DA';


                        $sheet
                            ->getStyle(
                                "K{$row}"
                            )
                            ->getFill()
                            ->setFillType(
                                Fill::FILL_SOLID
                            )
                            ->getStartColor()
                            ->setARGB(
                                $stateColor
                            );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Signature Section
                |--------------------------------------------------------------------------
                */

                $signatureRow =
                    $lastDataRow + 4;


                $signatureRanges = [
                    [
                        'range' =>
                            "A{$signatureRow}:C{$signatureRow}",

                        'cell' =>
                            "A{$signatureRow}",

                        'value' =>
                            'PREPARED BY',
                    ],

                    [
                        'range' =>
                            "E{$signatureRow}:G{$signatureRow}",

                        'cell' =>
                            "E{$signatureRow}",

                        'value' =>
                            'CHECKED BY',
                    ],

                    [
                        'range' =>
                            "I{$signatureRow}:{$this->lastColumn}{$signatureRow}",

                        'cell' =>
                            "I{$signatureRow}",

                        'value' =>
                            'AUTHORIZED BY',
                    ],
                ];


                foreach (
                    $signatureRanges as $signature
                ) {

                    $sheet->mergeCells(
                        $signature['range']
                    );

                    $sheet->setCellValue(
                        $signature['cell'],
                        $signature['value']
                    );

                    $sheet
                        ->getStyle(
                            $signature['range']
                        )
                        ->getFont()
                        ->setBold(true)
                        ->setSize(9);

                    $sheet
                        ->getStyle(
                            $signature['range']
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Signature Lines
                |--------------------------------------------------------------------------
                */

                $signatureLineRow =
                    $signatureRow + 2;


                $signatureLineRanges = [
                    "A{$signatureLineRow}:C{$signatureLineRow}",
                    "E{$signatureLineRow}:G{$signatureLineRow}",
                    "I{$signatureLineRow}:{$this->lastColumn}{$signatureLineRow}",
                ];


                foreach (
                    $signatureLineRanges as $range
                ) {

                    $cell =
                        explode(
                            ':',
                            $range
                        )[0];

                    $sheet->mergeCells(
                        $range
                    );

                    $sheet->setCellValue(
                        $cell,
                        '____________________________'
                    );

                    $sheet
                        ->getStyle($range)
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Signature Date
                |--------------------------------------------------------------------------
                */

                $signatureDateRow =
                    $signatureLineRow + 1;


                $dateRanges = [
                    "A{$signatureDateRow}:C{$signatureDateRow}",
                    "E{$signatureDateRow}:G{$signatureDateRow}",
                    "I{$signatureDateRow}:{$this->lastColumn}{$signatureDateRow}",
                ];


                foreach (
                    $dateRanges as $range
                ) {

                    $cell =
                        explode(
                            ':',
                            $range
                        )[0];

                    $sheet->mergeCells(
                        $range
                    );

                    $sheet->setCellValue(
                        $cell,
                        'Date: __________________'
                    );

                    $sheet
                        ->getStyle($range)
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    $sheet
                        ->getStyle($range)
                        ->getFont()
                        ->setSize(8);
                }


                /*
                |--------------------------------------------------------------------------
                | Footer
                |--------------------------------------------------------------------------
                */

                $footerRow =
                    $signatureDateRow + 3;


                $sheet->mergeCells(
                    "A{$footerRow}:{$this->lastColumn}{$footerRow}"
                );

                $sheet->setCellValue(
                    "A{$footerRow}",
                    'CPC Inventory Management System | Official RDD Report'
                );

                $sheet
                    ->getStyle(
                        "A{$footerRow}:{$this->lastColumn}{$footerRow}"
                    )
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                $sheet
                    ->getStyle(
                        "A{$footerRow}:{$this->lastColumn}{$footerRow}"
                    )
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(8);


                /*
                |--------------------------------------------------------------------------
                | Freeze Pane
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane(
                    'A12'
                );


                /*
                |--------------------------------------------------------------------------
                | Auto Filter
                |--------------------------------------------------------------------------
                */

                $filterLastRow =
                    max(
                        11,
                        $lastDataRow
                    );


                $sheet->setAutoFilter(
                    "A11:{$this->lastColumn}{$filterLastRow}"
                );


                /*
                |--------------------------------------------------------------------------
                | Page Setup
                |--------------------------------------------------------------------------
                */

                $pageSetup =
                    $sheet->getPageSetup();

                $pageSetup->setOrientation(
                    PageSetup::ORIENTATION_LANDSCAPE
                );

                $pageSetup->setPaperSize(
                    PageSetup::PAPERSIZE_A4
                );

                $pageSetup
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $pageSetup->setHorizontalCentered(
                    true
                );


                /*
                |--------------------------------------------------------------------------
                | Repeat Header
                |--------------------------------------------------------------------------
                */

                $pageSetup
                    ->setRowsToRepeatAtTopByStartAndEnd(
                        11,
                        11
                    );


                /*
                |--------------------------------------------------------------------------
                | Print Area
                |--------------------------------------------------------------------------
                */

                $pageSetup->setPrintArea(
                    "A1:{$this->lastColumn}{$footerRow}"
                );


                /*
                |--------------------------------------------------------------------------
                | Margins
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getPageMargins()
                    ->setTop(0.35)
                    ->setRight(0.25)
                    ->setBottom(0.45)
                    ->setLeft(0.25);


                /*
                |--------------------------------------------------------------------------
                | Header / Footer
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getHeaderFooter()
                    ->setOddHeader(
                        '&L' .
                        $this->reportTitle() .
                        '&RPage &P of &N'
                    );

                $sheet
                    ->getHeaderFooter()
                    ->setOddFooter(
                        '&C CPC Inventory Management System'
                    );


                /*
                |--------------------------------------------------------------------------
                | Document Properties
                |--------------------------------------------------------------------------
                */

                $spreadsheet =
                    $sheet->getParent();


                $spreadsheet
                    ->getProperties()
                    ->setCreator(
                        'CPC Inventory Management System'
                    )
                    ->setLastModifiedBy(
                        'CPC Inventory Management System'
                    )
                    ->setTitle(
                        $this->reportTitle()
                    )
                    ->setSubject(
                        'CPC RDD Management Report'
                    )
                    ->setDescription(
                        'Official RDD report generated by the CPC Inventory Management System.'
                    );


                /*
                |--------------------------------------------------------------------------
                | Tab Color
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getTabColor()
                    ->setRGB(
                        '0D3B66'
                    );
            },
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Headings
    |--------------------------------------------------------------------------
    */

    protected function headings(): array
    {
        if ($this->reportType === 'location') {
            return [
                '#',
                'Branch / Location',
                'Total RDD',
                'Active',
                'Pending',
                'Received',
                'Completed',
                'Beyond Repair',
                'Rejected / Cancelled',
                'Departments',
            ];
        }


        if ($this->reportType === 'history') {
            return [
                '#',
                'RDD Reference',
                'Asset Number',
                'Item Type',
                'Branch',
                'Department',
                'RDD Date',
                'Current Status',
                'History Events',
                'Last Change',
                'Record State',
            ];
        }


        return [
            '#',
            'RDD Reference',
            'Asset Number',
            'Item Type',
            'Serial Number',
            'Branch',
            'Department',
            'Employee',
            'RDD Date',
            'Status',
            'Record State',
            'Recommendation',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Map Record
    |--------------------------------------------------------------------------
    */

    protected function mapRecord(
        mixed $record,
        int $number
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        if (
            $this->reportType === 'location'
        ) {
            return [
                $number,

                $record['branch_name']
                    ?? 'Unknown Location',

                $record['total']
                    ?? 0,

                $record['active']
                    ?? 0,

                $record['pending']
                    ?? 0,

                $record['received']
                    ?? 0,

                $record['completed']
                    ?? 0,

                $record['beyond_repair']
                    ?? 0,

                $record['rejected']
                    ?? 0,

                $record['departments']
                    ?? '—',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | History
        |--------------------------------------------------------------------------
        */

        if (
            $this->reportType === 'history'
        ) {

            $item =
                $record->item;

            $itemType =
                $item?->itemType;

            $branch =
                $item?->branch;

            $department =
                $item?->department;

            $historyCount =
                $record
                    ->histories
                    ?->count()
                    ?? 0;

            $lastHistory =
                $record
                    ->histories
                    ?->sortByDesc(
                        'created_at'
                    )
                    ?->first();

            return [
                $number,

                $record->reference_no
                    ?? 'RDD-' . $record->id,

                $item?->asset_number
                    ?? '—',

                $itemType?->name
                    ?? $itemType?->item_type
                    ?? $itemType?->type_name
                    ?? '—',

                $branch?->name
                    ?? $branch?->branch_name
                    ?? '—',

                $department?->name
                    ?? $department?->department_name
                    ?? '—',

                $record->rdd_date
                    ?->format('d M Y')
                    ?? '—',

                $this->formatStatus(
                    $record->status
                ),

                $historyCount,

                $lastHistory?->created_at
                    ?->format('d M Y, h:i A')
                    ?? '—',

                $record->is_active
                    ? 'Active'
                    : 'Inactive',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Summary / Active
        |--------------------------------------------------------------------------
        */

        $item =
            $record->item;

        $itemType =
            $item?->itemType;

        $branch =
            $item?->branch;

        $department =
            $item?->department;

        $employee =
            $record->employee
            ?? $item?->currentEmployee;


        return [
            $number,

            $record->reference_no
                ?? 'RDD-' . $record->id,

            $item?->asset_number
                ?? '—',

            $itemType?->name
                ?? $itemType?->item_type
                ?? $itemType?->type_name
                ?? '—',

            $item?->serial_number
                ?? '—',

            $branch?->name
                ?? $branch?->branch_name
                ?? '—',

            $department?->name
                ?? $department?->department_name
                ?? '—',

            $employee?->name
                ?? $employee?->full_name
                ?? '—',

            $record->rdd_date
                ?->format('d M Y')
                ?? '—',

            $this->formatStatus(
                $record->status
            ),

            $record->is_active
                ? 'Active'
                : 'Inactive',

            $record->recommendation
                ?? '—',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Summary Cards
    |--------------------------------------------------------------------------
    */

    protected function renderSummaryCards(
        $sheet
    ): void {

        $records =
            $this->rddTransactions;


        if (
            $this->reportType === 'location'
        ) {

            $total =
                $records->sum(
                    'total'
                );

            $active =
                $records->sum(
                    'active'
                );

            $pending =
                $records->sum(
                    'pending'
                );

            $received =
                $records->sum(
                    'received'
                );

            $completed =
                $records->sum(
                    'completed'
                );

            $beyondRepair =
                $records->sum(
                    'beyond_repair'
                );


        } else {

            $total =
                $records->count();

            $active =
                $records->where(
                    'is_active',
                    true
                )->count();

            $pending =
                $records->whereIn(
                    'status',
                    [
                        'pending',
                        'approved',
                        'dispatched',
                    ]
                )->count();

            $received =
                $records->whereIn(
                    'status',
                    [
                        'received',
                        'inspection',
                        'in_progress',
                    ]
                )->count();

            $completed =
                $records->whereIn(
                    'status',
                    [
                        'completed',
                        'returned',
                    ]
                )->count();

            $beyondRepair =
                $records->where(
                    'status',
                    'beyond_repair'
                )->count();
        }


        /*
        |--------------------------------------------------------------------------
        | Six Summary Cards
        |--------------------------------------------------------------------------
        */

        $summaryCards = [
            [
                'cell' => 'A10',
                'range' => 'A10:B10',
                'label' => 'Total RDD Records',
                'value' => $total,
            ],

            [
                'cell' => 'C10',
                'range' => 'C10:D10',
                'label' => 'Active RDD',
                'value' => $active,
            ],

            [
                'cell' => 'E10',
                'range' => 'E10:F10',
                'label' => 'Pending / Processing',
                'value' => $pending,
            ],

            [
                'cell' => 'G10',
                'range' => 'G10:H10',
                'label' => 'Received / Inspection',
                'value' => $received,
            ],

            [
                'cell' => 'I10',
                'range' => 'I10:J10',
                'label' => 'Completed / Returned',
                'value' => $completed,
            ],

            [
                'cell' => 'K10',
                'range' =>
                    $this->lastColumn === 'L'
                        ? 'K10:L10'
                        : "K10:{$this->lastColumn}10",
                'label' => 'Beyond Repair',
                'value' => $beyondRepair,
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | Render Cards
        |--------------------------------------------------------------------------
        */

        foreach (
            $summaryCards as $card
        ) {

            $sheet->mergeCells(
                $card['range']
            );

            $sheet->setCellValue(
                $card['cell'],
                $card['label'] .
                ': ' .
                $card['value']
            );

            $sheet
                ->getStyle(
                    $card['range']
                )
                ->getFont()
                ->setBold(true)
                ->setSize(8);

            $sheet
                ->getStyle(
                    $card['range']
                )
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                )
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                )
                ->setWrapText(true);

            $sheet
                ->getStyle(
                    $card['range']
                )
                ->getFill()
                ->setFillType(
                    Fill::FILL_SOLID
                )
                ->getStartColor()
                ->setARGB(
                    'F4F7FA'
                );

            $sheet
                ->getStyle(
                    $card['range']
                )
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    Border::BORDER_THIN
                )
                ->getColor()
                ->setARGB(
                    'D9E2F3'
                );
        }


        $sheet
            ->getRowDimension(10)
            ->setRowHeight(36);
    }


    /*
    |--------------------------------------------------------------------------
    | Status Color
    |--------------------------------------------------------------------------
    */

    protected function statusColor(
        ?string $status
    ): string {

        return match (
            strtolower(
                (string) $status
            )
        ) {

            'pending' =>
                'FFF3CD',

            'approved' =>
                'DDEBF7',

            'dispatched' =>
                'DDEBF7',

            'received' =>
                'D1ECF1',

            'inspection' =>
                'E2E3E5',

            'in_progress' =>
                'E2E3E5',

            'completed' =>
                'D1E7DD',

            'returned' =>
                'D1ECF1',

            'beyond_repair' =>
                'FCE4D6',

            'rejected' =>
                'F8D7DA',

            'cancelled' =>
                'F8D7DA',

            default =>
                'F8FAFC',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Report Title
    |--------------------------------------------------------------------------
    */

    protected function reportTitle(): string
    {
        return match (
            $this->reportType
        ) {

            'history' =>
                'RDD REPORT — HISTORY',

            'active' =>
                'RDD REPORT — ACTIVE RECORDS',

            'location' =>
                'RDD REPORT — BY LOCATION',

            default =>
                'RDD REPORT — SUMMARY',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Build Criteria
    |--------------------------------------------------------------------------
    */

    protected function buildCriteria(): string
    {
        $criteria = [];


        /*
        |--------------------------------------------------------------------------
        | Report Type
        |--------------------------------------------------------------------------
        */

        $criteria[] =
            'Report Type: ' .
            $this->formatReportType(
                $this->reportType
            );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['status']
            )
        ) {

            $criteria[] =
                'Status: ' .
                $this->formatStatus(
                    $this->filters['status']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Active
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $this->filters['active']
            )
            &&
            $this->filters['active'] !== ''
        ) {

            $active =
                strtolower(
                    (string)
                    $this->filters['active']
                );


            if (
                in_array(
                    $active,
                    [
                        '1',
                        'true',
                        'active',
                    ],
                    true
                )
            ) {

                $criteria[] =
                    'Record State: Active';

            } elseif (
                in_array(
                    $active,
                    [
                        '0',
                        'false',
                        'inactive',
                    ],
                    true
                )
            ) {

                $criteria[] =
                    'Record State: Inactive';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['employee_id']
            )
        ) {

            $employee =
                Employee::find(
                    $this->filters['employee_id']
                );

            $criteria[] =
                'Employee: ' .
                (
                    $employee?->name
                    ?? $employee?->full_name
                    ?? 'Employee #' .
                    $this->filters['employee_id']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Branch
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['branch_id']
            )
        ) {

            $branch =
                Branch::find(
                    $this->filters['branch_id']
                );

            $criteria[] =
                'Branch: ' .
                (
                    $branch?->name
                    ?? $branch?->branch_name
                    ?? 'Branch #' .
                    $this->filters['branch_id']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['department_id']
            )
        ) {

            $department =
                Department::find(
                    $this->filters['department_id']
                );

            $criteria[] =
                'Department: ' .
                (
                    $department?->name
                    ?? $department?->department_name
                    ?? 'Department #' .
                    $this->filters['department_id']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Function Unit
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['function_unit_id']
            )
        ) {

            $functionUnit =
                FunctionUnit::find(
                    $this->filters['function_unit_id']
                );

            $criteria[] =
                'Function Unit: ' .
                (
                    $functionUnit?->name
                    ?? $functionUnit?->function_unit_name
                    ?? 'Function Unit #' .
                    $this->filters['function_unit_id']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Item Type
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['item_type_id']
            )
        ) {

            $itemType =
                ItemType::find(
                    $this->filters['item_type_id']
                );

            $criteria[] =
                'Item Type: ' .
                (
                    $itemType?->name
                    ?? $itemType?->item_type
                    ?? $itemType?->type_name
                    ?? 'Item Type #' .
                    $this->filters['item_type_id']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['from_date']
            )
        ) {

            $criteria[] =
                'From: ' .
                $this->formatDate(
                    $this->filters['from_date']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['to_date']
            )
        ) {

            $criteria[] =
                'To: ' .
                $this->formatDate(
                    $this->filters['to_date']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->filters['search']
            )
        ) {

            $criteria[] =
                'Search: ' .
                trim(
                    $this->filters['search']
                );
        }


        return implode(
            '  |  ',
            $criteria
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Report Type Formatter
    |--------------------------------------------------------------------------
    */

    protected function formatReportType(
        string $value
    ): string {

        return match (
            $value
        ) {

            'summary' =>
                'RDD Summary',

            'history' =>
                'RDD History',

            'active' =>
                'Active RDD Records',

            'location' =>
                'RDD by Location',

            default =>
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $value
                    )
                ),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Status Formatter
    |--------------------------------------------------------------------------
    */

    protected function formatStatus(
        ?string $status
    ): string {

        if (
            !$status
        ) {
            return '—';
        }


        return ucwords(
            str_replace(
                '_',
                ' ',
                $status
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Date Formatter
    |--------------------------------------------------------------------------
    */

    protected function formatDate(
        string $date
    ): string {

        try {

            return \Carbon\Carbon::parse(
                $date
            )->format(
                'd M Y'
            );

        } catch (
            \Throwable $exception
        ) {

            return $date;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Excel Column Letter
    |--------------------------------------------------------------------------
    */

    protected function columnLetter(
        int $number
    ): string {

        $letter = '';

        while (
            $number > 0
        ) {

            $number--;

            $letter =
                chr(
                    65 +
                    ($number % 26)
                ) .
                $letter;

            $number =
                intdiv(
                    $number,
                    26
                );
        }


        return $letter;
    }
}