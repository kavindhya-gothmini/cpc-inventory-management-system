@extends('layouts.admin')

@section('title', 'User Account Details | CPC Inventory Management System')

@section('content')

<style>
    /* =========================================================
       CPC USER ACCOUNT DETAILS
    ========================================================= */

    .user-show-page {
        min-height: calc(100vh - 72px);
        padding: 24px;
        background: #f5f7fb;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .page-title h2 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
    }

    .page-title h2 i {
        color: #0d6efd;
    }

    .page-title p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-header {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 0 15px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .2s ease;
    }

    .btn-back {
        background: #fff;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-back:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .btn-edit {
        background: #0d6efd;
        color: #fff;
        border: 1px solid #0d6efd;
    }

    .btn-edit:hover {
        background: #0b5ed7;
        color: #fff;
    }

    /* =========================================================
       DETAILS GRID
    ========================================================= */

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .details-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .05);
    }

    .details-card-header {
        padding: 17px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .details-card-header h5 {
        margin: 0;
        color: #374151;
        font-size: 16px;
        font-weight: 700;
    }

    .details-card-header h5 i {
        color: #0d6efd;
        margin-right: 7px;
    }

    .details-card-body {
        padding: 20px;
    }

    /* =========================================================
       PROFILE
    ========================================================= */

    .profile-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 18px;
        border-bottom: 1px solid #eef0f3;
    }

    .profile-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e7f1ff;
        color: #0d6efd;
        font-size: 25px;
        flex-shrink: 0;
    }

    .profile-name {
        margin: 0;
        color: #1f2937;
        font-size: 19px;
        font-weight: 700;
    }

    .profile-subtitle {
        margin-top: 4px;
        color: #6b7280;
        font-size: 13px;
    }

    /* =========================================================
       ROLE BADGES
    ========================================================= */

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 8px;
        white-space: nowrap;
    }

    .role-super-admin {
        background: #fef3c7;
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
       CURRENT ACCOUNT
    ========================================================= */

    .current-account {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 7px;
        color: #0d6efd;
        font-size: 11px;
        font-weight: 600;
    }

    /* =========================================================
       DETAIL ROWS
    ========================================================= */

    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f1f3;
    }

    .detail-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-label {
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        min-width: 135px;
    }

    .detail-value {
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        text-align: right;
        word-break: break-word;
    }

    /* =========================================================
       PERMISSIONS
    ========================================================= */

    .permissions-card {
        margin-top: 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .05);
    }

    .permissions-header {
        padding: 17px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .permissions-header h5 {
        margin: 0;
        color: #374151;
        font-size: 16px;
        font-weight: 700;
    }

    .permissions-header h5 i {
        color: #0d6efd;
        margin-right: 7px;
    }

    .permissions-body {
        padding: 20px;
    }

    .permissions-role {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 15px;
        border-radius: 9px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .permissions-role-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 9px;
        font-size: 19px;
    }

    .permissions-role-content h6 {
        margin: 0 0 5px;
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
    }

    .permissions-role-content p {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.55;
    }

    .permission-list {
        margin: 14px 0 0;
        padding: 0;
        list-style: none;
    }

    .permission-list li {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-bottom: 7px;
        color: #4b5563;
        font-size: 12px;
        line-height: 1.45;
    }

    .permission-list li:last-child {
        margin-bottom: 0;
    }

    .permission-list i {
        margin-top: 2px;
    }

    .permission-allowed i {
        color: #198754;
    }

    .permission-restricted i {
        color: #dc3545;
    }

    .permission-super {
        background: #fffbeb;
        border-color: #fde68a;
    }

    .permission-super .permissions-role-icon {
        background: #fef3c7;
        color: #92400e;
    }

    .permission-admin {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .permission-admin .permissions-role-icon {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .permission-employee {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .permission-employee .permissions-role-icon {
        background: #dcfce7;
        color: #15803d;
    }

    /* =========================================================
       EMPLOYEE SUMMARY
    ========================================================= */

    .employee-summary {
        padding: 14px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        margin-bottom: 15px;
    }

    .employee-summary-name {
        color: #1f2937;
        font-size: 16px;
        font-weight: 700;
    }

    .employee-summary-epf {
        color: #6b7280;
        font-size: 12px;
        margin-top: 3px;
    }

    /* =========================================================
       LOCATION
    ========================================================= */

    .location-block {
        margin-top: 14px;
    }

    .location-block-title {
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 7px;
    }

    .location-block-value {
        color: #374151;
        font-size: 14px;
        font-weight: 600;
    }

    /* =========================================================
       ACCOUNT PROTECTION
    ========================================================= */

    .protection-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 15px;
        padding: 12px 14px;
        background: #fff8e6;
        border: 1px solid #fde68a;
        border-radius: 8px;
        color: #92400e;
        font-size: 12px;
        line-height: 1.5;
    }

    .protection-notice i {
        margin-top: 2px;
    }

    /* =========================================================
       DANGER ZONE
    ========================================================= */

    .danger-zone {
        margin-top: 20px;
        background: #fff;
        border: 1px solid #fecdd3;
        border-radius: 12px;
        overflow: hidden;
    }

    .danger-zone-header {
        padding: 15px 20px;
        background: #fff1f2;
        border-bottom: 1px solid #fecdd3;
    }

    .danger-zone-header h5 {
        margin: 0;
        color: #b91c1c;
        font-size: 15px;
        font-weight: 700;
    }

    .danger-zone-body {
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .danger-zone-body p {
        margin: 0;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.5;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 0 15px;
        border: 1px solid #dc3545;
        border-radius: 8px;
        background: #dc3545;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-delete:hover {
        background: #bb2d3b;
        border-color: #bb2d3b;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .alert {
        border-radius: 9px;
        border: none;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .details-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {

        .user-show-page {
            padding: 16px;
        }

        .page-title h2 {
            font-size: 22px;
        }

        .page-header {
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn-header {
            flex: 1;
            justify-content: center;
        }

        .detail-row {
            display: block;
        }

        .detail-value {
            text-align: left;
            margin-top: 4px;
        }

        .danger-zone-body {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-delete {
            justify-content: center;
        }
    }
</style>


<div class="user-show-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="page-header">

        <div class="page-title">

            <h2>

                <i class="bi bi-person-vcard-fill me-2"></i>

                User Account Details

            </h2>

            <p>

                View system login, employee information,
                and account permissions.

            </p>

        </div>


        <div class="header-actions">

            <a href="{{ route('admin.users.index') }}"
               class="btn-header btn-back">

                <i class="bi bi-arrow-left"></i>

                Back to Accounts

            </a>


            <a href="{{ route('admin.users.edit', $user) }}"
               class="btn-header btn-edit">

                <i class="bi bi-pencil-fill"></i>

                Edit Account

            </a>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS / ERROR MESSAGES
    ====================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show mb-4"
             role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show mb-4"
             role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =====================================================
         MAIN DETAILS
    ====================================================== --}}
    <div class="details-grid">


        {{-- =================================================
             LOGIN ACCOUNT
        ================================================== --}}
        <div class="details-card">

            <div class="details-card-header">

                <h5>

                    <i class="bi bi-shield-lock-fill"></i>

                    Login Account

                </h5>

            </div>


            <div class="details-card-body">

                <div class="profile-header">

                    <div class="profile-icon">

                        @if($user->role === 'super_admin')

                            <i class="bi bi-shield-fill-check"></i>

                        @elseif($user->role === 'admin')

                            <i class="bi bi-shield-lock-fill"></i>

                        @else

                            <i class="bi bi-person-fill"></i>

                        @endif

                    </div>


                    <div>

                        <h3 class="profile-name">

                            {{ $user->name }}

                        </h3>

                        <div class="profile-subtitle">

                            CPC System Login Account

                        </div>


                        {{-- Role --}}
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


                        {{-- Current account --}}
                        @if($user->id === auth()->id())

                            <div class="current-account">

                                <i class="bi bi-person-check-fill"></i>

                                Current Account

                            </div>

                        @endif

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        EPF Number
                    </div>

                    <div class="detail-value">

                        {{ $user->epf_number ?? '—' }}

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Email
                    </div>

                    <div class="detail-value">

                        {{ $user->email }}

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Phone
                    </div>

                    <div class="detail-value">

                        {{ $user->phone ?? '—' }}

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Account Created
                    </div>

                    <div class="detail-value">

                        {{ $user->created_at?->format('d M Y, h:i A') ?? '—' }}

                    </div>

                </div>


                <div class="detail-row">

                    <div class="detail-label">
                        Last Updated
                    </div>

                    <div class="detail-value">

                        {{ $user->updated_at?->format('d M Y, h:i A') ?? '—' }}

                    </div>

                </div>


                {{-- Super Admin protection --}}
                @if($user->role === 'super_admin')

                    <div class="protection-notice">

                        <i class="bi bi-shield-lock-fill"></i>

                        <span>

                            This is a <strong>Super Admin</strong> account.
                            Privileged account-management access is enabled.
                            The last remaining Super Admin cannot be demoted
                            or deleted.

                        </span>

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
             LINKED EMPLOYEE
        ================================================== --}}
        <div class="details-card">

            <div class="details-card-header">

                <h5>

                    <i class="bi bi-person-badge-fill"></i>

                    Linked Employee

                </h5>

            </div>


            <div class="details-card-body">

                @if($user->employee)

                    <div class="employee-summary">

                        <div class="employee-summary-name">

                            {{ $user->employee->name }}

                        </div>

                        <div class="employee-summary-epf">

                            EPF:

                            <strong>

                                {{ $user->employee->epf_no }}

                            </strong>

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Designation
                        </div>

                        <div class="detail-value">

                            {{ $user->employee->designation ?? '—' }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Employee Email
                        </div>

                        <div class="detail-value">

                            {{ $user->employee->email ?? '—' }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Employee Phone
                        </div>

                        <div class="detail-value">

                            {{ $user->employee->phone ?? '—' }}

                        </div>

                    </div>


                    <div class="location-block">

                        <div class="location-block-title">
                            Branch
                        </div>

                        <div class="location-block-value">

                            {{ $user->employee->branch?->name ?? '—' }}

                        </div>

                    </div>


                    <div class="location-block">

                        <div class="location-block-title">
                            Department
                        </div>

                        <div class="location-block-value">

                            {{ $user->employee->department?->name ?? '—' }}

                        </div>

                    </div>


                    <div class="location-block">

                        <div class="location-block-title">
                            Function Unit
                        </div>

                        <div class="location-block-value">

                            {{ $user->employee->functionUnit?->name ?? '—' }}

                        </div>

                    </div>

                @else

                    <div class="text-center py-4">

                        <i class="bi bi-person-x fs-1 text-secondary"></i>

                        <h6 class="mt-3">

                            No Employee Linked

                        </h6>

                        <p class="text-muted small mb-0">

                            This login account is not currently linked
                            to an employee record.

                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
         ROLE PERMISSIONS
    ====================================================== --}}
    <div class="permissions-card">

        <div class="permissions-header">

            <h5>

                <i class="bi bi-shield-check"></i>

                Role & Permissions

            </h5>

        </div>


        <div class="permissions-body">


            {{-- =================================================
                 SUPER ADMIN
            ================================================== --}}
            @if($user->role === 'super_admin')

                <div class="permissions-role permission-super">

                    <div class="permissions-role-icon">

                        <i class="bi bi-shield-fill-check"></i>

                    </div>


                    <div class="permissions-role-content">

                        <h6>
                            Super Admin — Full System Access
                        </h6>

                        <p>

                            This account has the highest level of
                            system access.

                        </p>


                        <ul class="permission-list">

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Full access to CPC Inventory Management.
                                </span>

                            </li>

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Create, edit, view and delete permitted records.
                                </span>

                            </li>

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Manage login accounts and administrator roles.
                                </span>

                            </li>

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Perform privileged account-management operations.
                                </span>

                            </li>

                        </ul>

                    </div>

                </div>


            {{-- =================================================
                 STANDARD ADMIN
            ================================================== --}}
            @elseif($user->role === 'admin')

                <div class="permissions-role permission-admin">

                    <div class="permissions-role-icon">

                        <i class="bi bi-shield-lock-fill"></i>

                    </div>


                    <div class="permissions-role-content">

                        <h6>
                            Standard Admin — Operational Administration
                        </h6>

                        <p>

                            This account is intended for normal
                            day-to-day administrative operations.

                        </p>


                        <ul class="permission-list">

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Manage permitted inventory and operational records.
                                </span>

                            </li>

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Access permitted administrative modules.
                                </span>

                            </li>

                            <li class="permission-restricted">

                                <i class="bi bi-x-circle-fill"></i>

                                <span>
                                    Cannot access User Account Management.
                                </span>

                            </li>

                            <li class="permission-restricted">

                                <i class="bi bi-x-circle-fill"></i>

                                <span>
                                    Cannot manage privileged administrator accounts.
                                </span>

                            </li>

                        </ul>

                    </div>

                </div>


            {{-- =================================================
                 EMPLOYEE
            ================================================== --}}
            @else

                <div class="permissions-role permission-employee">

                    <div class="permissions-role-icon">

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <div class="permissions-role-content">

                        <h6>
                            Employee — View & Search Access
                        </h6>

                        <p>

                            This account is intended for employees
                            who need access to permitted information
                            without administrative privileges.

                        </p>


                        <ul class="permission-list">

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    View permitted system information.
                                </span>

                            </li>

                            <li class="permission-allowed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Search permitted records.
                                </span>

                            </li>

                            <li class="permission-restricted">

                                <i class="bi bi-x-circle-fill"></i>

                                <span>
                                    Cannot create or edit records.
                                </span>

                            </li>

                            <li class="permission-restricted">

                                <i class="bi bi-x-circle-fill"></i>

                                <span>
                                    Cannot delete records or manage user accounts.
                                </span>

                            </li>

                        </ul>

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         DELETE ACCOUNT
    ====================================================== --}}
    @if($user->id !== auth()->id())

        <div class="danger-zone">

            <div class="danger-zone-header">

                <h5>

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    Account Management

                </h5>

            </div>


            <div class="danger-zone-body">

                <p>

                    Deleting this login account will permanently remove
                    the user's system access. The linked employee record
                    will not be deleted.

                </p>


                <form action="{{ route('admin.users.destroy', $user) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to permanently delete this login account? The employee record will remain unchanged.');">

                    @csrf

                    @method('DELETE')


                    <button type="submit"
                            class="btn-delete">

                        <i class="bi bi-trash-fill"></i>

                        Delete Login Account

                    </button>

                </form>

            </div>

        </div>

    @endif

</div>

@endsection