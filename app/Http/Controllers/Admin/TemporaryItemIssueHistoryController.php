<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemporaryItemIssue;
use App\Models\TemporaryItemIssueHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemporaryItemIssueHistoryController extends Controller
{
    /**
     * Display temporary item issue history.
     */
    public function index(Request $request): View
    {
        $query = TemporaryItemIssueHistory::with([
            'temporaryItemIssue',
            'changedBy',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'changed_by_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'changed_by_epf',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'temporaryItemIssue',
                        function ($issueQuery) use ($search) {
                            $issueQuery
                                ->where(
                                    'item_code',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'item_type',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'event',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
            });
        }

        $histories = $query
            ->latest('changed_at')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.temporary-item-issues.history',
            compact('histories')
        );
    }

    /**
     * Display history for one temporary item issue.
     */
    public function show(
        TemporaryItemIssue $temporaryItemIssue
    ): View {
        $histories = $temporaryItemIssue
            ->histories()
            ->with('changedBy')
            ->paginate(20);

        return view(
            'admin.temporary-item-issues.history-show',
            compact(
                'temporaryItemIssue',
                'histories'
            )
        );
    }
}

