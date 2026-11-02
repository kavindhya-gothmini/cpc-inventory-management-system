@extends('layouts.admin')

@section('title', 'User Accounts | CPC Inventory Management System')

@section('content')

@php
$currentUser = auth()->user();


$totalUsers = $users->total();
$activeUsers = $users->getCollection()->where('is_locked', false)->count();
$lockedUsers = $users->getCollection()->where('is_locked', true)->count();

/*
|--------------------------------------------------------------------------
| Note
|--------------------------------------------------------------------------
| The summary counts above use the currently loaded pagination collection.
| If you want GLOBAL active/locked counts, calculate them in the controller
| and pass them to the view.
|--------------------------------------------------------------------------
*/


@endphp

<style>
    /* =========================================================
       CPC USER ACCOUNT MANAGEMENT
       Professional / Responsive UI
    ========================================================= */

    .users-page {
        min-height: calc(100vh - 72px);
        padding: 24px;
        background: #f5f7fb;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .users-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .users-title {
        min-width: 0;
    }

    .users-title h2 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1f2937;
        font-size: 27px;
        font-weight: 750;
        letter-spacing: -.025em;
    }

    .users-title h2 i {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: linear-gradient(
            135deg,
            #0d6efd,
            #0b5ed7
        );
        color: #fff;
        font-size: 19px;
        box-shadow: 0 5px 14px rgba(13, 110, 253, .20);
    }

    .users-title p {
        margin: 8px 0 0 52px;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.5;
    }

    .btn-create-user {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 43px;
        padding: 10px 17px;
        border: 1px solid #0d6efd;
        border-radius: 9px;
        background: #0d6efd;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 5px 14px rgba(13, 110, 253, .16);
        transition: all .2s ease;
    }

    .btn-create-user:hover {
        background: #0b5ed7;
        border-color: #0b5ed7;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 7px 17px rgba(13, 110, 253, .22);
    }

    /* =========================================================
       SUMMARY CARDS
    ========================================================= */

    .account-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .summary-card {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        padding: 16px 17px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        width: 70px;
        height: 70px;
        right: -25px;
        bottom: -30px;
        border-radius: 50%;
        background: rgba(13, 110, 253, .045);
    }

    .summary-icon {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 17px;
    }

    .summary-icon.total {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .summary-icon.active {
        background: #e9f7ef;
        color: #198754;
    }

    .summary-icon.locked {
        background: #fff1f2;
        color: #dc3545;
    }

    .summary-content {
        min-width: 0;
    }

    .summary-label {
        display: block;
        margin-bottom: 2px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .045em;
    }

    .summary-value {
        display: block;
        color: #1f2937;
        font-size: 21px;
        line-height: 1.2;
        font-weight: 750;
    }

    /* =========================================================
       ROLE INFORMATION
    ========================================================= */

    .role-info-card {
        margin-bottom: 20px;
        padding: 17px 18px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
    }

    .role-info-header {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 13px;
        color: #374151;
        font-size: 13px;
        font-weight: 750;
    }

    .role-info-header i {
        color: #0d6efd;
        font-size: 16px;
    }

    .role-info-list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .role-info-item {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
        padding: 10px 11px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        color: #6b7280;
        font-size: 11px;
    }

    .role-info-item > span:last-child {
        line-height: 1.4;
    }

    .role-info-item strong {
        color: #374151;
        font-weight: 650;
    }

    /* =========================================================
       ROLE BADGES
    ========================================================= */

    .role-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        width: max-content;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 750;
        line-height: 1.2;
        white-space: nowrap;
    }

    .role-super-admin {
        background: #fff8e1;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .role-admin {
        background: #e7f1ff;
        color: #0d6efd;
        border: 1px solid #cfe2ff;
    }

    .role-employee {
        background: #e9f7ef;
        color: #198754;
        border: 1px solid #c7eed8;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .users-page .alert {
        border-radius: 10px;
        border: 1px solid transparent;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .025);
        font-size: 13px;
    }

    .users-page .alert-success {
        border-color: #badbcc;
    }

    .users-page .alert-danger {
        border-color: #f1aeb5;
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .users-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 13px;
        box-shadow: 0 3px 14px rgba(0, 0, 0, .045);
    }

    .users-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 18px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }

    .users-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #374151;
        font-size: 13px;
        font-weight: 750;
    }

    .users-card-title i {
        color: #0d6efd;
    }

    .users-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 24px;
        padding: 0 8px;
        border-radius: 999px;
        background: #e7f1ff;
        color: #0d6efd;
        font-size: 11px;
        font-weight: 750;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        scrollbar-width: thin;
    }

    .users-table {
        width: 100%;
        min-width: 1180px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .users-table thead th {
        padding: 12px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .055em;
        white-space: nowrap;
    }

    .users-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #eef0f3;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .users-table tbody tr:last-child td {
        border-bottom: none;
    }

    .users-table tbody tr {
        transition: background .15s ease;
    }

    .users-table tbody tr:hover {
        background: #fbfdff;
    }

    .number-cell {
        width: 45px;
        color: #94a3b8 !important;
        font-size: 11px !important;
        font-weight: 650;
    }

    /* =========================================================
       EMPLOYEE
    ========================================================= */

    .employee-cell {
        min-width: 180px;
    }

    .employee-name {
        display: block;
        color: #111827;
        font-weight: 700;
        line-height: 1.35;
    }

    .employee-epf {
        display: block;
        margin-top: 3px;
        color: #6b7280;
        font-size: 11px;
    }

    .current-account,
    .protected-account {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
        padding: 3px 7px;
        border-radius: 5px;
        font-size: 9px;
        font-weight: 750;
    }

    .current-account {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .protected-account {
        background: #fff8e1;
        color: #92400e;
    }

    /* =========================================================
       CONTACT
    ========================================================= */

    .account-email {
        color: #475569;
        white-space: nowrap;
        font-size: 12px;
    }

    .phone-number {
        color: #475569;
        white-space: nowrap;
        font-size: 12px;
    }

    .epf-number {
        color: #374151;
        font-weight: 700;
        letter-spacing: .02em;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-active {
        background: #e9f7ef;
        color: #198754;
        border: 1px solid #c7eed8;
    }

    .status-locked {
        background: #fff1f2;
        color: #dc3545;
        border: 1px solid #fecdd3;
    }

    .failed-attempts {
        display: block;
        margin-top: 5px;
        color: #6b7280;
        font-size: 10px;
    }

    .locked-date {
        display: block;
        margin-top: 3px;
        color: #9ca3af;
        font-size: 9px;
    }

    /* =========================================================
       LOCATION
    ========================================================= */

    .location-text {
        min-width: 110px;
        line-height: 1.4;
    }

    .location-text strong {
        display: block;
        color: #374151;
        font-size: 12px;
        font-weight: 650;
    }

    .location-text span {
        display: block;
        margin-top: 2px;
        color: #6b7280;
        font-size: 10px;
    }

    .empty-value {
        color: #cbd5e1;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 7px;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition: all .18s ease;
    }

    .btn-action i {
        font-size: 12px;
    }

    .btn-view {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    .btn-view:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-edit {
        background: #fff8e6;
        color: #b7791f;
        border-color: #fde68a;
    }

    .btn-edit:hover {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-delete {
        background: #fff1f2;
        color: #dc3545;
        border-color: #fecdd3;
    }

    .btn-delete:hover {
        background: #ffe4e6;
        color: #b91c1c;
    }

    .btn-lock {
        background: #fff7ed;
        color: #c2410c;
        border-color: #fed7aa;
    }

    .btn-lock:hover {
        background: #ffedd5;
        color: #9a3412;
    }

    .btn-unlock {
        background: #ecfdf5;
        color: #198754;
        border-color: #bbf7d0;
    }

    .btn-unlock:hover {
        background: #d1fae5;
        color: #146c43;
    }

    .btn-disabled {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #e5e7eb;
        cursor: not-allowed;
    }

    /* =========================================================
       MOBILE USER CARDS
    ========================================================= */

    .mobile-user-list {
        display: none;
    }

    .mobile-user-card {
        padding: 16px;
        border-bottom: 1px solid #eef0f3;
    }

    .mobile-user-card:last-child {
        border-bottom: none;
    }

    .mobile-user-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 13px;
    }

    .mobile-user-name {
        min-width: 0;
    }

    .mobile-user-name strong {
        display: block;
        color: #111827;
        font-size: 14px;
        font-weight: 750;
    }

    .mobile-user-name span {
        display: block;
        margin-top: 3px;
        color: #64748b;
        font-size: 10px;
    }

    .mobile-details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }

    .mobile-detail {
        min-width: 0;
        padding: 9px 10px;
        border: 1px solid #eef0f3;
        border-radius: 8px;
        background: #f8fafc;
    }

    .mobile-detail-label {
        display: block;
        margin-bottom: 3px;
        color: #94a3b8;
        font-size: 9px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .mobile-detail-value {
        display: block;
        overflow: hidden;
        color: #374151;
        font-size: 11px;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .mobile-actions {
        display: flex;
        gap: 7px;
        padding-top: 2px;
    }

    .mobile-actions .btn-action {
        width: 36px;
        height: 36px;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 65px 25px;
        text-align: center;
    }

    .empty-state-icon {
        width: 68px;
        height: 68px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        border-radius: 18px;
        background: #f1f5f9;
        color: #94a3b8;
    }

    .empty-state-icon i {
        font-size: 30px;
    }

    .empty-state h5 {
        margin: 0 0 7px;
        color: #374151;
        font-size: 16px;
        font-weight: 750;
    }

    .empty-state p {
        max-width: 450px;
        margin: 0 auto;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 62px;
        padding: 12px 18px;
        border-top: 1px solid #e5e7eb;
    }

    .pagination-wrapper .pagination {
        margin: 0;
    }

    /* =========================================================
       RESPONSIVE - TABLET
    ========================================================= */

    @media (max-width: 1100px) {

        .users-page {
            padding: 20px;
        }

        .role-info-list {
            grid-template-columns: 1fr;
        }

        .account-summary {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    /* =========================================================
       RESPONSIVE - MOBILE
    ========================================================= */

    @media (max-width: 768px) {

        .users-page {
            padding: 14px;
        }

        .users-header {
            margin-bottom: 18px;
        }

        .users-title h2 {
            font-size: 21px;
        }

        .users-title h2 i {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            font-size: 17px;
        }

        .users-title p {
            margin-left: 48px;
            font-size: 11px;
        }

        .btn-create-user {
            width: 100%;
        }

        .account-summary {
            grid-template-columns: 1fr;
            gap: 9px;
        }

        .summary-card {
            padding: 13px 14px;
        }

        .role-info-card {
            padding: 14px;
        }

        .role-info-list {
            grid-template-columns: 1fr;
        }

        .role-info-item {
            align-items: flex-start;
        }

        .users-card-header {
            padding: 13px 14px;
        }

        .table-wrapper {
            display: none;
        }

        .mobile-user-list {
            display: block;
        }

        .pagination-wrapper {
            padding: 12px 8px;
            overflow-x: auto;
            justify-content: flex-start;
        }

        .pagination-wrapper .pagination {
            margin: 0 auto;
        }
    }

    /* =========================================================
       RESPONSIVE - SMALL MOBILE
    ========================================================= */

    @media (max-width: 420px) {

        .users-page {
            padding: 10px;
        }

        .users-title h2 {
            font-size: 19px;
        }

        .users-title h2 i {
            width: 35px;
            height: 35px;
            font-size: 15px;
        }

        .users-title p {
            margin-left: 45px;
            font-size: 10px;
        }

        .mobile-details {
            grid-template-columns: 1fr;
        }

        .mobile-user-top {
            flex-direction: column;
        }

        .mobile-user-top > .role-badge {
            align-self: flex-start;
        }
    }
</style>

<div class="users-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}
<div class="users-header">

    <div class="users-title">

        <h2>
            <i class="bi bi-person-lock"></i>
            <span>User Accounts</span>
        </h2>

        <p>
            Manage CPC system login accounts, roles and access security.
        </p>

    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">

        <a
            href="{{ route('admin.users.create') }}"
            class="btn-create-user"
        >
            <i class="bi bi-person-plus-fill"></i>
            <span>Create Login Account</span>
        </a>

        <a
            href="{{ route('admin.users.create-employee-login') }}"
            class="btn-create-user"
        >
            <i class="bi bi-person-check-fill"></i>
            <span>Create Employee Login (EPF)</span>
        </a>

    </div>


</div>


{{-- =====================================================
     ACCOUNT SUMMARY
====================================================== --}}
<div class="account-summary">

    <div class="summary-card">

        <div class="summary-icon total">
            <i class="bi bi-people-fill"></i>
        </div>

        <div class="summary-content">
            <span class="summary-label">Total Accounts</span>
            <span class="summary-value">{{ $totalUsers }}</span>
        </div>

    </div>


    <div class="summary-card">

        <div class="summary-icon active">
            <i class="bi bi-person-check-fill"></i>
        </div>

        <div class="summary-content">
            <span class="summary-label">Active Accounts</span>
            <span class="summary-value">{{ $activeUsers }}</span>
        </div>

    </div>


    <div class="summary-card">

        <div class="summary-icon locked">
            <i class="bi bi-lock-fill"></i>
        </div>

        <div class="summary-content">
            <span class="summary-label">Locked Accounts</span>
            <span class="summary-value">{{ $lockedUsers }}</span>
        </div>

    </div>

</div>


{{-- =====================================================
     ROLE INFORMATION
====================================================== --}}
<div class="role-info-card">

    <div class="role-info-header">

        <i class="bi bi-shield-check"></i>

        <span>Account Roles & Access Levels</span>

    </div>

    <div class="role-info-list">

        <div class="role-info-item">

            <span class="role-badge role-super-admin">
                <i class="bi bi-shield-fill-check"></i>
                Super Admin
            </span>

            <span>
                <strong>Full system control</strong>
            </span>

        </div>


        <div class="role-info-item">

            <span class="role-badge role-admin">
                <i class="bi bi-shield-lock-fill"></i>
                Standard Admin
            </span>

            <span>
                <strong>Operational administration</strong>
            </span>

        </div>


        <div class="role-info-item">

            <span class="role-badge role-employee">
                <i class="bi bi-person-fill"></i>
                Employee
            </span>

            <span>
                <strong>View & search access</strong>
            </span>

        </div>

    </div>

</div>


{{-- =====================================================
     SUCCESS MESSAGE
====================================================== --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show mb-4"
        role="alert"
    >

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>

@endif


{{-- =====================================================
     ERROR MESSAGE
====================================================== --}}
@if(session('error'))

    <div
        class="alert alert-danger alert-dismissible fade show mb-4"
        role="alert"
    >

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>

@endif


{{-- =====================================================
     VALIDATION ERRORS
====================================================== --}}
@if($errors->any())

    <div
        class="alert alert-danger alert-dismissible fade show mb-4"
        role="alert"
    >

        <strong>
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Please check the following:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>

@endif


{{-- =====================================================
     USERS CARD
====================================================== --}}
<div class="users-card">

    <div class="users-card-header">

        <div class="users-card-title">

            <i class="bi bi-person-lines-fill"></i>

            <span>System Login Accounts</span>

            <span class="users-count">
                {{ $totalUsers }}
            </span>

        </div>

    </div>


    {{-- =================================================
         DESKTOP TABLE
    ================================================== --}}
    <div class="table-wrapper">

        @if($users->count())

            <table class="users-table">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Employee</th>
                        <th>EPF Number</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Account Status</th>
                        <th>Branch</th>
                        <th>Department</th>
                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($users as $user)

                        <tr>

                            {{-- NUMBER --}}
                            <td class="number-cell">
                                {{ $users->firstItem() + $loop->index }}
                            </td>


                            {{-- EMPLOYEE --}}
                            <td class="employee-cell">

                                <span class="employee-name">
                                    {{ $user->employee?->name ?? $user->name }}
                                </span>

                                @if($user->employee)

                                    <span class="employee-epf">
                                        {{ $user->employee->designation ?? 'CPC Employee' }}
                                    </span>

                                @endif


                                @if($user->id === auth()->id())

                                    <span class="current-account">
                                        <i class="bi bi-person-check-fill"></i>
                                        Current Account
                                    </span>

                                @endif


                                @if(
                                    $user->role === 'super_admin' &&
                                    $user->id !== auth()->id()
                                )

                                    <span class="protected-account">
                                        <i class="bi bi-shield-lock-fill"></i>
                                        Protected Account
                                    </span>

                                @endif

                            </td>


                            {{-- EPF --}}
                            <td>
                                <span class="epf-number">
                                    {{ $user->epf_number ?? '—' }}
                                </span>
                            </td>


                            {{-- EMAIL --}}
                            <td>
                                <span class="account-email">
                                    {{ $user->email }}
                                </span>
                            </td>


                            {{-- PHONE --}}
                            <td>
                                <span class="phone-number">
                                    {{ $user->phone ?? '—' }}
                                </span>
                            </td>


                            {{-- ROLE --}}
                            <td>

                                @if($user->role === 'super_admin')

                                    <span class="role-badge role-super-admin">
                                        <i class="bi bi-shield-fill-check"></i>
                                        Super Admin
                                    </span>

                                @elseif($user->role === 'admin')

                                    <span class="role-badge role-admin">
                                        <i class="bi bi-shield-lock-fill"></i>
                                        Standard Admin
                                    </span>

                                @else

                                    <span class="role-badge role-employee">
                                        <i class="bi bi-person-fill"></i>
                                        Employee
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($user->is_locked)

                                    <span class="status-badge status-locked">
                                        <i class="bi bi-lock-fill"></i>
                                        Locked
                                    </span>

                                    <span class="failed-attempts">
                                        {{ $user->failed_login_attempts }}
                                        failed attempt{{ $user->failed_login_attempts == 1 ? '' : 's' }}
                                    </span>

                                    @if($user->locked_at)

                                        <span class="locked-date">
                                            Locked:
                                            {{ $user->locked_at->format('d M Y, h:i A') }}
                                        </span>

                                    @endif

                                @else

                                    <span class="status-badge status-active">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Active
                                    </span>

                                    @if($user->failed_login_attempts > 0)

                                        <span class="failed-attempts">
                                            {{ $user->failed_login_attempts }}
                                            failed attempt{{ $user->failed_login_attempts == 1 ? '' : 's' }}
                                        </span>

                                    @endif

                                @endif

                            </td>


                            {{-- BRANCH --}}
                            <td>

                                @if($user->employee?->branch)

                                    <div class="location-text">

                                        <strong>
                                            {{ $user->employee->branch->name }}
                                        </strong>

                                    </div>

                                @else

                                    <span class="empty-value">—</span>

                                @endif

                            </td>


                            {{-- DEPARTMENT --}}
                            <td>

                                @if($user->employee?->department)

                                    <div class="location-text">

                                        <strong>
                                            {{ $user->employee->department->name }}
                                        </strong>

                                        @if($user->employee->functionUnit)

                                            <span>
                                                {{ $user->employee->functionUnit->name }}
                                            </span>

                                        @endif

                                    </div>

                                @else

                                    <span class="empty-value">—</span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- VIEW --}}
                                    <a
                                        href="{{ route('admin.users.show', $user) }}"
                                        class="btn-action btn-view"
                                        title="View Account"
                                        aria-label="View Account"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="btn-action btn-edit"
                                        title="Edit Account"
                                        aria-label="Edit Account"
                                    >
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>


                                    {{-- LOCK / UNLOCK --}}
                                    @if($user->id === auth()->id())

                                        <button
                                            type="button"
                                            class="btn-action btn-disabled"
                                            title="You cannot lock your own account"
                                            aria-label="Account lock disabled"
                                            disabled
                                        >
                                            <i class="bi bi-lock-fill"></i>
                                        </button>

                                    @elseif($user->role === 'super_admin')

                                        @php
                                            $superAdminCount = \App\Models\User::where(
                                                'role',
                                                'super_admin'
                                            )->count();
                                        @endphp

                                        @if($superAdminCount <= 1)

                                            <button
                                                type="button"
                                                class="btn-action btn-disabled"
                                                title="The last Super Admin account cannot be locked"
                                                aria-label="Super Admin lock disabled"
                                                disabled
                                            >
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </button>

                                        @elseif($user->is_locked)

                                            <form
                                                action="{{ route('admin.users.unlock', $user) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Unlock the login account for {{ addslashes($user->name) }}?');"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn-action btn-unlock"
                                                    title="Unlock Account"
                                                    aria-label="Unlock Account"
                                                >
                                                    <i class="bi bi-unlock-fill"></i>
                                                </button>

                                            </form>

                                        @else

                                            <form
                                                action="{{ route('admin.users.lock', $user) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Lock the login account for {{ addslashes($user->name) }}?');"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn-action btn-lock"
                                                    title="Lock Account"
                                                    aria-label="Lock Account"
                                                >
                                                    <i class="bi bi-lock-fill"></i>
                                                </button>

                                            </form>

                                        @endif

                                    @elseif($user->is_locked)

                                        <form
                                            action="{{ route('admin.users.unlock', $user) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Unlock the login account for {{ addslashes($user->name) }}?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn-action btn-unlock"
                                                title="Unlock Account"
                                                aria-label="Unlock Account"
                                            >
                                                <i class="bi bi-unlock-fill"></i>
                                            </button>

                                        </form>

                                    @else

                                        <form
                                            action="{{ route('admin.users.lock', $user) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Lock the login account for {{ addslashes($user->name) }}?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn-action btn-lock"
                                                title="Lock Account"
                                                aria-label="Lock Account"
                                            >
                                                <i class="bi bi-lock-fill"></i>
                                            </button>

                                        </form>

                                    @endif


                                    {{-- DELETE --}}
                                    @if($user->id !== auth()->id())

                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this login account? This action cannot be undone.');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn-action btn-delete"
                                                title="Delete Account"
                                                aria-label="Delete Account"
                                            >
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn-action btn-disabled"
                                            title="You cannot delete your own account"
                                            aria-label="Account deletion disabled"
                                            disabled
                                        >
                                            <i class="bi bi-shield-lock-fill"></i>
                                        </button>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @endif

    </div>


    {{-- =================================================
         MOBILE USER CARDS
    ================================================== --}}
    @if($users->count())

        <div class="mobile-user-list">

            @foreach($users as $user)

                <div class="mobile-user-card">

                    <div class="mobile-user-top">

                        <div class="mobile-user-name">

                            <strong>
                                {{ $user->employee?->name ?? $user->name }}
                            </strong>

                            <span>
                                {{ $user->employee?->designation ?? 'CPC System User' }}
                                @if($user->epf_number)
                                    • EPF {{ $user->epf_number }}
                                @endif
                            </span>

                            @if($user->id === auth()->id())

                                <span class="current-account">
                                    <i class="bi bi-person-check-fill"></i>
                                    Current Account
                                </span>

                            @endif

                        </div>


                        @if($user->role === 'super_admin')

                            <span class="role-badge role-super-admin">
                                <i class="bi bi-shield-fill-check"></i>
                                Super Admin
                            </span>

                        @elseif($user->role === 'admin')

                            <span class="role-badge role-admin">
                                <i class="bi bi-shield-lock-fill"></i>
                                Standard Admin
                            </span>

                        @else

                            <span class="role-badge role-employee">
                                <i class="bi bi-person-fill"></i>
                                Employee
                            </span>

                        @endif

                    </div>


                    <div class="mobile-details">

                        <div class="mobile-detail">

                            <span class="mobile-detail-label">
                                Email
                            </span>

                            <span class="mobile-detail-value">
                                {{ $user->email }}
                            </span>

                        </div>


                        <div class="mobile-detail">

                            <span class="mobile-detail-label">
                                Phone
                            </span>

                            <span class="mobile-detail-value">
                                {{ $user->phone ?? '—' }}
                            </span>

                        </div>


                        <div class="mobile-detail">

                            <span class="mobile-detail-label">
                                Account Status
                            </span>

                            <span class="mobile-detail-value">

                                @if($user->is_locked)

                                    <span class="status-badge status-locked">
                                        <i class="bi bi-lock-fill"></i>
                                        Locked
                                    </span>

                                @else

                                    <span class="status-badge status-active">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Active
                                    </span>

                                @endif

                            </span>

                        </div>


                        <div class="mobile-detail">

                            <span class="mobile-detail-label">
                                Branch
                            </span>

                            <span class="mobile-detail-value">
                                {{ $user->employee?->branch?->name ?? '—' }}
                            </span>

                        </div>


                        <div class="mobile-detail">

                            <span class="mobile-detail-label">
                                Department
                            </span>

                            <span class="mobile-detail-value">
                                {{ $user->employee?->department?->name ?? '—' }}
                            </span>

                        </div>


                        <div class="mobile-detail">

                            <span class="mobile-detail-label">
                                Function Unit
                            </span>

                            <span class="mobile-detail-value">
                                {{ $user->employee?->functionUnit?->name ?? '—' }}
                            </span>

                        </div>

                    </div>


                    <div class="mobile-actions">

                        {{-- VIEW --}}
                        <a
                            href="{{ route('admin.users.show', $user) }}"
                            class="btn-action btn-view"
                            title="View Account"
                            aria-label="View Account"
                        >
                            <i class="bi bi-eye-fill"></i>
                        </a>


                        {{-- EDIT --}}
                        <a
                            href="{{ route('admin.users.edit', $user) }}"
                            class="btn-action btn-edit"
                            title="Edit Account"
                            aria-label="Edit Account"
                        >
                            <i class="bi bi-pencil-fill"></i>
                        </a>


                        {{-- LOCK / UNLOCK --}}
                        @if($user->id === auth()->id())

                            <button
                                type="button"
                                class="btn-action btn-disabled"
                                title="You cannot lock your own account"
                                disabled
                            >
                                <i class="bi bi-lock-fill"></i>
                            </button>

                        @elseif($user->role === 'super_admin')

                            @php
                                $superAdminCount = \App\Models\User::where(
                                    'role',
                                    'super_admin'
                                )->count();
                            @endphp

                            @if($superAdminCount <= 1)

                                <button
                                    type="button"
                                    class="btn-action btn-disabled"
                                    title="The last Super Admin account cannot be locked"
                                    disabled
                                >
                                    <i class="bi bi-shield-lock-fill"></i>
                                </button>

                            @elseif($user->is_locked)

                                <form
                                    action="{{ route('admin.users.unlock', $user) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Unlock the login account for {{ addslashes($user->name) }}?');"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn-action btn-unlock"
                                        title="Unlock Account"
                                    >
                                        <i class="bi bi-unlock-fill"></i>
                                    </button>

                                </form>

                            @else

                                <form
                                    action="{{ route('admin.users.lock', $user) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Lock the login account for {{ addslashes($user->name) }}?');"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn-action btn-lock"
                                        title="Lock Account"
                                    >
                                        <i class="bi bi-lock-fill"></i>
                                    </button>

                                </form>

                            @endif

                        @elseif($user->is_locked)

                            <form
                                action="{{ route('admin.users.unlock', $user) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Unlock the login account for {{ addslashes($user->name) }}?');"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn-action btn-unlock"
                                    title="Unlock Account"
                                >
                                    <i class="bi bi-unlock-fill"></i>
                                </button>

                            </form>

                        @else

                            <form
                                action="{{ route('admin.users.lock', $user) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Lock the login account for {{ addslashes($user->name) }}?');"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn-action btn-lock"
                                    title="Lock Account"
                                >
                                    <i class="bi bi-lock-fill"></i>
                                </button>

                            </form>

                        @endif


                        {{-- DELETE --}}
                        @if($user->id !== auth()->id())

                            <form
                                action="{{ route('admin.users.destroy', $user) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this login account? This action cannot be undone.');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn-action btn-delete"
                                    title="Delete Account"
                                    aria-label="Delete Account"
                                >
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </form>

                        @else

                            <button
                                type="button"
                                class="btn-action btn-disabled"
                                title="You cannot delete your own account"
                                disabled
                            >
                                <i class="bi bi-shield-lock-fill"></i>
                            </button>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- =================================================
             EMPTY STATE
        ================================================== --}}
        <div class="empty-state">

            <div class="empty-state-icon">
                <i class="bi bi-person-lock"></i>
            </div>

            <h5>
                No Login Accounts Found
            </h5>

            <p>
                No employee has been registered with a CPC system
                login account yet.
            </p>

        </div>

    @endif


    {{-- =================================================
         PAGINATION
    ================================================== --}}
    @if($users->hasPages())

        <div class="pagination-wrapper">

            {{ $users->links() }}

        </div>

    @endif

</div>


</div>

@endsection
