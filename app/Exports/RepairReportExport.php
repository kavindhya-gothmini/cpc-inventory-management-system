<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionUnit;
use App\Models\ItemType;
use App\Models\Repair;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class RepairReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithColumnWidths
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected array $filters;

    protected Collection $repairs;

    protected array $repairNumbers = [];


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;

        $this->repairs = collect();

        $this->repairNumbers = [];
    }


    /*
    |--------------------------------------------------------------------------
    | Collection
    |--------------------------------------------------------------------------
    */

    public function collection(): Collection
    {
        $reportType = $this->filters['report_type'] ?? 'summary';

        $query = Repair::query()
            ->with([
                'item.itemType',
                'item.branch',
                'item.department',
                'item.functionUnit',
                'employee',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Report Type Filter
        |--------------------------------------------------------------------------
        */

        if ($reportType === 'pending') {
            $query->whereIn('status', [
                'pending',
                'sent_for_repair',
                'under_repair',
            ]);
        }

        if ($reportType === 'completed') {
            $query->whereIn('status', [
                'completed',
                'returned',
            ]);
        }

        if ($reportType === 'pending_completed') {
            $query->whereIn('status', [
                'pending',
                'sent_for_repair',
                'under_repair',
                'completed',
                'returned',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
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
        | Employee Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['employee_id'])) {
            $query->where(
                'employee_id',
                $this->filters['employee_id']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Branch Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['branch_id'])) {
            $branchId = $this->filters['branch_id'];

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
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['department_id'])) {
            $departmentId = $this->filters['department_id'];

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
        | Function Unit Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['function_unit_id'])) {
            $functionUnitId = $this->filters['function_unit_id'];

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
        | Item Type Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['item_type_id'])) {
            $itemTypeId = $this->filters['item_type_id'];

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
        | Technician Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['technician'])) {
            $technician = trim(
                $this->filters['technician']
            );

            $query->where(
                'technician',
                'like',
                '%' . $technician . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['from_date'])) {
            $query->whereDate(
                'date_sent',
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
                'date_sent',
                '<=',
                $this->filters['to_date']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($reportType === 'cost') {
            $query->orderByDesc('repair_cost');
        } else {
            $query->latest('id');
        }


        /*
        |--------------------------------------------------------------------------
        | Execute Query
        |--------------------------------------------------------------------------
        */

        $this->repairs = $query->get();


        /*
        |--------------------------------------------------------------------------
        | Stable Row Numbers
        |--------------------------------------------------------------------------
        */

        $this->repairNumbers = $this->repairs
            ->values()
            ->mapWithKeys(
                function ($repair, $index) {
                    return [
                        $repair->id => $index + 1,
                    ];
                }
            )
            ->all();

        return $this->repairs;
    }


    /*
    |--------------------------------------------------------------------------
    | Headings
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        return [
            '#',
            'Repair Reference',
            'Asset Number',
            'Item Type',
            'Employee',
            'Branch',
            'Department',
            'Function Unit',
            'Problem Description',
            'Technician',
            'Carrier',
            'Date Sent',
            'Date Returned',
            'Status',
            'Repair Cost',
            'Turnaround Days',
            'Remarks',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Mapping
    |--------------------------------------------------------------------------
    */

    public function map($repair): array
    {
        $item = $repair->item;

        $employee = $repair->employee;

        $itemType = $item?->itemType;

        $branch = $item?->branch;

        $department = $item?->department;

        $functionUnit = $item?->functionUnit;


        /*
        |--------------------------------------------------------------------------
        | Turnaround Days
        |--------------------------------------------------------------------------
        */

        $turnaroundDays = '';

        if (
            $repair->date_sent &&
            $repair->date_returned
        ) {
            $turnaroundDays = $repair->date_sent->diffInDays(
                $repair->date_returned
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Return Row
        |--------------------------------------------------------------------------
        */

        return [
            $this->repairNumbers[$repair->id] ?? '',

            $repair->repair_reference ?? '—',

            $item?->asset_number ?? '—',

            $itemType?->name
                ?? $itemType?->item_type
                ?? $itemType?->type_name
                ?? '—',

            $employee?->name
                ?? $employee?->full_name
                ?? '—',

            $branch?->name
                ?? $branch?->branch_name
                ?? '—',

            $department?->name
                ?? $department?->department_name
                ?? '—',

            $functionUnit?->name
                ?? $functionUnit?->function_unit_name
                ?? '—',

            $repair->problem_description ?? '—',

            $repair->technician ?? '—',

            $repair->carrier ?? '—',

            $repair->date_sent
                ? $repair->date_sent->format('d M Y')
                : '—',

            $repair->date_returned
                ? $repair->date_returned->format('d M Y')
                : '—',

            $this->formatStatus(
                $repair->status
            ),

            (float) (
                $repair->repair_cost ?? 0
            ),

            $turnaroundDays,

            $repair->remarks ?? '—',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Status Formatter
    |--------------------------------------------------------------------------
    */

    protected function formatStatus(?string $status): string
    {
        if (!$status) {
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
    | Column Widths
    |--------------------------------------------------------------------------
    */

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 20,
            'C' => 18,
            'D' => 18,
            'E' => 24,
            'F' => 20,
            'G' => 22,
            'H' => 22,
            'I' => 42,
            'J' => 22,
            'K' => 18,
            'L' => 15,
            'M' => 15,
            'N' => 20,
            'O' => 16,
            'P' => 17,
            'Q' => 35,
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
                $sheet = $event->sheet->getDelegate();


                /*
                |--------------------------------------------------------------------------
                | Insert Header Rows
                |--------------------------------------------------------------------------
                */

                $sheet->insertNewRowBefore(
                    1,
                    10
                );


                /*
                |--------------------------------------------------------------------------
                | Sheet Title
                |--------------------------------------------------------------------------
                */

                $sheet->setTitle(
                    'Repair Report'
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
                        70
                    );

                    $drawing->setCoordinates(
                        'A1'
                    );

                    $drawing->setOffsetX(
                        8
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

                $headerRows = [
                    1 => 'CEYLON PETROLEUM CORPORATION',
                    2 => 'INFORMATION TECHNOLOGY FUNCTION',
                    3 => 'CPC INVENTORY MANAGEMENT SYSTEM',
                    4 => $this->reportTitle(),
                    5 => 'Generated on: ' . now()->format(
                        'd M Y, h:i A'
                    ),
                ];

                foreach (
                    $headerRows as $row => $value
                ) {
                    $sheet->mergeCells(
                        "B{$row}:Q{$row}"
                    );

                    $sheet->setCellValue(
                        "B{$row}",
                        $value
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Header Row Heights
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(30);

                $sheet
                    ->getRowDimension(2)
                    ->setRowHeight(19);

                $sheet
                    ->getRowDimension(3)
                    ->setRowHeight(19);

                $sheet
                    ->getRowDimension(4)
                    ->setRowHeight(25);

                $sheet
                    ->getRowDimension(5)
                    ->setRowHeight(18);


                /*
                |--------------------------------------------------------------------------
                | Header Alignment
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle('B1:Q5')
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
                    ->getStyle('B1:Q1')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(16);

                $sheet
                    ->getStyle('B2:Q3')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet
                    ->getStyle('B4:Q4')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(13);

                $sheet
                    ->getStyle('B5:Q5')
                    ->getFont()
                    ->setItalic(true)
                    ->setSize(9);


                /*
                |--------------------------------------------------------------------------
                | Header Bottom Border
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle('A5:Q5')
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
                | Report Criteria Heading
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    'A7:Q7'
                );

                $sheet->setCellValue(
                    'A7',
                    'REPORT CRITERIA'
                );

                $sheet
                    ->getStyle('A7:Q7')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet
                    ->getStyle('A7:Q7')
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );

                $sheet
                    ->getStyle('A7:Q7')
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
                | Report Criteria
                |--------------------------------------------------------------------------
                */

                $criteria = $this->buildCriteria();

                $sheet->mergeCells(
                    'A8:Q8'
                );

                $sheet->setCellValue(
                    'A8',
                    $criteria ?: 'All Repair Records'
                );

                $sheet
                    ->getStyle('A8:Q8')
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );

                $sheet
                    ->getStyle('A8:Q8')
                    ->getFont()
                    ->setSize(9);

                $sheet
                    ->getStyle('A8:Q8')
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB(
                        'F8FAFC'
                    );

                $sheet
                    ->getRowDimension(8)
                    ->setRowHeight(32);


                /*
                |--------------------------------------------------------------------------
                | Report Summary Heading
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    'A9:Q9'
                );

                $sheet->setCellValue(
                    'A9',
                    'REPORT SUMMARY'
                );

                $sheet
                    ->getStyle('A9:Q9')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet
                    ->getStyle('A9:Q9')
                    ->getFill()
                    ->setFillType(
                        Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB(
                        'D9EAF7'
                    );

                $sheet
                    ->getStyle('A9:Q9')
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );


                /*
                |--------------------------------------------------------------------------
                | Summary Calculations
                |--------------------------------------------------------------------------
                */

                $totalRepairs = $this->repairs->count();

                $pendingRepairs = $this->repairs
                    ->whereIn(
                        'status',
                        [
                            'pending',
                            'sent_for_repair',
                            'under_repair',
                        ]
                    )
                    ->count();

                $completedRepairs = $this->repairs
                    ->whereIn(
                        'status',
                        [
                            'completed',
                            'returned',
                        ]
                    )
                    ->count();

                $cancelledRepairs = $this->repairs
                    ->where(
                        'status',
                        'cancelled'
                    )
                    ->count();

                $totalCost = $this->repairs->sum(
                    function ($repair) {
                        return (float) (
                            $repair->repair_cost ?? 0
                        );
                    }
                );

                $averageCost = $totalRepairs > 0
                    ? $totalCost / $totalRepairs
                    : 0;

                $completedWithDates = $this->repairs
                    ->filter(
                        function ($repair) {
                            return
                                $repair->date_sent &&
                                $repair->date_returned;
                        }
                    );

                $averageTurnaround =
                    $completedWithDates->count() > 0
                        ? round(
                            $completedWithDates->avg(
                                function ($repair) {
                                    return $repair
                                        ->date_sent
                                        ->diffInDays(
                                            $repair->date_returned
                                        );
                                }
                            ),
                            1
                        )
                        : 0;


                /*
                |--------------------------------------------------------------------------
                | Summary Cards
                |--------------------------------------------------------------------------
                */

        /*
|--------------------------------------------------------------------------
| SUMMARY CARDS
|--------------------------------------------------------------------------
| IMPORTANT:
| The "cell" value must ALWAYS be a valid Excel coordinate
| such as A10, C10, E10, G10.
|
| Never use a status value such as CANCELLED as the cell.
|--------------------------------------------------------------------------
*/

$totalRepairs = $this->repairs->count();

$pendingRepairs = $this->repairs
    ->whereIn('status', [
        'pending',
        'sent_for_repair',
        'under_repair',
    ])
    ->count();

$completedRepairs = $this->repairs
    ->whereIn('status', [
        'completed',
        'returned',
    ])
    ->count();

$cancelledRepairs = $this->repairs
    ->where('status', 'cancelled')
    ->count();

$totalRepairCost = (float) $this->repairs->sum(
    fn ($repair) => (float) ($repair->repair_cost ?? 0)
);

$averageRepairCost = $totalRepairs > 0
    ? $totalRepairCost / $totalRepairs
    : 0;

$summaryCards = [
    [
        'cell' => 'A10',
        'range' => 'A10:B10',
        'label' => 'Total Repairs',
        'value' => $totalRepairs,
    ],
    [
        'cell' => 'C10',
        'range' => 'C10:D10',
        'label' => 'Pending / In Repair',
        'value' => $pendingRepairs,
    ],
    [
        'cell' => 'E10',
        'range' => 'E10:F10',
        'label' => 'Completed / Returned',
        'value' => $completedRepairs,
    ],
    [
        'cell' => 'G10',
        'range' => 'G10:H10',
        'label' => 'Cancelled',
        'value' => $cancelledRepairs,
    ],
    [
        'cell' => 'I10',
        'range' => 'I10:J10',
        'label' => 'Total Repair Cost',
        'value' => number_format($totalRepairCost, 2),
    ],
    [
        'cell' => 'K10',
        'range' => 'K10:L10',
        'label' => 'Average Repair Cost',
        'value' => number_format($averageRepairCost, 2),
    ],
];

foreach ($summaryCards as $card) {
    $sheet->mergeCells($card['range']);

    $sheet->setCellValue(
        $card['cell'],
        $card['label'] . ': ' . $card['value']
    );

    $sheet->getStyle($card['range'])
        ->getFont()
        ->setBold(true)
        ->setSize(10);

    $sheet->getStyle($card['range'])
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        )
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        )
        ->setWrapText(true);

    $sheet->getStyle($card['range'])
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );
}
                /*
                |--------------------------------------------------------------------------
                | Fix Cancelled Card Label
                |--------------------------------------------------------------------------
                */

                $summaryCards[3]['label'] = 'CANCELLED';


                /*
                |--------------------------------------------------------------------------
                | Render Summary Cards
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


                /*
                |--------------------------------------------------------------------------
                | Table Header
                |--------------------------------------------------------------------------
                */

                $headerRange = 'A11:Q11';

                $sheet
                    ->getStyle($headerRange)
                    ->getFont()
                    ->setBold(true)
                    ->setSize(9);

                $sheet
                    ->getStyle($headerRange)
                    ->getFont()
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
                    ->getRowDimension(11)
                    ->setRowHeight(34);


                /*
                |--------------------------------------------------------------------------
                | Data Rows
                |--------------------------------------------------------------------------
                */

                $dataStartRow = 12;

                $repairCount = $this->repairs->count();

                $lastDataRow = $repairCount > 0
                    ? $dataStartRow + $repairCount - 1
                    : 12;


                /*
                |--------------------------------------------------------------------------
                | Data Exists
                |--------------------------------------------------------------------------
                */

                if ($this->repairs->isNotEmpty()) {
                    $dataRange =
                        'A' .
                        $dataStartRow .
                        ':Q' .
                        $lastDataRow;


                    /*
                    |--------------------------------------------------------------------------
                    | Borders
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | Alignment
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle($dataRange)
                        ->getAlignment()
                        ->setVertical(
                            Alignment::VERTICAL_TOP
                        )
                        ->setWrapText(true);


                    /*
                    |--------------------------------------------------------------------------
                    | Row Styling
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $row = $dataStartRow;
                        $row <= $lastDataRow;
                        $row++
                    ) {
                        $sheet
                            ->getRowDimension($row)
                            ->setRowHeight(42);

                        if (
                            ($row - $dataStartRow) % 2 === 1
                        ) {
                            $sheet
                                ->getStyle(
                                    'A' .
                                    $row .
                                    ':Q' .
                                    $row
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
                    | Number Column
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A' .
                            $dataStartRow .
                            ':A' .
                            $lastDataRow
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Date Columns
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'L' .
                            $dataStartRow .
                            ':M' .
                            $lastDataRow
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Status Column
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'N' .
                            $dataStartRow .
                            ':N' .
                            $lastDataRow
                        )
                        ->getFont()
                        ->setBold(true);

                    $sheet
                        ->getStyle(
                            'N' .
                            $dataStartRow .
                            ':N' .
                            $lastDataRow
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Repair Cost
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'O' .
                            $dataStartRow .
                            ':O' .
                            $lastDataRow
                        )
                        ->getNumberFormat()
                        ->setFormatCode(
                            '#,##0.00'
                        );

                    $sheet
                        ->getStyle(
                            'O' .
                            $dataStartRow .
                            ':O' .
                            $lastDataRow
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Turnaround
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'P' .
                            $dataStartRow .
                            ':P' .
                            $lastDataRow
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Status Cell Highlighting
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $this->repairs as $index => $repair
                    ) {
                        $row = $dataStartRow + $index;

                        $statusColor = match (
                            $repair->status
                        ) {
                            'pending' =>
                                'FFF3CD',

                            'sent_for_repair' =>
                                'DDEBF7',

                            'under_repair' =>
                                'E2E3E5',

                            'completed' =>
                                'D1E7DD',

                            'returned' =>
                                'D1ECF1',

                            'cancelled' =>
                                'F8D7DA',

                            default =>
                                'F8FAFC',
                        };

                        $sheet
                            ->getStyle(
                                'N' . $row
                            )
                            ->getFill()
                            ->setFillType(
                                Fill::FILL_SOLID
                            )
                            ->getStartColor()
                            ->setARGB(
                                $statusColor
                            );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Empty Report
                |--------------------------------------------------------------------------
                */

                if ($this->repairs->isEmpty()) {
                    $sheet->mergeCells(
                        'A12:Q12'
                    );

                    $sheet->setCellValue(
                        'A12',
                        'No repair records found for the selected criteria.'
                    );

                    $sheet
                        ->getStyle('A12:Q12')
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        )
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        )
                        ->setWrapText(true);

                    $sheet
                        ->getStyle('A12:Q12')
                        ->getFont()
                        ->setItalic(true)
                        ->setSize(9);

                    $sheet
                        ->getStyle('A12:Q12')
                        ->getFill()
                        ->setFillType(
                            Fill::FILL_SOLID
                        )
                        ->getStartColor()
                        ->setARGB(
                            'F8FAFC'
                        );

                    $sheet
                        ->getStyle('A12:Q12')
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
                        ->getRowDimension(12)
                        ->setRowHeight(30);
                }


                /*
                |--------------------------------------------------------------------------
                | Signature Section
                |--------------------------------------------------------------------------
                */

                $signatureRow = $lastDataRow + 4;

                $signatureRanges = [
                    [
                        'range' =>
                            'A' .
                            $signatureRow .
                            ':D' .
                            $signatureRow,

                        'cell' =>
                            'A' .
                            $signatureRow,

                        'value' =>
                            'PREPARED BY',
                    ],

                    [
                        'range' =>
                            'G' .
                            $signatureRow .
                            ':J' .
                            $signatureRow,

                        'cell' =>
                            'G' .
                            $signatureRow,

                        'value' =>
                            'CHECKED BY',
                    ],

                    [
                        'range' =>
                            'M' .
                            $signatureRow .
                            ':P' .
                            $signatureRow,

                        'cell' =>
                            'M' .
                            $signatureRow,

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
                    'A' .
                    $signatureLineRow .
                    ':D' .
                    $signatureLineRow,

                    'G' .
                    $signatureLineRow .
                    ':J' .
                    $signatureLineRow,

                    'M' .
                    $signatureLineRow .
                    ':P' .
                    $signatureLineRow,
                ];


                foreach (
                    $signatureLineRanges as $range
                ) {
                    $cell = explode(
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
                    'A' .
                    $signatureDateRow .
                    ':D' .
                    $signatureDateRow,

                    'G' .
                    $signatureDateRow .
                    ':J' .
                    $signatureDateRow,

                    'M' .
                    $signatureDateRow .
                    ':P' .
                    $signatureDateRow,
                ];


                foreach (
                    $dateRanges as $range
                ) {
                    $cell = explode(
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
                    'A' .
                    $footerRow .
                    ':Q' .
                    $footerRow
                );

                $sheet->setCellValue(
                    'A' . $footerRow,
                    'CPC Inventory Management System | Official Repair Report'
                );

                $sheet
                    ->getStyle(
                        'A' .
                        $footerRow .
                        ':Q' .
                        $footerRow
                    )
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                $sheet
                    ->getStyle(
                        'A' .
                        $footerRow .
                        ':Q' .
                        $footerRow
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

                $filterLastRow = max(
                    11,
                    $lastDataRow
                );

                $sheet->setAutoFilter(
                    'A11:Q' . $filterLastRow
                );


                /*
                |--------------------------------------------------------------------------
                | Page Setup
                |--------------------------------------------------------------------------
                */

                $pageSetup = $sheet->getPageSetup();

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
                | Repeat Table Header
                |--------------------------------------------------------------------------
                */

                $pageSetup->setRowsToRepeatAtTopByStartAndEnd(
                    11,
                    11
                );


                /*
                |--------------------------------------------------------------------------
                | Print Area
                |--------------------------------------------------------------------------
                */

                $pageSetup->setPrintArea(
                    'A1:Q' . $footerRow
                );


                /*
                |--------------------------------------------------------------------------
                | Page Margins
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
                        '&LRepair Report&RPage &P of &N'
                    );

                $sheet
                    ->getHeaderFooter()
                    ->setOddFooter(
                        '&C CPC Inventory Management System'
                    );


                /*
                |--------------------------------------------------------------------------
                | Gridlines
                |--------------------------------------------------------------------------
                */

                $sheet->setShowGridlines(
                    false
                );


                /*
                |--------------------------------------------------------------------------
                | Document Properties
                |--------------------------------------------------------------------------
                */

                $spreadsheet = $sheet->getParent();

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
                        'CPC Repair Management Report'
                    )
                    ->setDescription(
                        'Official repair report generated by the CPC Inventory Management System.'
                    );
            },
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Report Title
    |--------------------------------------------------------------------------
    */

    protected function reportTitle(): string
    {
        return match (
            $this->filters['report_type']
            ?? 'summary'
        ) {
            'pending' =>
                'REPAIR REPORT — PENDING / UNDER REPAIR',

            'completed' =>
                'REPAIR REPORT — COMPLETED / RETURNED',

            'pending_completed' =>
                'REPAIR REPORT — PENDING & COMPLETED',

            'cost' =>
                'REPAIR REPORT — COST ANALYSIS',

            default =>
                'REPAIR REPORT — SUMMARY',
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
                $this->filters['report_type']
                ?? 'summary'
            );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['status'])) {
            $criteria[] =
                'Status: ' .
                $this->formatStatus(
                    $this->filters['status']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['employee_id'])) {
            $employee = Employee::find(
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

        if (!empty($this->filters['branch_id'])) {
            $branch = Branch::find(
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

        if (!empty($this->filters['department_id'])) {
            $department = Department::find(
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

        if (!empty($this->filters['function_unit_id'])) {
            $functionUnit = FunctionUnit::find(
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

        if (!empty($this->filters['item_type_id'])) {
            $itemType = ItemType::find(
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
        | Technician
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['technician'])) {
            $criteria[] =
                'Technician: ' .
                trim(
                    $this->filters['technician']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['from_date'])) {
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

        if (!empty($this->filters['to_date'])) {
            $criteria[] =
                'To: ' .
                $this->formatDate(
                    $this->filters['to_date']
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
        return match ($value) {
            'summary' =>
                'Repair Summary',

            'pending' =>
                'Pending Repairs',

            'completed' =>
                'Completed Repairs',

            'pending_completed' =>
                'Pending & Completed Repairs',

            'cost' =>
                'Repair Cost Report',

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
        } catch (\Throwable $exception) {
            return $date;
        }
    }
}
