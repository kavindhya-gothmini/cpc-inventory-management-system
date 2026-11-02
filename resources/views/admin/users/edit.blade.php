@extends('layouts.admin')

@section('title', 'Edit Login Account | CPC Inventory Management System')

@section('content')

@php
$currentUser = auth()->user();


/*
|--------------------------------------------------------------------------
| USER LOGIN MANAGEMENT ACCESS
|--------------------------------------------------------------------------
| Only Super Admin can manage login accounts.
*/

if (!$currentUser || $currentUser->role !== 'super_admin') {
    abort(403);
}

$isCurrentUser = $user->id === $currentUser->id;

/*
|--------------------------------------------------------------------------
| Current Account Role
|--------------------------------------------------------------------------
*/

$accountRole = match ($user->role) {
    'super_admin' => 'Super Admin',
    'admin' => 'Standard Admin',
    'employee' => 'Employee',
    default => ucfirst(str_replace('_', ' ', $user->role)),
};

$superAdminCount = \App\Models\User::where(
    'role',
    'super_admin'
)->count();

$isLastSuperAdmin =
    $user->role === 'super_admin' &&
    $superAdminCount <= 1;

/*
|--------------------------------------------------------------------------
| Existing Phone Number
|--------------------------------------------------------------------------
| The database stores the complete phone number, for example:
| +94712345678
|
| Extract country code and the final 9 digits for the form.
*/

$existingPhone = old('phone', $user->phone ?? '');

$countryCode = '+94';
$phoneNumber = '';

if (preg_match('/^(\+[1-9][0-9]{0,3})([0-9]{9})$/', $existingPhone, $matches)) {
    $countryCode = $matches[1];
    $phoneNumber = $matches[2];
} else {
    $digitsOnly = preg_replace('/[^0-9]/', '', $existingPhone);

    if (strlen($digitsOnly) >= 9) {
        $phoneNumber = substr($digitsOnly, -9);
    }
}


@endphp

<style>
    /* =========================================================
       CPC EDIT USER ACCOUNT
    ========================================================= */

    .user-edit-page {
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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 0 15px;
        border-radius: 8px;
        background: #fff;
        color: #374151;
        border: 1px solid #d1d5db;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .2s ease;
    }

    .btn-back:hover {
        background: #f3f4f6;
        color: #111827;
    }

    /* =========================================================
       FORM CARD
    ========================================================= */

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .05);
        overflow: hidden;
    }

    .form-card-header {
        padding: 18px 22px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-card-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #374151;
    }

    .form-card-header p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .form-card-body {
        padding: 24px;
    }

    /* =========================================================
       EMPLOYEE SUMMARY
    ========================================================= */

    .employee-summary {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 15px;
        margin-bottom: 24px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
    }

    .employee-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 50%;
        background: #e7f1ff;
        color: #0d6efd;
        font-size: 20px;
    }

    .employee-summary-name {
        color: #1f2937;
        font-size: 16px;
        font-weight: 700;
    }

    .employee-summary-details {
        margin-top: 3px;
        color: #6b7280;
        font-size: 12px;
    }

    /* =========================================================
       PROFILE PICTURE
    ========================================================= */

    .profile-picture-section {
        margin-bottom: 26px;
        padding: 18px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
    }

    .profile-picture-content {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .profile-picture-preview-wrapper {
        position: relative;
        width: 104px;
        height: 104px;
        flex-shrink: 0;
    }

    .profile-picture-preview {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
        background: #e7f1ff;
    }

    .profile-picture-default {
        width: 104px;
        height: 104px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e7f1ff;
        color: #0d6efd;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
        font-size: 40px;
    }

    .profile-picture-info {
        flex: 1;
        min-width: 240px;
    }

    .profile-picture-title {
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .profile-picture-description {
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .profile-picture-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .profile-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 7px;
        background: #0d6efd;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .profile-upload-label:hover {
        background: #0b5ed7;
    }

    .profile-remove-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 7px;
        border: 1px solid #fecaca;
        background: #fff;
        color: #b91c1c;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .profile-remove-button:hover {
        background: #fef2f2;
    }

    .profile-remove-button.active {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .profile-file-name {
        display: block;
        margin-top: 7px;
        color: #6b7280;
        font-size: 11px;
    }

    .profile-file-input {
        display: none;
    }

    .profile-remove-input {
        display: none;
    }

    /* =========================================================
       CURRENT ROLE BANNER
    ========================================================= */

    .current-role-banner {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 24px;
        padding: 14px 16px;
        border-radius: 9px;
        border: 1px solid #fde68a;
        background: #fffbeb;
    }

    .current-role-icon {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #fef3c7;
        color: #92400e;
    }

    .current-role-content strong {
        display: block;
        color: #92400e;
        font-size: 13px;
        margin-bottom: 3px;
    }

    .current-role-content span {
        color: #57534e;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM SECTIONS
    ========================================================= */

    .form-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 4px 0 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eef0f3;
        color: #374151;
        font-size: 15px;
        font-weight: 700;
    }

    .form-section-title i {
        color: #0d6efd;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        margin-bottom: 7px;
    }

    .required-mark {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .12);
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #6b7280;
    }

    .form-text {
        color: #6b7280;
        font-size: 12px;
        margin-top: 5px;
    }

    /* =========================================================
       EPF VALIDATION
    ========================================================= */

    .epf-validation-message {
        display: none;
        margin-top: 6px;
        font-size: 12px;
        line-height: 1.45;
    }

    .epf-validation-message.show {
        display: block;
    }

    .epf-validation-message.invalid {
        color: #b91c1c;
    }

    .epf-validation-message.valid {
        color: #15803d;
    }

    /* =========================================================
       PHONE INPUT
    ========================================================= */

    .phone-input-group {
        display: flex;
        gap: 8px;
    }

    .phone-country-code {
        width: 145px;
        flex-shrink: 0;
    }

    .phone-number-wrapper {
        flex: 1;
        position: relative;
    }

    .phone-number-wrapper .form-control {
        padding-right: 58px;
    }

    .phone-counter {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 11px;
        pointer-events: none;
    }

    .phone-counter.complete {
        color: #198754;
        font-weight: 700;
    }

    .phone-counter.invalid {
        color: #dc3545;
    }

    .phone-validation-message {
        display: none;
        margin-top: 6px;
        color: #dc3545;
        font-size: 12px;
    }

    .phone-validation-message.show {
        display: block;
    }

    /* =========================================================
       EMAIL VALIDATION
    ========================================================= */

    .email-validation-message {
        display: none;
        margin-top: 6px;
        color: #dc3545;
        font-size: 12px;
    }

    .email-validation-message.show {
        display: block;
    }

    /* =========================================================
       ROLE DISPLAY
    ========================================================= */

    .role-display-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px;
        border: 1px solid #fde68a;
        border-radius: 10px;
        background: #fffbeb;
    }

    .role-display-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .role-display-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 9px;
        background: #fef3c7;
        color: #92400e;
        font-size: 19px;
    }

    .role-display-title {
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
    }

    .role-display-description {
        margin-top: 3px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.45;
    }

    .role-locked-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 11px;
        border-radius: 999px;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================================================
       PASSWORD
    ========================================================= */

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 45px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #6b7280;
        cursor: pointer;
        padding: 4px;
    }

    .password-toggle:hover {
        color: #374151;
    }

    .password-rules {
        margin-top: 8px;
        padding: 10px 12px;
        background: #f8fafc;
        border-radius: 7px;
        font-size: 12px;
        color: #6b7280;
    }

    .password-rules ul {
        margin: 5px 0 0;
        padding-left: 18px;
    }

    .password-rules li {
        margin-bottom: 2px;
    }

    /* =========================================================
       SECURITY NOTICE
    ========================================================= */

    .security-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 24px;
        padding: 13px 15px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.55;
    }

    .security-notice i {
        color: #0d6efd;
        font-size: 16px;
        margin-top: 1px;
    }

    .security-notice strong {
        color: #374151;
    }

    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 24px;
        border-top: 1px solid #eef0f3;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 0 18px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-cancel:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .btn-update {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 0 20px;
        border: none;
        border-radius: 8px;
        background: #0d6efd;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-update:hover {
        background: #0b5ed7;
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

    @media (max-width: 768px) {

        .user-edit-page {
            padding: 16px;
        }

        .page-title h2 {
            font-size: 22px;
        }

        .form-card-body {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-update {
            width: 100%;
        }

        .employee-summary {
            align-items: flex-start;
        }

        .profile-picture-content {
            align-items: flex-start;
        }

        .role-display-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .role-locked-badge {
            align-self: flex-start;
        }

        .phone-input-group {
            flex-direction: column;
        }

        .phone-country-code {
            width: 100%;
        }
    }

    @media (max-width: 480px) {

        .user-edit-page {
            padding: 12px;
        }

        .form-card-body {
            padding: 15px;
        }

        .profile-picture-info {
            min-width: 100%;
        }

        .profile-picture-actions {
            width: 100%;
        }

        .profile-upload-label,
        .profile-remove-button {
            width: 100%;
            justify-content: center;
        }

        .current-role-banner {
            padding: 12px;
        }

        .role-display-card {
            padding: 13px;
        }
    }
</style>

<div class="user-edit-page">

```
{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div class="page-title">

        <h2>
            <i class="bi bi-person-gear me-2"></i>
            Edit Login Account
        </h2>

        <p>
            Update the account identity and login information
            associated with this CPC system account.
        </p>

    </div>

    <a
        href="{{ route('admin.users.index') }}"
        class="btn-back"
    >
        <i class="bi bi-arrow-left"></i>
        Back to Accounts
    </a>

</div>


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
            Please correct the following errors:
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
        ></button>

    </div>

@endif


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
        ></button>

    </div>

@endif


{{-- =====================================================
     FORM CARD
====================================================== --}}

<div class="form-card">

    <div class="form-card-header">

        <h5>
            <i class="bi bi-shield-lock-fill me-2"></i>
            Account Settings
        </h5>

        <p>
            Super Admin can update the account identity,
            contact details, password and profile picture.
        </p>

    </div>


    <div class="form-card-body">


        {{-- =================================================
             LINKED EMPLOYEE
        ================================================== --}}

        @if($user->employee)

            <div class="employee-summary">

                <div class="employee-icon">

                    <i class="bi bi-person-badge-fill"></i>

                </div>

                <div>

                    <div class="employee-summary-name">

                        {{ $user->employee->name }}

                    </div>

                    <div class="employee-summary-details">

                        Employee Record EPF:

                        <strong>
                            {{ $user->employee->epf_no }}
                        </strong>

                        @if($user->employee->designation)

                            &nbsp;•&nbsp;

                            {{ $user->employee->designation }}

                        @endif

                    </div>

                </div>

            </div>

        @endif


        {{-- =================================================
             CURRENT ROLE INFORMATION
        ================================================== --}}

        <div class="current-role-banner">

            <div class="current-role-icon">

                <i class="bi bi-shield-fill-check"></i>

            </div>

            <div class="current-role-content">

                <strong>
                    Current Access Level:
                    {{ $accountRole }}
                </strong>

                <span>

                    This account's system role is protected.
                    The Super Admin can update the account
                    details without changing its current
                    access level.

                </span>

            </div>

        </div>


        <form
            id="editUserForm"
            action="{{ route('admin.users.update', $user) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            {{-- =================================================
                 PROFILE PICTURE
            ================================================== --}}

            <div class="form-section-title">

                <i class="bi bi-person-circle"></i>

                Profile Picture

            </div>


            <div class="profile-picture-section">

                <div class="profile-picture-content">

                    <div class="profile-picture-preview-wrapper">

                        @if($user->profile_picture)

                            <img
                                src="{{ asset('storage/' . $user->profile_picture) }}"
                                alt="{{ $user->name }}"
                                class="profile-picture-preview"
                                id="profilePicturePreview"
                            >

                        @else

                            <div
                                class="profile-picture-default"
                                id="profilePictureDefault"
                            >

                                <i class="bi bi-person-fill"></i>

                            </div>

                            <img
                                src=""
                                alt="{{ $user->name }}"
                                class="profile-picture-preview d-none"
                                id="profilePicturePreview"
                            >

                        @endif

                    </div>


                    <div class="profile-picture-info">

                        <div class="profile-picture-title">

                            Account Profile Photo

                        </div>

                        <div class="profile-picture-description">

                            Upload a professional profile picture
                            for this login account.

                            <br>

                            <strong>Allowed:</strong>
                            JPG, JPEG, PNG, WEBP

                            &nbsp;|&nbsp;

                            <strong>Maximum:</strong>
                            2 MB

                        </div>


                        <div class="profile-picture-actions">

                            <label
                                for="profile_picture"
                                class="profile-upload-label"
                            >

                                <i class="bi bi-camera-fill"></i>

                                Choose Picture

                            </label>


                            @if($user->profile_picture)

                                <button
                                    type="button"
                                    class="profile-remove-button"
                                    id="removeProfilePicture"
                                >

                                    <i class="bi bi-trash3"></i>

                                    Remove Picture

                                </button>

                            @endif

                        </div>


                        <input
                            type="file"
                            name="profile_picture"
                            id="profile_picture"
                            class="profile-file-input"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >


                        @if($user->profile_picture)

                            <input
                                type="hidden"
                                name="remove_profile_picture"
                                id="remove_profile_picture"
                                value="0"
                                class="profile-remove-input"
                            >

                        @endif


                        <span
                            class="profile-file-name"
                            id="profileFileName"
                        >

                            @if($user->profile_picture)

                                Current profile picture

                            @else

                                No profile picture selected

                            @endif

                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 LOGIN ACCOUNT IDENTITY
            ================================================== --}}

            <div class="form-section-title mt-4">

                <i class="bi bi-person-vcard-fill"></i>

                Login Account Identity

            </div>


            <div class="row g-4">


                {{-- EPF NUMBER --}}

                <div class="col-md-6">

                    <label
                        for="epf_number"
                        class="form-label"
                    >

                        EPF Number

                        <span class="required-mark">*</span>

                    </label>

                    <input
                        type="text"
                        name="epf_number"
                        id="epf_number"
                        value="{{ old('epf_number', $user->epf_number) }}"
                        class="form-control @error('epf_number') is-invalid @enderror"
                        maxlength="5"
                        minlength="5"
                        pattern="[0-9]{5}"
                        inputmode="numeric"
                        required
                        autocomplete="off"
                    >

                    @error('epf_number')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    <div
                        class="epf-validation-message"
                        id="epfValidationMessage"
                    ></div>

                    <div class="form-text">

                        Enter exactly 5 numeric digits.
                        Repeated digits and sequential numbers
                        such as 12345, 23456 or 54321 are not allowed.

                    </div>

                </div>


                {{-- ACCOUNT NAME --}}

                <div class="col-md-6">

                    <label
                        for="name"
                        class="form-label"
                    >

                        Account Name

                        <span class="required-mark">*</span>

                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                        maxlength="255"
                        required
                        autocomplete="name"
                    >

                    @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    <div class="form-text">

                        This is the name displayed for the
                        login account. Updating it does not
                        change the linked Employee record.

                    </div>

                </div>


                {{-- EMAIL --}}

                <div class="col-md-6">

                    <label
                        for="email"
                        class="form-label"
                    >

                        Login Email

                        <span class="required-mark">*</span>

                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control @error('email') is-invalid @enderror"
                        maxlength="255"
                        required
                        autocomplete="email"
                        placeholder="example@cpc.lk"
                    >

                    @error('email')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    <div
                        class="email-validation-message"
                        id="emailValidationMessage"
                    ></div>

                    <div class="form-text">

                        Must be a valid email address containing
                        <strong>@</strong>. Used for login and
                        password recovery.

                    </div>

                </div>


                {{-- PHONE --}}

                <div class="col-md-6">

                    <label
                        class="form-label"
                    >

                        Phone Number

                        <span class="required-mark">*</span>

                    </label>


                    <div class="phone-input-group">


                        {{-- COUNTRY CODE --}}

                        <select
                            name="country_code"
                            id="country_code"
                            class="form-select phone-country-code @error('country_code') is-invalid @enderror"
                            required
                        >

                            <option
                                value=""
                                disabled
                            >
                                Country Code
                            </option>

                            <option
                                value="+94"
                                {{ $countryCode === '+94' ? 'selected' : '' }}
                            >
                                +94 — Sri Lanka
                            </option>

                            <option
                                value="+91"
                                {{ $countryCode === '+91' ? 'selected' : '' }}
                            >
                                +91 — India
                            </option>

                            <option
                                value="+1"
                                {{ $countryCode === '+1' ? 'selected' : '' }}
                            >
                                +1 — USA / Canada
                            </option>

                            <option
                                value="+44"
                                {{ $countryCode === '+44' ? 'selected' : '' }}
                            >
                                +44 — United Kingdom
                            </option>

                            <option
                                value="+61"
                                {{ $countryCode === '+61' ? 'selected' : '' }}
                            >
                                +61 — Australia
                            </option>

                            <option
                                value="+971"
                                {{ $countryCode === '+971' ? 'selected' : '' }}
                            >
                                +971 — UAE
                            </option>

                        </select>


                        {{-- 9-DIGIT NUMBER --}}

                        <div class="phone-number-wrapper">

                            <input
                                type="text"
                                name="phone_number"
                                id="phone_number"
                                value="{{ old('phone_number', $phoneNumber) }}"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                maxlength="9"
                                minlength="9"
                                pattern="[0-9]{9}"
                                inputmode="numeric"
                                required
                                autocomplete="tel-national"
                                placeholder="712345678"
                            >

                            <span
                                class="phone-counter"
                                id="phoneCounter"
                            >
                                0/9
                            </span>

                        </div>

                    </div>


                    @error('country_code')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror


                    @error('phone_number')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror


                    <div
                        class="phone-validation-message"
                        id="phoneValidationMessage"
                    ></div>


                    <div class="form-text">

                        Select the country code and enter exactly
                        9 numeric digits.

                        Example:

                        <strong>+94 712345678</strong>

                        The system stores the complete number as:

                        <strong>+94712345678</strong>.

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SYSTEM ROLE
            ================================================== --}}

            <div class="form-section-title mt-4">

                <i class="bi bi-shield-lock-fill"></i>

                System Role

            </div>


            <div class="role-display-card">

                <div class="role-display-left">

                    <div class="role-display-icon">

                        @if($user->role === 'super_admin')

                            <i class="bi bi-shield-fill-check"></i>

                        @elseif($user->role === 'admin')

                            <i class="bi bi-shield-fill"></i>

                        @else

                            <i class="bi bi-person-check-fill"></i>

                        @endif

                    </div>


                    <div>

                        <div class="role-display-title">

                            {{ $accountRole }}

                        </div>

                        <div class="role-display-description">

                            This account retains its current
                            {{ $accountRole }} access.

                            Its system role cannot be changed
                            from User Login Management.

                        </div>

                    </div>

                </div>


                <span class="role-locked-badge">

                    <i class="bi bi-lock-fill"></i>

                    Role Locked

                </span>

            </div>


            {{-- =================================================
                 PASSWORD
            ================================================== --}}

            <div class="form-section-title mt-4">

                <i class="bi bi-lock-fill"></i>

                Change Password

            </div>


            <div class="row g-4">


                {{-- NEW PASSWORD --}}

                <div class="col-md-6">

                    <label
                        for="password"
                        class="form-label"
                    >

                        New Password

                        <span class="text-muted fw-normal">
                            (optional)
                        </span>

                    </label>

                    <div class="password-wrapper">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Leave blank to keep current password"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('password', this)"
                            title="Show password"
                            aria-label="Show password"
                        >

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                    @error('password')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                    <div class="password-rules">

                        <strong>
                            If changing the password:
                        </strong>

                        <ul>

                            <li>At least 8 characters</li>
                            <li>At least one uppercase letter</li>
                            <li>At least one lowercase letter</li>
                            <li>At least one number</li>
                            <li>At least one special character</li>

                        </ul>

                    </div>

                </div>


                {{-- CONFIRM PASSWORD --}}

                <div class="col-md-6">

                    <label
                        for="password_confirmation"
                        class="form-label"
                    >

                        Confirm New Password

                    </label>

                    <div class="password-wrapper">

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control"
                            placeholder="Re-enter new password"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('password_confirmation', this)"
                            title="Show password"
                            aria-label="Show password"
                        >

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SECURITY NOTICE
            ================================================== --}}

            <div class="security-notice">

                <i class="bi bi-shield-check"></i>

                <div>

                    <strong>Account security:</strong>

                    Only a Super Admin can access User Login
                    Management.

                    This page allows the Super Admin to update
                    the login account's name, EPF number, email,
                    phone number, password and profile picture.

                    <br>

                    The account's existing
                    <strong>{{ $accountRole }}</strong>
                    role is protected and cannot be changed
                    through this page.

                    <br>

                    Changes to the login account's name and EPF
                    number do not modify the linked Employee
                    record.

                </div>

            </div>


            {{-- =================================================
                 FORM ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('admin.users.index') }}"
                    class="btn-cancel"
                >

                    <i class="bi bi-x-lg"></i>

                    Cancel

                </a>

                <button
                    type="submit"
                    class="btn-update"
                >

                    <i class="bi bi-check-lg"></i>

                    Update Account

                </button>

            </div>

        </form>

    </div>

</div>
```

</div>

<script>

    /* =========================================================
       PASSWORD TOGGLE
    ========================================================= */

    function togglePassword(fieldId, button) {

        const field =
            document.getElementById(fieldId);

        const icon =
            button.querySelector('i');

        if (!field || !icon) {
            return;
        }

        if (field.type === 'password') {

            field.type = 'text';

            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');

            button.title = 'Hide password';

            button.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            field.type = 'password';

            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');

            button.title = 'Show password';

            button.setAttribute(
                'aria-label',
                'Show password'
            );

        }
    }


    /* =========================================================
       EPF VALIDATION
       Rules:
       - Exactly 5 numeric digits
       - Repeated digits not allowed
       - Ascending sequence not allowed
       - Descending sequence not allowed
    ========================================================= */

    function validateEpfNumber(epf) {

        epf = String(epf || '').trim();


        if (!/^[0-9]{5}$/.test(epf)) {

            return {
                valid: false,
                message:
                    'EPF number must contain exactly 5 numeric digits.'
            };

        }


        /* Repeated digit: 11111, 22222, etc. */

        if (/^([0-9])\1{4}$/.test(epf)) {

            return {
                valid: false,
                message:
                    'Repeated-digit EPF numbers are not allowed. Please enter a valid 5-digit EPF number.'
            };

        }


        let ascending = true;
        let descending = true;


        for (let i = 1; i < epf.length; i++) {

            const previous =
                Number(epf[i - 1]);

            const current =
                Number(epf[i]);


            if (current !== previous + 1) {
                ascending = false;
            }


            if (current !== previous - 1) {
                descending = false;
            }

        }


        if (ascending || descending) {

            return {
                valid: false,
                message:
                    'Sequential EPF numbers such as 12345, 23456 or 54321 are not allowed.'
            };

        }


        return {
            valid: true,
            message: ''
        };
    }


    /* =========================================================
       EMAIL VALIDATION
    ========================================================= */

    function validateEmailAddress(email) {

        email = String(email || '').trim();


        if (!email) {

            return {
                valid: false,
                message: 'Email address is required.'
            };

        }


        if (!email.includes('@')) {

            return {
                valid: false,
                message:
                    'Please enter a valid email address containing @.'
            };

        }


        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (!emailPattern.test(email)) {

            return {
                valid: false,
                message:
                    'Please enter a valid email address.'
            };

        }


        return {
            valid: true,
            message: ''
        };
    }


    /* =========================================================
       PHONE VALIDATION
    ========================================================= */

    function validatePhoneNumber(phone) {

        phone = String(phone || '').trim();


        if (!/^[0-9]{9}$/.test(phone)) {

            return {
                valid: false,
                message:
                    'Contact number must contain exactly 9 numeric digits after the country code.'
            };

        }


        return {
            valid: true,
            message: ''
        };
    }


    /* =========================================================
       DOM READY
    ========================================================= */

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            const form =
                document.getElementById('editUserForm');

            const epfInput =
                document.getElementById('epf_number');

            const epfMessage =
                document.getElementById(
                    'epfValidationMessage'
                );

            const emailInput =
                document.getElementById('email');

            const emailMessage =
                document.getElementById(
                    'emailValidationMessage'
                );

            const countryCode =
                document.getElementById('country_code');

            const phoneInput =
                document.getElementById('phone_number');

            const phoneCounter =
                document.getElementById('phoneCounter');

            const phoneMessage =
                document.getElementById(
                    'phoneValidationMessage'
                );


            /* =================================================
               EPF LIVE VALIDATION
            ================================================= */

            function updateEpfValidation() {

                if (!epfInput || !epfMessage) {
                    return true;
                }


                epfInput.value =
                    epfInput.value.replace(
                        /[^0-9]/g,
                        ''
                    ).slice(0, 5);


                const result =
                    validateEpfNumber(
                        epfInput.value
                    );


                if (!epfInput.value) {

                    epfMessage.textContent = '';
                    epfMessage.className =
                        'epf-validation-message';

                    epfInput.classList.remove(
                        'is-valid',
                        'is-invalid'
                    );

                    return false;
                }


                if (result.valid) {

                    epfMessage.textContent =
                        'Valid 5-digit EPF number.';

                    epfMessage.className =
                        'epf-validation-message show valid';

                    epfInput.classList.remove(
                        'is-invalid'
                    );

                    epfInput.classList.add(
                        'is-valid'
                    );

                    return true;

                }


                epfMessage.textContent =
                    result.message;

                epfMessage.className =
                    'epf-validation-message show invalid';

                epfInput.classList.remove(
                    'is-valid'
                );

                epfInput.classList.add(
                    'is-invalid'
                );

                return false;
            }


            if (epfInput) {

                epfInput.addEventListener(
                    'input',
                    updateEpfValidation
                );

                epfInput.addEventListener(
                    'blur',
                    updateEpfValidation
                );

                updateEpfValidation();
            }


            /* =================================================
               EMAIL LIVE VALIDATION
            ================================================= */

            function updateEmailValidation() {

                if (!emailInput || !emailMessage) {
                    return true;
                }


                const result =
                    validateEmailAddress(
                        emailInput.value
                    );


                if (!emailInput.value.trim()) {

                    emailMessage.textContent = '';

                    emailMessage.className =
                        'email-validation-message';

                    emailInput.classList.remove(
                        'is-valid',
                        'is-invalid'
                    );

                    return false;
                }


                if (result.valid) {

                    emailMessage.textContent = '';

                    emailMessage.className =
                        'email-validation-message';

                    emailInput.classList.remove(
                        'is-invalid'
                    );

                    emailInput.classList.add(
                        'is-valid'
                    );

                    return true;

                }


                emailMessage.textContent =
                    result.message;

                emailMessage.className =
                    'email-validation-message show';

                emailInput.classList.remove(
                    'is-valid'
                );

                emailInput.classList.add(
                    'is-invalid'
                );

                return false;
            }


            if (emailInput) {

                emailInput.addEventListener(
                    'input',
                    updateEmailValidation
                );

                emailInput.addEventListener(
                    'blur',
                    updateEmailValidation
                );

                updateEmailValidation();
            }


            /* =================================================
               PHONE COUNTER + LIVE VALIDATION
            ================================================= */

            function updatePhoneValidation() {

                if (!phoneInput) {
                    return false;
                }


                phoneInput.value =
                    phoneInput.value.replace(
                        /[^0-9]/g,
                        ''
                    ).slice(0, 9);


                const length =
                    phoneInput.value.length;


                if (phoneCounter) {

                    phoneCounter.textContent =
                        length + '/9';

                    phoneCounter.classList.remove(
                        'complete',
                        'invalid'
                    );


                    if (length === 9) {

                        phoneCounter.classList.add(
                            'complete'
                        );

                    } else if (length > 0) {

                        phoneCounter.classList.add(
                            'invalid'
                        );

                    }

                }


                const result =
                    validatePhoneNumber(
                        phoneInput.value
                    );


                if (!phoneInput.value) {

                    phoneInput.classList.remove(
                        'is-valid',
                        'is-invalid'
                    );

                    if (phoneMessage) {

                        phoneMessage.textContent =
                            '';

                        phoneMessage.className =
                            'phone-validation-message';

                    }

                    return false;
                }


                if (result.valid) {

                    phoneInput.classList.remove(
                        'is-invalid'
                    );

                    phoneInput.classList.add(
                        'is-valid'
                    );

                    if (phoneMessage) {

                        phoneMessage.textContent =
                            '';

                        phoneMessage.className =
                            'phone-validation-message';

                    }

                    return true;

                }


                phoneInput.classList.remove(
                    'is-valid'
                );

                phoneInput.classList.add(
                    'is-invalid'
                );


                if (phoneMessage) {

                    phoneMessage.textContent =
                        result.message;

                    phoneMessage.className =
                        'phone-validation-message show';

                }


                return false;
            }


            if (phoneInput) {

                phoneInput.addEventListener(
                    'input',
                    updatePhoneValidation
                );

                phoneInput.addEventListener(
                    'blur',
                    updatePhoneValidation
                );

                updatePhoneValidation();
            }


            /* =================================================
               PROFILE PICTURE
            ================================================= */

            const fileInput =
                document.getElementById(
                    'profile_picture'
                );

            const preview =
                document.getElementById(
                    'profilePicturePreview'
                );

            const defaultImage =
                document.getElementById(
                    'profilePictureDefault'
                );

            const fileName =
                document.getElementById(
                    'profileFileName'
                );

            const removeButton =
                document.getElementById(
                    'removeProfilePicture'
                );

            const removeInput =
                document.getElementById(
                    'remove_profile_picture'
                );


            /* =================================================
               FILE SELECTION
            ================================================= */

            if (fileInput) {

                fileInput.addEventListener(
                    'change',
                    function () {

                        const file =
                            this.files[0];

                        if (!file) {
                            return;
                        }


                        if (
                            !file.type.match(
                                /^image\/(jpeg|png|webp)$/
                            )
                        ) {

                            alert(
                                'Please select a JPG, JPEG, PNG or WEBP image.'
                            );

                            this.value = '';

                            return;
                        }


                        if (
                            file.size >
                            2 * 1024 * 1024
                        ) {

                            alert(
                                'The selected profile picture is larger than 2 MB.'
                            );

                            this.value = '';

                            return;
                        }


                        if (fileName) {

                            fileName.textContent =
                                file.name;

                        }


                        const reader =
                            new FileReader();

                        reader.onload =
                            function (event) {

                                if (preview) {

                                    preview.src =
                                        event.target.result;

                                    preview.classList.remove(
                                        'd-none'
                                    );

                                }

                                if (defaultImage) {

                                    defaultImage.classList.add(
                                        'd-none'
                                    );

                                }

                            };

                        reader.readAsDataURL(file);


                        if (removeInput) {

                            removeInput.value = '0';

                        }


                        if (removeButton) {

                            removeButton.classList.remove(
                                'active'
                            );

                            removeButton.innerHTML =
                                '<i class="bi bi-trash3"></i> Remove Picture';

                        }

                    }
                );

            }


            /* =================================================
               REMOVE / KEEP PROFILE PICTURE
            ================================================= */

            if (removeButton) {

                removeButton.addEventListener(
                    'click',
                    function () {

                        if (!removeInput) {
                            return;
                        }


                        const removing =
                            removeInput.value === '0';


                        if (removing) {

                            removeInput.value = '1';


                            if (preview) {

                                preview.src = '';

                                preview.classList.add(
                                    'd-none'
                                );

                            }


                            if (defaultImage) {

                                defaultImage.classList.remove(
                                    'd-none'
                                );

                            }


                            if (fileInput) {

                                fileInput.value = '';

                            }


                            if (fileName) {

                                fileName.textContent =
                                    'Profile picture will be removed when you save.';

                            }


                            this.classList.add(
                                'active'
                            );

                            this.innerHTML =
                                '<i class="bi bi-arrow-counterclockwise"></i> Keep Current Picture';

                        } else {

                            removeInput.value = '0';


                            if (preview) {

                                preview.classList.remove(
                                    'd-none'
                                );

                                @if($user->profile_picture)

                                    preview.src =
                                        "{{ asset('storage/' . $user->profile_picture) }}";

                                @endif

                            }


                            if (defaultImage) {

                                defaultImage.classList.add(
                                    'd-none'
                                );

                            }


                            if (fileName) {

                                fileName.textContent =
                                    'Current profile picture';

                            }


                            this.classList.remove(
                                'active'
                            );

                            this.innerHTML =
                                '<i class="bi bi-trash3"></i> Remove Picture';

                        }

                    }
                );

            }


            /* =================================================
               FINAL FORM VALIDATION
            ================================================= */

            if (form) {

                form.addEventListener(
                    'submit',
                    function (event) {

                        const epfValid =
                            updateEpfValidation();

                        const emailValid =
                            updateEmailValidation();

                        const phoneValid =
                            updatePhoneValidation();


                        if (!countryCode || !countryCode.value) {

                            event.preventDefault();

                            alert(
                                'Please select a country code.'
                            );

                            if (countryCode) {
                                countryCode.focus();
                            }

                            return;
                        }


                        if (
                            !epfValid ||
                            !emailValid ||
                            !phoneValid
                        ) {

                            event.preventDefault();


                            if (!epfValid && epfInput) {

                                epfInput.focus();

                                return;

                            }


                            if (!emailValid && emailInput) {

                                emailInput.focus();

                                return;

                            }


                            if (!phoneValid && phoneInput) {

                                phoneInput.focus();

                                return;

                            }

                        }

                    }
                );

            }

        }
    );

</script>

@endsection
