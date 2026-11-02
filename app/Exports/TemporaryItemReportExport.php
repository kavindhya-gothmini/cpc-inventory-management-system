<?php

namespace App\Exports;

use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionUnit;
use App\Models\TemporaryItemIssue;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

class TemporaryItemReportExport implements
    FromCollection,
    WithEvents,
    WithColumnWidths
{
    protected array $filters = [];

    protected Collection $temporaryItemIssues;

    protected string $reportType = 'summary';

    protected string $lastColumn = 'J';

    protected bool $collectionLoaded = false;

    /**
     * ============================================================
     * CONSTRUCTOR
     * ============================================================
     */
    public function __construct(Request|array $request = [])
    {
        if ($request instanceof Request) {
            $this->filters = $request->all();

            $this->reportType = $request->get(
                'report_type',
                'summary'
            );
        } else {
            $this->filters = $request;

            $this->reportType =
                $request['report_type'] ?? 'summary';
        }

        $allowedTypes = [
            'summary',
            'history',
            'current_returned',
            'overdue',
        ];

        if (!in_array($this->reportType, $allowedTypes, true)) {
            $this->reportType = 'summary';
        }

        $this->lastColumn =
            $this->reportType === 'history'
                ? 'K'
                : 'J';

        $this->temporaryItemIssues = collect();
    }

    /**
     * ============================================================
     * COLLECTION
     * ============================================================
     */
    public function collection(): Collection
    {
        if ($this->collectionLoaded) {
            return $this->temporaryItemIssues;
        }

        $search = trim(
            (string) ($this->filters['search'] ?? '')
        );

        $itemCode = trim(
            (string) ($this->filters['item_code'] ?? '')
        );

        $itemType =
            $this->filters['item_type'] ?? null;

        $departmentId =
            $this->filters['department_id'] ?? null;

        $functionUnitId =
            $this->filters['function_unit_id'] ?? null;

        $requestingEmployeeId =
            $this->filters['requesting_employee_id'] ?? null;

        $eventName = trim(
            (string) ($this->filters['event'] ?? '')
        );

        $status =
            $this->filters['status'] ?? null;

        $fromDate =
            $this->filters['from_date'] ?? null;

        $toDate =
            $this->filters['to_date'] ?? null;

        $temporaryItemIssueId =
            $this->filters['temporary_item_issue_id'] ?? null;

        $overdueDays = max(
            1,
            min(
                365,
                (int) (
                    $this->filters['overdue_days']
                    ?? 7
                )
            )
        );

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */
        $query = TemporaryItemIssue::query()
            ->with([
                'department',
                'functionUnit',
                'requestingEmployee',
                'collectingEmployee',
                'returningEmployee',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SELECTED ISSUE
        |--------------------------------------------------------------------------
        */
        if (
            $temporaryItemIssueId !== null &&
            $temporaryItemIssueId !== ''
        ) {
            $query->where(
                'id',
                $temporaryItemIssueId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        if ($search !== '') {
            $query->where(function ($q) use ($search) {

                $q->where(
                    'item_code',
                    'like',
                    '%' . $search . '%'
                )

                    ->orWhere(
                        'item_type',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'event',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'remarks',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhereHas(
                        'requestingEmployee',
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
                    )

                    ->orWhereHas(
                        'collectingEmployee',
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
                    )

                    ->orWhereHas(
                        'returningEmployee',
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
        | ITEM CODE
        |--------------------------------------------------------------------------
        */
        if ($itemCode !== '') {
            $query->where(
                'item_code',
                'like',
                '%' . $itemCode . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ITEM TYPE
        |--------------------------------------------------------------------------
        */
        if (
            $itemType !== null &&
            $itemType !== ''
        ) {
            $query->where(
                'item_type',
                $itemType
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT
        |--------------------------------------------------------------------------
        */
        if (
            $departmentId !== null &&
            $departmentId !== ''
        ) {
            $query->where(
                'department_id',
                $departmentId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FUNCTION UNIT
        |--------------------------------------------------------------------------
        */
        if (
            $functionUnitId !== null &&
            $functionUnitId !== ''
        ) {
            $query->where(
                'function_unit_id',
                $functionUnitId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REQUESTING EMPLOYEE
        |--------------------------------------------------------------------------
        */
        if (
            $requestingEmployeeId !== null &&
            $requestingEmployeeId !== ''
        ) {
            $query->where(
                'requesting_employee_id',
                $requestingEmployeeId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | EVENT
        |--------------------------------------------------------------------------
        */
        if ($eventName !== '') {
            $query->where(
                'event',
                'like',
                '%' . $eventName . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
        if (
            $status !== null &&
            $status !== ''
        ) {
            $query->where(
                'status',
                $status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */
        if (
            $fromDate !== null &&
            $fromDate !== ''
        ) {
            $query->whereDate(
                'issue_datetime',
                '>=',
                $fromDate
            );
        }

        if (
            $toDate !== null &&
            $toDate !== ''
        ) {
            $query->whereDate(
                'issue_datetime',
                '<=',
                $toDate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CURRENT & RETURNED
        |--------------------------------------------------------------------------
        */
        if ($this->reportType === 'current_returned') {
            $query->whereIn(
                'status',
                [
                    'issued',
                    'received',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | OVERDUE
        |--------------------------------------------------------------------------
        */
        if ($this->reportType === 'overdue') {
            $query
                ->where(
                    'status',
                    'issued'
                )
                ->where(
                    'issue_datetime',
                    '<=',
                    now()->subDays($overdueDays)
                );
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL RESULT
        |--------------------------------------------------------------------------
        */
        $this->temporaryItemIssues = $query
            ->orderByDesc('issue_datetime')
            ->orderByDesc('id')
            ->get();

        $this->collectionLoaded = true;

        return $this->temporaryItemIssues;
    }

    /**
     * ============================================================
     * COLUMN WIDTHS
     * ============================================================
     */
    public function columnWidths(): array
    {
        if ($this->reportType === 'history') {

            return [
                'A' => 7,
                'B' => 18,
                'C' => 15,
                'D' => 21,
                'E' => 25,
                'F' => 24,
                'G' => 30,
                'H' => 17,
                'I' => 30,
                'J' => 18,
                'K' => 18,
            ];
        }

        return [
            'A' => 7,
            'B' => 18,
            'C' => 15,
            'D' => 21,
            'E' => 25,
            'F' => 24,
            'G' => 30,
            'H' => 30,
            'I' => 18,
            'J' => 17,
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

            AfterSheet::class => function (
                AfterSheet $event
            ) {

                $sheet =
                    $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | LOAD DATA
                |--------------------------------------------------------------------------
                */
                $items =
                    $this->collection();

                /*
                |--------------------------------------------------------------------------
                | REMOVE RAW COLLECTION DATA
                |--------------------------------------------------------------------------
                |
                | FromCollection writes the TemporaryItemIssue model
                | attributes into the worksheet automatically.
                |
                | Those raw rows are not part of the official report.
                | Remove them before constructing the formatted report.
                |
                |--------------------------------------------------------------------------
                */
                $rawHighestRow =
                    $sheet->getHighestRow();

                if ($rawHighestRow > 0) {
                    $sheet->removeRow(
                        1,
                        $rawHighestRow
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | SELECTED ISSUE
                |--------------------------------------------------------------------------
                */
                $selectedIssue = null;

                $temporaryItemIssueId =
                    $this->filters[
                        'temporary_item_issue_id'
                    ] ?? null;

                if (
                    $temporaryItemIssueId !== null &&
                    $temporaryItemIssueId !== ''
                ) {
                    $selectedIssue =
                        TemporaryItemIssue::with([
                            'department',
                            'functionUnit',
                            'requestingEmployee',
                            'collectingEmployee',
                            'returningEmployee',
                        ])->find(
                            $temporaryItemIssueId
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | REPORT TITLE
                |--------------------------------------------------------------------------
                */
                $reportTitle =
                    $this->reportTitle();

                $lastColumn =
                    $this->lastColumn;

                /*
                |--------------------------------------------------------------------------
                | SHEET SETUP
                |--------------------------------------------------------------------------
                */
                $sheet->setTitle(
                    'Temporary Item Report'
                );

                $sheet->setShowGridlines(false);

                $sheet
                    ->getSheetView()
                    ->setZoomScale(90);

                /*
                |--------------------------------------------------------------------------
                | DEFAULT FONT
                |--------------------------------------------------------------------------
                */
                $sheet
                    ->getParent()
                    ->getDefaultStyle()
                    ->getFont()
                    ->setName('Arial')
                    ->setSize(9);

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
                        'Ceylon Petroleum Corporation'
                    );

                    $drawing->setDescription(
                        'CPC Logo'
                    );

                    $drawing->setPath(
                        $logoPath
                    );

                    $drawing->setHeight(68);

                    $drawing->setCoordinates(
                        'A1'
                    );

                    $drawing->setOffsetX(12);

                    $drawing->setOffsetY(4);

                    $drawing->setWorksheet(
                        $sheet
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | CPC HEADER
                |--------------------------------------------------------------------------
                */
                $headerRows = [
                    1 =>
                        'CEYLON PETROLEUM CORPORATION',

                    2 =>
                        'INFORMATION TECHNOLOGY FUNCTION',

                    3 =>
                        'CPC INVENTORY MANAGEMENT SYSTEM',
                ];

                foreach (
                    $headerRows as $row => $value
                ) {

                    $sheet->mergeCells(
                        "B{$row}:{$lastColumn}{$row}"
                    );

                    $sheet->setCellValue(
                        "B{$row}",
                        $value
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | REPORT TITLE
                |--------------------------------------------------------------------------
                */
                $sheet->mergeCells(
                    "B5:{$lastColumn}5"
                );

                $sheet->setCellValue(
                    'B5',
                    $reportTitle
                );

                /*
                |--------------------------------------------------------------------------
                | GENERATED DATE
                |--------------------------------------------------------------------------
                */
                $sheet->mergeCells(
                    "B6:{$lastColumn}6"
                );

                $sheet->setCellValue(
                    'B6',
                    'Generated on: ' .
                    now()->format(
                        'd M Y, H:i'
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | HEADER STYLING
                |--------------------------------------------------------------------------
                */
                $sheet
                    ->getStyle(
                        "B1:{$lastColumn}1"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 16,
                            'color' => [
                                'rgb' => '0D3B66',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_LEFT,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                $sheet
                    ->getStyle(
                        "B2:{$lastColumn}2"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 10,
                            'color' => [
                                'rgb' => '374151',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_LEFT,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                $sheet
                    ->getStyle(
                        "B3:{$lastColumn}3"
                    )
                    ->applyFromArray([

                        'font' => [
                            'size' => 10,
                            'color' => [
                                'rgb' => '4B5563',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_LEFT,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                $sheet
                    ->getStyle(
                        "B5:{$lastColumn}5"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 14,
                            'color' => [
                                'rgb' => '0D3B66',
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
                            'top' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'D9E2F3',
                                ],
                            ],

                            'bottom' => [
                                'borderStyle' =>
                                    Border::BORDER_MEDIUM,

                                'color' => [
                                    'rgb' => '0D3B66',
                                ],
                            ],
                        ],
                    ]);

                $sheet
                    ->getStyle(
                        "B6:{$lastColumn}6"
                    )
                    ->applyFromArray([

                        'font' => [
                            'italic' => true,
                            'size' => 9,
                            'color' => [
                                'rgb' => '6B7280',
                            ],
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
                | REPORT CRITERIA TITLE
                |--------------------------------------------------------------------------
                */
                $sheet->mergeCells(
                    "A7:{$lastColumn}7"
                );

                $sheet->setCellValue(
                    'A7',
                    'REPORT CRITERIA'
                );

                $sheet
                    ->getStyle(
                        "A7:{$lastColumn}7"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 9,
                            'color' => [
                                'rgb' => '0D3B66',
                            ],
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'color' => [
                                'rgb' => 'EAF2F8',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_LEFT,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],

                        'borders' => [
                            'top' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'D9E2F3',
                                ],
                            ],

                            'bottom' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'D9E2F3',
                                ],
                            ],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | CRITERIA
                |--------------------------------------------------------------------------
                */
                $criteria =
                    $this->buildCriteria(
                        $selectedIssue
                    );

                $criteriaText =
                    count($criteria) > 0
                        ? implode(
                            '   |   ',
                            $criteria
                        )
                        : 'No additional filtering criteria applied.';

                $sheet->mergeCells(
                    "A8:{$lastColumn}8"
                );

                $sheet->setCellValue(
                    'A8',
                    $criteriaText
                );

                $sheet
                    ->getStyle(
                        "A8:{$lastColumn}8"
                    )
                    ->applyFromArray([

                        'font' => [
                            'size' => 8.5,
                            'color' => [
                                'rgb' => '374151',
                            ],
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'color' => [
                                'rgb' => 'F8FAFC',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_LEFT,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,

                            'wrapText' => true,
                        ],

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'D9E2F3',
                                ],
                            ],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | REPORT SUMMARY TITLE
                |--------------------------------------------------------------------------
                */
                $sheet->mergeCells(
                    "A9:{$lastColumn}9"
                );

                $sheet->setCellValue(
                    'A9',
                    'REPORT SUMMARY'
                );

                $sheet
                    ->getStyle(
                        "A9:{$lastColumn}9"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 9,
                            'color' => [
                                'rgb' => '0D3B66',
                            ],
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'color' => [
                                'rgb' => 'EAF2F8',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_LEFT,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],

                        'borders' => [
                            'top' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'D9E2F3',
                                ],
                            ],

                            'bottom' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'D9E2F3',
                                ],
                            ],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | SUMMARY CALCULATIONS
                |--------------------------------------------------------------------------
                */
                $totalIssues =
                    $items->count();

                $currentlyIssued =
                    $items
                        ->where(
                            'status',
                            'issued'
                        )
                        ->count();

                $returnedItems =
                    $items
                        ->where(
                            'status',
                            'received'
                        )
                        ->count();

                $cancelledItems =
                    $items
                        ->where(
                            'status',
                            'cancelled'
                        )
                        ->count();

                $overdueItems =
                    $this->countOverdue(
                        $items
                    );

                $uniqueEvents =
                    $items
                        ->whereNotNull('event')
                        ->pluck('event')
                        ->filter(
                            fn ($value) =>
                                trim(
                                    (string) $value
                                ) !== ''
                        )
                        ->unique()
                        ->count();

                /*
                |--------------------------------------------------------------------------
                | SUMMARY CARDS
                |--------------------------------------------------------------------------
                */
                $summaryCards = [

                    [
                        'range' => 'A10:B10',
                        'label' => 'TOTAL ISSUES',
                        'value' => $totalIssues,
                    ],

                    [
                        'range' => 'C10:D10',
                        'label' => 'CURRENTLY ISSUED',
                        'value' => $currentlyIssued,
                    ],

                    [
                        'range' => 'E10:F10',
                        'label' => 'RETURNED',
                        'value' => $returnedItems,
                    ],

                    [
                        'range' => 'G10:H10',
                        'label' => 'CANCELLED',
                        'value' => $cancelledItems,
                    ],

                    [
                        'range' => 'I10:J10',
                        'label' => 'OVERDUE',
                        'value' => $overdueItems,
                    ],
                ];

                if ($lastColumn === 'K') {

                    $summaryCards[] = [
                        'range' => 'K10:K10',
                        'label' => 'EVENTS',
                        'value' => $uniqueEvents,
                    ];
                }

                foreach (
                    $summaryCards as $card
                ) {

                    $range =
                        $card['range'];

                    $startCell =
                        explode(
                            ':',
                            $range
                        )[0];

                    if ($range !== $startCell) {

                        $sheet->mergeCells(
                            $range
                        );
                    }

                    $sheet->setCellValue(
                        $startCell,
                        $card['label'] .
                        "\n" .
                        $card['value']
                    );

                    $sheet
                        ->getStyle($range)
                        ->applyFromArray([

                            'font' => [
                                'bold' => true,
                                'size' => 9,
                                'color' => [
                                    'rgb' => '0D3B66',
                                ],
                            ],

                            'fill' => [
                                'fillType' =>
                                    Fill::FILL_SOLID,

                                'color' => [
                                    'rgb' => 'F4F7FA',
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
                                        'rgb' => 'D9E2F3',
                                    ],
                                ],
                            ],
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | TABLE HEADINGS
                |--------------------------------------------------------------------------
                */
                $headingRow = 11;

                $headings =
                    $this->headingsForReport();

                foreach (
                    $headings as $column => $heading
                ) {

                    $sheet->setCellValue(
                        "{$column}{$headingRow}",
                        $heading
                    );
                }

                $sheet
                    ->getStyle(
                        "A{$headingRow}:{$lastColumn}{$headingRow}"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 9,
                            'color' => [
                                'rgb' => 'FFFFFF',
                            ],
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'color' => [
                                'rgb' => '0D3B66',
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
                                    'rgb' => 'FFFFFF',
                                ],
                            ],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | DATA ROWS
                |--------------------------------------------------------------------------
                */
                $dataStartRow = 12;

                $lastDataRow =
                    $dataStartRow +
                    $items->count() -
                    1;

                if ($items->count() > 0) {

                    $rowNumber =
                        $dataStartRow;

                    foreach (
                        $items as $issue
                    ) {

                        $record =
                            $this->mapRecord(
                                $issue,
                                $rowNumber -
                                $dataStartRow +
                                1
                            );

                        foreach (
                            $record as $column => $value
                        ) {

                            $sheet->setCellValue(
                                "{$column}{$rowNumber}",
                                $value
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | ALTERNATING ROW FILL
                        |--------------------------------------------------------------------------
                        */
                        if (
                            (
                                $rowNumber -
                                $dataStartRow
                            ) % 2 === 1
                        ) {

                            $sheet
                                ->getStyle(
                                    "A{$rowNumber}:{$lastColumn}{$rowNumber}"
                                )
                                ->getFill()
                                ->setFillType(
                                    Fill::FILL_SOLID
                                );

                            $sheet
                                ->getStyle(
                                    "A{$rowNumber}:{$lastColumn}{$rowNumber}"
                                )
                                ->getFill()
                                ->getStartColor()
                                ->setRGB(
                                    'F8FAFC'
                                );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | BASE DATA STYLE
                        |--------------------------------------------------------------------------
                        |
                        | InventoryReportExport alignment standard:
                        | all data starts LEFT aligned.
                        |
                        |--------------------------------------------------------------------------
                        */
                        $sheet
                            ->getStyle(
                                "A{$rowNumber}:{$lastColumn}{$rowNumber}"
                            )
                            ->applyFromArray([

                                'font' => [
                                    'size' => 9,
                                ],

                                'alignment' => [
                                    'horizontal' =>
                                        Alignment::HORIZONTAL_LEFT,

                                    'vertical' =>
                                        Alignment::VERTICAL_CENTER,

                                    'wrapText' => true,
                                ],

                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' =>
                                            Border::BORDER_THIN,

                                        'color' => [
                                            'rgb' => 'D9E2F3',
                                        ],
                                    ],
                                ],
                            ]);

                        /*
                        |--------------------------------------------------------------------------
                        | CENTER COLUMNS
                        |--------------------------------------------------------------------------
                        |
                        | Only # and Status are centered.
                        |
                        |--------------------------------------------------------------------------
                        */
                        $centerColumns =
                            $this->centerColumns();

                        foreach (
                            $centerColumns as $column
                        ) {

                            $sheet
                                ->getStyle(
                                    "{$column}{$rowNumber}"
                                )
                                ->getAlignment()
                                ->setHorizontal(
                                    Alignment::HORIZONTAL_CENTER
                                );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | STATUS CELL
                        |--------------------------------------------------------------------------
                        */
                        $statusColumn =
                            $this->reportType === 'history'
                                ? 'H'
                                : 'J';

                        $statusRawValue =
                            trim(
                                (string)
                                $sheet
                                    ->getCell(
                                        "{$statusColumn}{$rowNumber}"
                                    )
                                    ->getValue()
                            );

                        $statusValue =
                            strtolower(
                                str_replace(
                                    ' ',
                                    '_',
                                    $statusRawValue
                                )
                            );

                        $sheet
                            ->getStyle(
                                "{$statusColumn}{$rowNumber}"
                            )
                            ->applyFromArray([

                                'font' => [
                                    'bold' => true,
                                    'size' => 9,
                                ],

                                'fill' => [
                                    'fillType' =>
                                        Fill::FILL_SOLID,

                                    'color' => [
                                        'rgb' =>
                                            $this->statusColor(
                                                $statusValue
                                            ),
                                    ],
                                ],

                                'alignment' => [
                                    'horizontal' =>
                                        Alignment::HORIZONTAL_CENTER,

                                    'vertical' =>
                                        Alignment::VERTICAL_CENTER,

                                    'wrapText' => true,
                                ],
                            ]);

                        /*
                        |--------------------------------------------------------------------------
                        | ROW HEIGHT
                        |--------------------------------------------------------------------------
                        */
                        $sheet
                            ->getRowDimension(
                                $rowNumber
                            )
                            ->setRowHeight(
                                $this->reportType === 'history'
                                    ? 40
                                    : 38
                            );

                        $rowNumber++;
                    }

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | EMPTY STATE
                    |--------------------------------------------------------------------------
                    */
                    $lastDataRow =
                        $dataStartRow;

                    $sheet->mergeCells(
                        "A{$dataStartRow}:{$lastColumn}{$dataStartRow}"
                    );

                    $emptyMessage =
                        match ($this->reportType) {

                            'current_returned' =>
                                'No current or returned temporary item records found.',

                            'overdue' =>
                                'No overdue temporary item records found.',

                            'history' =>
                                'No temporary item issue history records found.',

                            default =>
                                'No temporary item issue records found for the selected criteria.',
                        };

                    $sheet->setCellValue(
                        "A{$dataStartRow}",
                        $emptyMessage
                    );

                    $sheet
                        ->getStyle(
                            "A{$dataStartRow}:{$lastColumn}{$dataStartRow}"
                        )
                        ->applyFromArray([

                            'font' => [
                                'italic' => true,
                                'size' => 9,
                                'color' => [
                                    'rgb' => '6B7280',
                                ],
                            ],

                            'fill' => [
                                'fillType' =>
                                    Fill::FILL_SOLID,

                                'color' => [
                                    'rgb' => 'F8FAFC',
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
                                        'rgb' => 'D9E2F3',
                                    ],
                                ],
                            ],
                        ]);

                    $sheet
                        ->getRowDimension(
                            $dataStartRow
                        )
                        ->setRowHeight(32);
                }

                /*
                |--------------------------------------------------------------------------
                | SELECTED ISSUE OUTLINE
                |--------------------------------------------------------------------------
                */
                if (
                    $selectedIssue &&
                    $items->count() > 0
                ) {

                    $sheet
                        ->getStyle(
                            "A{$dataStartRow}:{$lastColumn}{$lastDataRow}"
                        )
                        ->applyFromArray([

                            'borders' => [
                                'outline' => [
                                    'borderStyle' =>
                                        Border::BORDER_MEDIUM,

                                    'color' => [
                                        'rgb' => '0D6EFD',
                                    ],
                                ],
                            ],
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | DOCUMENT CERTIFICATION
                |--------------------------------------------------------------------------
                */
                $signatureStart =
                    max(
                        $lastDataRow + 4,
                        17
                    );

                $sheet->mergeCells(
                    "A{$signatureStart}:{$lastColumn}{$signatureStart}"
                );

                $sheet->setCellValue(
                    "A{$signatureStart}",
                    'DOCUMENT CERTIFICATION & AUTHORIZATION'
                );

                $sheet
                    ->getStyle(
                        "A{$signatureStart}:{$lastColumn}{$signatureStart}"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 10,
                            'color' => [
                                'rgb' => '0D3B66',
                            ],
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'color' => [
                                'rgb' => 'EAF2F8',
                            ],
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
                                    'rgb' => 'D9E2F3',
                                ],
                            ],
                        ],
                    ]);

                $signatureRow =
                    $signatureStart + 2;

                /*
                |--------------------------------------------------------------------------
                | SIGNATURE BLOCKS
                |--------------------------------------------------------------------------
                */
                $authorizedEndColumn =
                    $lastColumn === 'K'
                        ? 'K'
                        : 'J';

                $signatureBlocks = [

                    [
                        'range' =>
                            "A{$signatureRow}:C{$signatureRow}",

                        'details' => [
                            'A' =>
                                'Prepared By',

                            'A+' =>
                                'Signature: __________________',

                            'A++' =>
                                'Name / Designation: __________',

                            'A+++' =>
                                'Date: ________________________',
                        ],
                    ],

                    [
                        'range' =>
                            "D{$signatureRow}:G{$signatureRow}",

                        'details' => [
                            'D' =>
                                'Checked By',

                            'D+' =>
                                'Signature: __________________',

                            'D++' =>
                                'Name / Designation: __________',

                            'D+++' =>
                                'Date: ________________________',
                        ],
                    ],

                    [
                        'range' =>
                            "H{$signatureRow}:{$authorizedEndColumn}{$signatureRow}",

                        'details' => [
                            'H' =>
                                'Authorized By',

                            'H+' =>
                                'Signature: __________________',

                            'H++' =>
                                'Name / Designation: __________',

                            'H+++' =>
                                'Date: ________________________',
                        ],
                    ],
                ];

                foreach (
                    $signatureBlocks as $block
                ) {

                    $range =
                        $block['range'];

                    $rangeParts =
                        explode(
                            ':',
                            $range
                        );

                    $startColumn =
                        preg_replace(
                            '/\d+/',
                            '',
                            $rangeParts[0]
                        );

                    $endColumn =
                        preg_replace(
                            '/\d+/',
                            '',
                            $rangeParts[1]
                        );

                    $sheet->mergeCells(
                        $range
                    );

                    $sheet->setCellValue(
                        "{$startColumn}{$signatureRow}",
                        $block['details'][$startColumn]
                    );

                    for (
                        $offset = 2;
                        $offset <= 4;
                        $offset++
                    ) {

                        $currentRow =
                            $signatureRow +
                            $offset;

                        $suffix =
                            match ($offset) {
                                2 => '+',
                                3 => '++',
                                4 => '+++',
                            };

                        $detailKey =
                            $startColumn .
                            $suffix;

                        $detailRange =
                            "{$startColumn}{$currentRow}:" .
                            $endColumn .
                            "{$currentRow}";

                        $sheet->mergeCells(
                            $detailRange
                        );

                        $sheet->setCellValue(
                            "{$startColumn}{$currentRow}",
                            $block['details'][$detailKey]
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | SIGNATURE STYLING
                |--------------------------------------------------------------------------
                */
                $sheet
                    ->getStyle(
                        "A{$signatureRow}:{$lastColumn}" .
                        ($signatureRow + 4)
                    )
                    ->applyFromArray([

                        'font' => [
                            'size' => 9,
                            'color' => [
                                'rgb' => '374151',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_LEFT,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,

                            'wrapText' => true,
                        ],

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'E5E7EB',
                                ],
                            ],
                        ],
                    ]);

                $sheet
                    ->getStyle(
                        "A{$signatureRow}:{$lastColumn}{$signatureRow}"
                    )
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 10,
                            'color' => [
                                'rgb' => '0D3B66',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_CENTER,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'color' => [
                                'rgb' => 'F8FAFC',
                            ],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | FOOTER
                |--------------------------------------------------------------------------
                */
                $footerRow =
                    $signatureRow + 7;

                $sheet->mergeCells(
                    "A{$footerRow}:{$lastColumn}{$footerRow}"
                );

                $sheet->setCellValue(
                    "A{$footerRow}",
                    '© ' .
                    now()->format('Y') .
                    ' Ceylon Petroleum Corporation | ' .
                    'CPC Inventory Management System'
                );

                $sheet
                    ->getStyle(
                        "A{$footerRow}:{$lastColumn}{$footerRow}"
                    )
                    ->applyFromArray([

                        'font' => [
                            'italic' => true,
                            'size' => 8,
                            'color' => [
                                'rgb' => '6B7280',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_CENTER,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],

                        'borders' => [
                            'top' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [
                                    'rgb' => 'D9E2F3',
                                ],
                            ],
                        ],
                    ]);

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
                    ->setRowHeight(18);

                $sheet
                    ->getRowDimension(3)
                    ->setRowHeight(18);

                $sheet
                    ->getRowDimension(4)
                    ->setRowHeight(8);

                $sheet
                    ->getRowDimension(5)
                    ->setRowHeight(28);

                $sheet
                    ->getRowDimension(6)
                    ->setRowHeight(18);

                $sheet
                    ->getRowDimension(7)
                    ->setRowHeight(20);

                $sheet
                    ->getRowDimension(8)
                    ->setRowHeight(
                        $selectedIssue
                            ? 48
                            : 38
                    );

                $sheet
                    ->getRowDimension(9)
                    ->setRowHeight(20);

                $sheet
                    ->getRowDimension(10)
                    ->setRowHeight(42);

                $sheet
                    ->getRowDimension(11)
                    ->setRowHeight(38);

                $sheet
                    ->getRowDimension($signatureStart)
                    ->setRowHeight(22);

                $sheet
                    ->getRowDimension($signatureRow)
                    ->setRowHeight(23);

                $sheet
                    ->getRowDimension($footerRow)
                    ->setRowHeight(20);

                /*
                |--------------------------------------------------------------------------
                | FREEZE TABLE HEADER
                |--------------------------------------------------------------------------
                */
                $sheet->freezePane(
                    'A12'
                );

                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */
                if ($items->count() > 0) {

                    $sheet->setAutoFilter(
                        "A11:{$lastColumn}{$lastDataRow}"
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | PRINT SETTINGS
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

                $pageSetup->setFitToWidth(1);

                $pageSetup->setFitToHeight(0);

                $pageSetup->setHorizontalCentered(true);

                /*
                |--------------------------------------------------------------------------
                | REPEAT TABLE HEADER
                |--------------------------------------------------------------------------
                */
                $pageSetup
                    ->setRowsToRepeatAtTopByStartAndEnd(
                        11,
                        11
                    );

                /*
                |--------------------------------------------------------------------------
                | PRINT MARGINS
                |--------------------------------------------------------------------------
                */
                $pageMargins =
                    $sheet->getPageMargins();

                $pageMargins->setTop(0.35);

                $pageMargins->setRight(0.25);

                $pageMargins->setBottom(0.45);

                $pageMargins->setLeft(0.25);

                /*
                |--------------------------------------------------------------------------
                | PRINT AREA
                |--------------------------------------------------------------------------
                */
                $pageSetup->setPrintArea(
                    "A1:{$lastColumn}{$footerRow}"
                );

                /*
                |--------------------------------------------------------------------------
                | HEADER / FOOTER
                |--------------------------------------------------------------------------
                */
                $headerTitle =
                    str_replace(
                        '&',
                        '&&',
                        $reportTitle
                    );

                $headerFooter =
                    $sheet->getHeaderFooter();

                $headerFooter->setOddHeader(
                    '&L&B' .
                    $headerTitle .
                    '&B&RPage &P of &N'
                );

                $headerFooter->setOddFooter(
                    '&CCPC Inventory Management System'
                );

                /*
                |--------------------------------------------------------------------------
                | DOCUMENT PROPERTIES
                |--------------------------------------------------------------------------
                */
                $sheet
                    ->getParent()
                    ->getProperties()
                    ->setCreator(
                        'CPC Inventory Management System'
                    )
                    ->setLastModifiedBy(
                        'CPC Inventory Management System'
                    )
                    ->setTitle(
                        $reportTitle
                    )
                    ->setSubject(
                        'CPC Temporary Item Management Report'
                    )
                    ->setDescription(
                        'Official temporary item issue report generated by the CPC Inventory Management System.'
                    );

                /*
                |--------------------------------------------------------------------------
                | TAB COLOR
                |--------------------------------------------------------------------------
                */
                $sheet
                    ->getTabColor()
                    ->setRGB(
                        '0D3B66'
                    );

                /*
                |--------------------------------------------------------------------------
                | ACTIVE CELL
                |--------------------------------------------------------------------------
                */
                $sheet->setSelectedCell(
                    'A1'
                );
            },
        ];
    }

    /**
     * ============================================================
     * REPORT HEADINGS
     * ============================================================
     */
    protected function headingsForReport(): array
    {
        if ($this->reportType === 'history') {

            return [
                'A' => '#',
                'B' => 'Issue Date',
                'C' => 'Item Code',
                'D' => 'Item Type',
                'E' => 'Event',
                'F' => 'Department',
                'G' => 'Requesting Employee',
                'H' => 'Status',
                'I' => 'Collecting Employee',
                'J' => 'Return Date',
                'K' => 'Record State',
            ];
        }

        return [
            'A' => '#',
            'B' => 'Issue Date',
            'C' => 'Item Code',
            'D' => 'Item Type',
            'E' => 'Event',
            'F' => 'Department',
            'G' => 'Requesting Employee',
            'H' => 'Collecting Employee',
            'I' => 'Return Date',
            'J' => 'Status',
        ];
    }

    /**
     * ============================================================
     * MAP RECORD
     * ============================================================
     */
    protected function mapRecord(
        TemporaryItemIssue $issue,
        int $rowNumber
    ): array {

        $issueDate =
            $this->formatDate(
                $issue->issue_datetime
            );

        $returnDate =
            $this->formatDate(
                $issue->received_datetime
            );

        $department =
            optional(
                $issue->department
            )->name ?: '—';

        $requestingEmployee =
            optional(
                $issue->requestingEmployee
            )->name ?: '—';

        $collectingEmployee =
            optional(
                $issue->collectingEmployee
            )->name ?: '—';

        $status =
            $this->formatStatus(
                $issue->status
            );

        if ($this->reportType === 'history') {

            return [

                'A' =>
                    $rowNumber,

                'B' =>
                    $issueDate,

                'C' =>
                    $issue->item_code
                    ?: '—',

                'D' =>
                    $issue->item_type
                    ?: '—',

                'E' =>
                    $issue->event
                    ?: '—',

                'F' =>
                    $department,

                'G' =>
                    $requestingEmployee,

                'H' =>
                    $status,

                'I' =>
                    $collectingEmployee,

                'J' =>
                    $returnDate,

                'K' =>
                    $this->recordState(
                        $issue
                    ),
            ];
        }

        return [

            'A' =>
                $rowNumber,

            'B' =>
                $issueDate,

            'C' =>
                $issue->item_code
                ?: '—',

            'D' =>
                $issue->item_type
                ?: '—',

            'E' =>
                $issue->event
                ?: '—',

            'F' =>
                $department,

            'G' =>
                $requestingEmployee,

            'H' =>
                $collectingEmployee,

            'I' =>
                $returnDate,

            'J' =>
                $status,
        ];
    }

    /**
     * ============================================================
     * RECORD STATE
     * ============================================================
     */
    protected function recordState(
        TemporaryItemIssue $issue
    ): string {

        return match (
            strtolower(
                trim(
                    (string) $issue->status
                )
            )
        ) {

            'issued' =>
                'Active',

            'received' =>
                'Returned',

            'cancelled' =>
                'Cancelled',

            default =>
                'Recorded',
        };
    }

    /**
     * ============================================================
     * STATUS COLOR
     * ============================================================
     */
    protected function statusColor(
        string $status
    ): string {

        return match (
            strtolower(
                trim(
                    $status
                )
            )
        ) {

            'issued',
            'currently_issued' =>
                'FFF3CD',

            'received',
            'returned' =>
                'D1E7DD',

            'cancelled' =>
                'F8D7DA',

            default =>
                'F8FAFC',
        };
    }

    /**
     * ============================================================
     * CENTER COLUMNS
     * ============================================================
     *
     * Same alignment principle as InventoryReportExport:
     *
     * #       = Center
     * Status  = Center
     *
     * All other data columns remain LEFT aligned.
     *
     */
    protected function centerColumns(): array
    {
        if ($this->reportType === 'history') {

            return [
                'A',
                'H',
            ];
        }

        return [
            'A',
            'J',
        ];
    }

    /**
     * ============================================================
     * BUILD CRITERIA
     * ============================================================
     */
    protected function buildCriteria(
        ?TemporaryItemIssue $selectedIssue = null
    ): array {

        $criteria = [];

        /*
        |--------------------------------------------------------------------------
        | REPORT TYPE
        |--------------------------------------------------------------------------
        */
        $criteria[] =
            'Report Type: ' .
            $this->formatReportType(
                $this->reportType
            );

        /*
        |--------------------------------------------------------------------------
        | SELECTED ISSUE
        |--------------------------------------------------------------------------
        */
        if ($selectedIssue) {

            $selectedIssueLabel =
                $selectedIssue->item_code
                ?: 'No Item Code';

            if (
                !empty(
                    $selectedIssue->item_type
                )
            ) {

                $selectedIssueLabel .=
                    ' — ' .
                    $selectedIssue->item_type;
            }

            if (
                !empty(
                    $selectedIssue->event
                )
            ) {

                $selectedIssueLabel .=
                    ' — ' .
                    $selectedIssue->event;
            }

            $criteria[] =
                'Temporary Item Issue: ' .
                $selectedIssueLabel;
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        $search =
            trim(
                (string) (
                    $this->filters['search']
                    ?? ''
                )
            );

        if ($search !== '') {

            $criteria[] =
                'Search: ' .
                $search;
        }

        /*
        |--------------------------------------------------------------------------
        | ITEM CODE
        |--------------------------------------------------------------------------
        */
        $itemCode =
            trim(
                (string) (
                    $this->filters['item_code']
                    ?? ''
                )
            );

        if ($itemCode !== '') {

            $criteria[] =
                'Item Code: ' .
                $itemCode;
        }

        /*
        |--------------------------------------------------------------------------
        | ITEM TYPE
        |--------------------------------------------------------------------------
        */
        $itemType =
            $this->filters['item_type']
            ?? null;

        if (
            $itemType !== null &&
            $itemType !== ''
        ) {

            $criteria[] =
                'Item Type: ' .
                $itemType;
        }

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT
        |--------------------------------------------------------------------------
        */
        $departmentId =
            $this->filters['department_id']
            ?? null;

        if (
            $departmentId !== null &&
            $departmentId !== ''
        ) {

            $department =
                Department::find(
                    $departmentId
                );

            if ($department) {

                $criteria[] =
                    'Department: ' .
                    $department->name;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FUNCTION UNIT
        |--------------------------------------------------------------------------
        */
        $functionUnitId =
            $this->filters['function_unit_id']
            ?? null;

        if (
            $functionUnitId !== null &&
            $functionUnitId !== ''
        ) {

            $functionUnit =
                FunctionUnit::find(
                    $functionUnitId
                );

            if ($functionUnit) {

                $criteria[] =
                    'Function Unit: ' .
                    $functionUnit->name;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | REQUESTING EMPLOYEE
        |--------------------------------------------------------------------------
        */
        $requestingEmployeeId =
            $this->filters[
                'requesting_employee_id'
            ] ?? null;

        if (
            $requestingEmployeeId !== null &&
            $requestingEmployeeId !== ''
        ) {

            $employee =
                Employee::find(
                    $requestingEmployeeId
                );

            if ($employee) {

                $criteria[] =
                    'Requesting Employee: ' .
                    $employee->name .
                    ' (' .
                    $employee->epf_no .
                    ')';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EVENT
        |--------------------------------------------------------------------------
        */
        $event =
            trim(
                (string) (
                    $this->filters['event']
                    ?? ''
                )
            );

        if ($event !== '') {

            $criteria[] =
                'Event: ' .
                $event;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
        $status =
            $this->filters['status']
            ?? null;

        if (
            $status !== null &&
            $status !== ''
        ) {

            $criteria[] =
                'Status: ' .
                $this->formatStatus(
                    $status
                );
        }

        /*
        |--------------------------------------------------------------------------
        | FROM DATE
        |--------------------------------------------------------------------------
        */
        $fromDate =
            $this->filters['from_date']
            ?? null;

        if (
            $fromDate !== null &&
            $fromDate !== ''
        ) {

            $criteria[] =
                'From: ' .
                $this->formatDate(
                    $fromDate,
                    'd M Y'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TO DATE
        |--------------------------------------------------------------------------
        */
        $toDate =
            $this->filters['to_date']
            ?? null;

        if (
            $toDate !== null &&
            $toDate !== ''
        ) {

            $criteria[] =
                'To: ' .
                $this->formatDate(
                    $toDate,
                    'd M Y'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | OVERDUE
        |--------------------------------------------------------------------------
        */
        if (
            $this->reportType ===
            'overdue'
        ) {

            $overdueDays = max(
                1,
                min(
                    365,
                    (int) (
                        $this->filters[
                            'overdue_days'
                        ] ?? 7
                    )
                )
            );

            $criteria[] =
                'Overdue After: ' .
                $overdueDays .
                ' days';
        }

        return $criteria;
    }

    /**
     * ============================================================
     * COUNT OVERDUE
     * ============================================================
     */
    protected function countOverdue(
        Collection $items
    ): int {

        $overdueDays = max(
            1,
            min(
                365,
                (int) (
                    $this->filters[
                        'overdue_days'
                    ] ?? 7
                )
            )
        );

        $overdueLimit =
            now()->subDays(
                $overdueDays
            );

        return $items
            ->filter(
                function ($issue) use (
                    $overdueLimit
                ) {

                    return
                        strtolower(
                            trim(
                                (string)
                                $issue->status
                            )
                        ) === 'issued'
                        &&
                        $issue->issue_datetime
                        &&
                        Carbon::parse(
                            $issue->issue_datetime
                        )->lessThanOrEqualTo(
                            $overdueLimit
                        );
                }
            )
            ->count();
    }

    /**
     * ============================================================
     * REPORT TITLE
     * ============================================================
     */
    protected function reportTitle(): string
    {
        return match (
            $this->reportType
        ) {

            'history' =>
                'TEMPORARY ITEM ISSUE REPORT — HISTORY',

            'current_returned' =>
                'CURRENT & RETURNED TEMPORARY ITEMS REPORT',

            'overdue' =>
                'OVERDUE TEMPORARY ITEM REPORT',

            default =>
                'TEMPORARY ITEM ISSUE REPORT — SUMMARY',
        };
    }

    /**
     * ============================================================
     * FORMAT REPORT TYPE
     * ============================================================
     */
    protected function formatReportType(
        string $type
    ): string {

        return match ($type) {

            'history' =>
                'History',

            'current_returned' =>
                'Current & Returned',

            'overdue' =>
                'Overdue',

            default =>
                'Summary',
        };
    }

    /**
     * ============================================================
     * FORMAT STATUS
     * ============================================================
     */
    protected function formatStatus(
        ?string $status
    ): string {

        if (
            $status === null ||
            trim($status) === ''
        ) {
            return '—';
        }

        return ucwords(
            str_replace(
                '_',
                ' ',
                trim($status)
            )
        );
    }

    /**
     * ============================================================
     * FORMAT DATE
     * ============================================================
     */
    protected function formatDate(
        mixed $date,
        string $format = 'd M Y H:i'
    ): string {

        if (
            $date === null ||
            $date === ''
        ) {
            return '—';
        }

        try {

            return Carbon::parse(
                $date
            )->format(
                $format
            );

        } catch (\Throwable $e) {

            return '—';
        }
    }
}