
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\MainDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\FaultReportController;

use App\Http\Controllers\Admin\ItemTypeController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\MakeController;
use App\Http\Controllers\Admin\ItemModelController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FunctionUnitController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\CpcMasterDataController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EmployeeLoginController;
use App\Http\Controllers\Admin\ItemAssignmentController;

use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\RepairController;
use App\Http\Controllers\Admin\RepairSparePartController;
use App\Http\Controllers\Admin\SparePartSupplierController;
use App\Http\Controllers\Admin\RepairCollectionController;
use App\Http\Controllers\Admin\RepairAccessoryController;
use App\Http\Controllers\Admin\RddController;

use App\Http\Controllers\Admin\TemporaryItemIssueController;
use App\Http\Controllers\Admin\TemporaryItemIssueHistoryController;
use App\Http\Controllers\Admin\Inventory\TemporaryItemController;
use App\Http\Controllers\Admin\ReportController;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route('main.dashboard');
})->name('home');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'showLoginForm']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'login']
    );

    Route::get(
        '/forgot-password',
        [ForgotPasswordController::class, 'showLinkRequestForm']
    )->name('password.request');

    Route::post(
        '/forgot-password',
        [ForgotPasswordController::class, 'sendResetCode']
    )->name('password.email');

    Route::post(
        '/forgot-password/verify',
        [ForgotPasswordController::class, 'verifyCode']
    )->name('password.verify');

    Route::get(
        '/reset-password',
        [ForgotPasswordController::class, 'showResetForm']
    )->name('password.reset.phone');

    Route::post(
        '/reset-password',
        [ForgotPasswordController::class, 'resetPassword']
    )->name('password.update');
});


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {

    auth()->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');

})->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| Main Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/main-dashboard',
    [MainDashboardController::class, 'index']
)->middleware('auth')->name('main.dashboard');


/*
|--------------------------------------------------------------------------
| Main IT Function Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| Employee / User Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/employee-dashboard',
    [EmployeeDashboardController::class, 'index']
)->middleware('auth')->name('employee.dashboard');


/*
|--------------------------------------------------------------------------
| Fault Reporting
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/fault-reports',
        [FaultReportController::class, 'index']
    )->name('fault-reports.index');

    Route::get(
        '/fault-reports/create',
        [FaultReportController::class, 'create']
    )->name('fault-reports.create');

    Route::post(
        '/fault-reports',
        [FaultReportController::class, 'store']
    )->name('fault-reports.store');

    Route::get(
        '/fault-reports/{faultReport}',
        [FaultReportController::class, 'show']
    )->whereNumber('faultReport')
     ->name('fault-reports.show');
});


/*
|--------------------------------------------------------------------------
| Main Administration / Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'auth',
        'role:admin,employee,super_admin',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Super Admin — Employee Management
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:super_admin')->group(function () {

            Route::get(
                'employees/create',
                [EmployeeController::class, 'create']
            )->name('employees.create');

            Route::post(
                'employees',
                [EmployeeController::class, 'store']
            )->name('employees.store');

            Route::get(
                'employees/{employee}/edit',
                [EmployeeController::class, 'edit']
            )->name('employees.edit');

            Route::put(
                'employees/{employee}',
                [EmployeeController::class, 'update']
            )->name('employees.update');

            Route::patch(
                'employees/{employee}',
                [EmployeeController::class, 'update']
            )->name('employees.update.patch');

            Route::delete(
                'employees/{employee}',
                [EmployeeController::class, 'destroy']
            )->name('employees.destroy');


            /*
            |--------------------------------------------------------------------------
            | Super Admin — Inventory Item Management
            |--------------------------------------------------------------------------
            */

            Route::get(
                'items/create',
                [ItemController::class, 'create']
            )->name('items.create');

            Route::post(
                'items',
                [ItemController::class, 'store']
            )->name('items.store');

            Route::get(
                'items/{item}/edit',
                [ItemController::class, 'edit']
            )->name('items.edit');

            Route::put(
                'items/{item}',
                [ItemController::class, 'update']
            )->name('items.update');

            Route::patch(
                'items/{item}',
                [ItemController::class, 'update']
            )->name('items.update.patch');

            Route::delete(
                'items/{item}',
                [ItemController::class, 'destroy']
            )->name('items.destroy');


            /*
            |--------------------------------------------------------------------------
            | Make Management
            |--------------------------------------------------------------------------
            */

            Route::resource('makes', MakeController::class);


            /*
            |--------------------------------------------------------------------------
            | Item Model Management
            |--------------------------------------------------------------------------
            */

            Route::resource('item-models', ItemModelController::class);
        });


        /*
        |--------------------------------------------------------------------------
        | Inventory Items — View / Search
        |--------------------------------------------------------------------------
        */

        Route::get(
            'items',
            [ItemController::class, 'index']
        )->name('items.index');

        Route::get(
            'items/{item}',
            [ItemController::class, 'show']
        )->name('items.show');


        /*
        |--------------------------------------------------------------------------
        | Employees — View / Search
        |--------------------------------------------------------------------------
        */

        Route::get(
            'employees',
            [EmployeeController::class, 'index']
        )->name('employees.index');

        Route::get(
            'employees/{employee}',
            [EmployeeController::class, 'show']
        )->name('employees.show');


        /*
        |--------------------------------------------------------------------------
        | CPC HAPIS Master Data — EPF Lookup
        |--------------------------------------------------------------------------
        | Read-only lookup from the local CPC master cache.
        | Branch restrictions are enforced by the controller.
        */

        Route::get(
            'cpc-master/employee-by-epf',
            [CpcMasterDataController::class, 'employeeByEpf']
        )->name('cpc-master.employee-by-epf');


        /*
        |--------------------------------------------------------------------------
        | Item Types
        |--------------------------------------------------------------------------
        */

        Route::resource('item-types', ItemTypeController::class);


        /*
        |--------------------------------------------------------------------------
        | Employee → Inventory Item Assignment
        |--------------------------------------------------------------------------
        */

        Route::get(
            'employees/{employee}/assign-item',
            [ItemAssignmentController::class, 'create']
        )->name('employees.assign-item');

        Route::post(
            'employees/{employee}/assign-item',
            [ItemAssignmentController::class, 'store']
        )->name('employees.assign-item.store');

        Route::put(
            'employees/{employee}/assignments/{itemAssignment}/return',
            [ItemAssignmentController::class, 'returnItem']
        )->name('employees.assignments.return');


        /*
        |--------------------------------------------------------------------------
        | User / Login Account Management — Super Admin Only
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:super_admin')->group(function () {

            /*
            |--------------------------------------------------------------------------
            | EPF-Based Employee Login Management
            |--------------------------------------------------------------------------
            | GET  - Search for an employee by EPF and show login status.
            | POST - Create a login if the employee has no existing login.
            | PUT  - Update the email and/or password of an existing login.
            */

            Route::get(
                'users/create-employee-login',
                [EmployeeLoginController::class, 'create']
            )->name('users.create-employee-login');

            Route::post(
                'users/create-employee-login',
                [EmployeeLoginController::class, 'store']
            )->name('users.store-employee-login');

            Route::put(
                'users/update-employee-login',
                [EmployeeLoginController::class, 'update']
            )->name('users.update-employee-login');


            /*
            |--------------------------------------------------------------------------
            | Existing User Management Routes — Preserved
            |--------------------------------------------------------------------------
            */

            Route::resource('users', UserController::class);

            Route::post(
                'users/{user}/lock',
                [UserController::class, 'lock']
            )->name('users.lock');

            Route::post(
                'users/{user}/unlock',
                [UserController::class, 'unlock']
            )->name('users.unlock');
        });


        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */

      Route::get('branches', [BranchController::class, 'index'])
    ->name('branches.index');

Route::get('branches/{locationId}', [BranchController::class, 'show'])
    ->whereNumber('locationId')
    ->name('branches.show');
        Route::resource('departments', DepartmentController::class);
        Route::resource('function-units', FunctionUnitController::class);
    });


/*
|--------------------------------------------------------------------------
| Inventory Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/inventory')
    ->name('admin.inventory.')
    ->middleware([
        'auth',
        'role:admin,employee,super_admin',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Temporary Item Details
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'temporary-items',
            TemporaryItemController::class
        )->names('temporary-items');


        /*
        |--------------------------------------------------------------------------
        | Spare Parts Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('repairs/spare-parts')
            ->name('repairs.spare-parts.')
            ->group(function () {

                Route::get('/', [SparePartController::class, 'index'])
                    ->name('index');

                Route::get('/create', [SparePartController::class, 'create'])
                    ->name('create');

                Route::post('/', [SparePartController::class, 'store'])
                    ->name('store');

                Route::get('/{sparePart}', [SparePartController::class, 'show'])
                    ->name('show');

                Route::get('/{sparePart}/edit', [SparePartController::class, 'edit'])
                    ->name('edit');

                Route::put('/{sparePart}', [SparePartController::class, 'update'])
                    ->name('update');

                Route::delete('/{sparePart}', [SparePartController::class, 'destroy'])
                    ->name('destroy');
            });


        /*
        |--------------------------------------------------------------------------
        | Supplier Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('repairs/suppliers')
            ->name('repairs.suppliers.')
            ->group(function () {

                Route::get('/', [SparePartSupplierController::class, 'index'])
                    ->name('index');

                Route::get('/create', [SparePartSupplierController::class, 'create'])
                    ->name('create');

                Route::post('/', [SparePartSupplierController::class, 'store'])
                    ->name('store');

                Route::put(
                    '/{supplier}/documents/{document}',
                    [SparePartSupplierController::class, 'updateDocument']
                )->name('documents.update');

                Route::delete(
                    '/{supplier}/documents/{document}',
                    [SparePartSupplierController::class, 'destroyDocument']
                )->name('documents.destroy');

                Route::get('/{supplier}/edit', [SparePartSupplierController::class, 'edit'])
                    ->name('edit');

                Route::get('/{supplier}', [SparePartSupplierController::class, 'show'])
                    ->name('show');

                Route::put('/{supplier}', [SparePartSupplierController::class, 'update'])
                    ->name('update');

                Route::delete('/{supplier}', [SparePartSupplierController::class, 'destroy'])
                    ->name('destroy');

                Route::patch(
                    '/{supplier}/activate',
                    [SparePartSupplierController::class, 'activate']
                )->name('activate');

                Route::patch(
                    '/{supplier}/deactivate',
                    [SparePartSupplierController::class, 'deactivate']
                )->name('deactivate');
            });


        /*
        |--------------------------------------------------------------------------
        | Repair → Spare Part Usage
        |--------------------------------------------------------------------------
        */

        Route::post(
            'repairs/{repair}/spare-parts',
            [RepairSparePartController::class, 'store']
        )->name('repairs.repair-spare-parts.store');

        Route::put(
            'repairs/{repair}/spare-parts/{repairSparePart}',
            [RepairSparePartController::class, 'update']
        )->name('repairs.repair-spare-parts.update');

        Route::delete(
            'repairs/{repair}/spare-parts/{repairSparePart}',
            [RepairSparePartController::class, 'destroy']
        )->name('repairs.repair-spare-parts.destroy');


        /*
        |--------------------------------------------------------------------------
        | Repair → Repaired Item Collection
        |--------------------------------------------------------------------------
        */

        Route::post(
            'repairs/{repair}/collection',
            [RepairCollectionController::class, 'store']
        )->name('repairs.collection.store');

        Route::delete(
            'repairs/{repair}/collection',
            [RepairCollectionController::class, 'destroy']
        )->name('repairs.collection.destroy');


        /*
        |--------------------------------------------------------------------------
        | Repair → Accessories
        |--------------------------------------------------------------------------
        */

        Route::post(
            'repairs/{repair}/accessories',
            [RepairAccessoryController::class, 'store']
        )->name('repairs.accessories.store');

        Route::put(
            'repairs/{repair}/accessories/{accessory}',
            [RepairAccessoryController::class, 'update']
        )->name('repairs.accessories.update');

        Route::delete(
            'repairs/{repair}/accessories/{accessory}',
            [RepairAccessoryController::class, 'destroy']
        )->name('repairs.accessories.destroy');

        Route::put(
            'repairs/{repair}/accessories/{accessory}/return',
            [RepairAccessoryController::class, 'returnAccessory']
        )->name('repairs.accessories.return');


        /*
        |--------------------------------------------------------------------------
        | Repair Management
        |--------------------------------------------------------------------------
        */

        Route::resource('repairs', RepairController::class);


        /*
        |--------------------------------------------------------------------------
        | RDD Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('rdd')
            ->name('rdd.')
            ->group(function () {

                Route::get('/', [RddController::class, 'index'])
                    ->name('index');

                Route::get('/create', [RddController::class, 'create'])
                    ->name('create');

                Route::post('/', [RddController::class, 'store'])
                    ->name('store');

                Route::patch('/{rdd}/status', [RddController::class, 'updateStatus'])
                    ->name('status');

                Route::get('/{rdd}/history', [RddController::class, 'history'])
                    ->name('history');

                Route::get('/{rdd}', [RddController::class, 'show'])
                    ->name('show');

                Route::get('/{rdd}/edit', [RddController::class, 'edit'])
                    ->name('edit');

                Route::put('/{rdd}', [RddController::class, 'update'])
                    ->name('update');

                Route::delete('/{rdd}', [RddController::class, 'destroy'])
                    ->name('destroy');
            });
    });


/*
|--------------------------------------------------------------------------
| Temporary Item Issue Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/temporary-item-issues')
    ->name('admin.temporary-item-issues.')
    ->middleware([
        'auth',
        'role:admin,employee,super_admin',
    ])
    ->group(function () {

        Route::get(
            '/history',
            [TemporaryItemIssueHistoryController::class, 'index']
        )->name('history.index');

        Route::get(
            '/{temporaryItemIssue}/history',
            [TemporaryItemIssueHistoryController::class, 'show']
        )->name('history.show');

        Route::patch(
            '/{temporaryItemIssue}/receive',
            [TemporaryItemIssueController::class, 'receive']
        )->name('receive');

        Route::patch(
            '/{temporaryItemIssue}/cancel',
            [TemporaryItemIssueController::class, 'cancel']
        )->name('cancel');

        Route::get('/', [TemporaryItemIssueController::class, 'index'])
            ->name('index');

        Route::get('/create', [TemporaryItemIssueController::class, 'create'])
            ->name('create');

        Route::post('/', [TemporaryItemIssueController::class, 'store'])
            ->name('store');

        Route::get('/{temporaryItemIssue}', [TemporaryItemIssueController::class, 'show'])
            ->name('show');

        Route::get('/{temporaryItemIssue}/edit', [TemporaryItemIssueController::class, 'edit'])
            ->name('edit');

        Route::put('/{temporaryItemIssue}', [TemporaryItemIssueController::class, 'update'])
            ->name('update');

        Route::delete('/{temporaryItemIssue}', [TemporaryItemIssueController::class, 'destroy'])
            ->name('destroy');
    });


/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::prefix('admin/reports')
    ->name('admin.reports.')
    ->middleware([
        'auth',
        'role:admin,employee,super_admin',
    ])
    ->group(function () {

        Route::get('/', [ReportController::class, 'index'])
            ->name('index');

        Route::get('/inventory/summary', [ReportController::class, 'inventorySummary'])
            ->name('inventory.summary');

        Route::get('/inventory/print', [ReportController::class, 'inventoryPrint'])
            ->name('inventory.print');

        Route::get('/inventory/pdf', [ReportController::class, 'inventoryPdf'])
            ->name('inventory.pdf');

        Route::get('/inventory/excel', [ReportController::class, 'inventoryExcel'])
            ->name('inventory.excel');


        Route::get('/assignments', [ReportController::class, 'assignmentReports'])
            ->name('assignments.index');

        Route::get('/assignments/print', [ReportController::class, 'assignmentPrint'])
            ->name('assignments.print');

        Route::get('/assignments/pdf', [ReportController::class, 'assignmentPdf'])
            ->name('assignments.pdf');

        Route::get('/assignments/excel', [ReportController::class, 'assignmentExcel'])
            ->name('assignments.excel');


        Route::get('/repairs', [ReportController::class, 'repairReports'])
            ->name('repairs.index');

        Route::get('/repairs/print', [ReportController::class, 'repairPrint'])
            ->name('repairs.print');

        Route::get('/repairs/pdf', [ReportController::class, 'repairPdf'])
            ->name('repairs.pdf');

        Route::get('/repairs/excel', [ReportController::class, 'repairExcel'])
            ->name('repairs.excel');


        Route::get('/rdd', [ReportController::class, 'rddReports'])
            ->name('rdd.index');

        Route::get('/rdd/print', [ReportController::class, 'rddPrint'])
            ->name('rdd.print');

        Route::get('/rdd/pdf', [ReportController::class, 'rddPdf'])
            ->name('rdd.pdf');

        Route::get('/rdd/excel', [ReportController::class, 'rddExcel'])
            ->name('rdd.excel');


        Route::get('/temporary-items', [ReportController::class, 'temporaryItemReports'])
            ->name('temporary-items.index');

        Route::get('/temporary-items/print', [ReportController::class, 'temporaryItemPrint'])
            ->name('temporary-items.print');

        Route::get('/temporary-items/pdf', [ReportController::class, 'temporaryItemPdf'])
            ->name('temporary-items.pdf');

        Route::get('/temporary-items/excel', [ReportController::class, 'temporaryItemExcel'])
            ->name('temporary-items.excel');
    });
