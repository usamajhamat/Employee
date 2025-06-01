<?php
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NetworkInfoController;
use App\Http\Controllers\TanazaApiController;
use App\Http\Controllers\TanazaController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuditController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\BankTransactionController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerPaymentController;
use App\Http\Controllers\Api\DirectEntryController;
use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\FeedDoseController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\SupplierPaymentController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\WeightController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::group(['middleware' => 'api', 'prefix' => 'v1'], function () {
    Route::get('user/', function (Request $request) {
        return $request->user();
    });

    Route::get('uploads', [FileUploadController::class, 'index']);
    Route::post('uploads', [FileUploadController::class, 'store']);
    Route::delete('uploads', [FileUploadController::class, 'destroy']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories', [CategoryController::class, 'destroy']);

    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products', [ProductController::class, 'destroy']);

    Route::get('banks', [BankController::class, 'index']);
    Route::post('banks', [BankController::class, 'store']);
    Route::put('banks/{id}', [BankController::class, 'update']);
    Route::delete('banks', [BankController::class, 'destroy']);

    Route::get('bank-transactions', [BankTransactionController::class, 'index']);
    Route::post('bank-transactions', [BankTransactionController::class, 'store']);
    Route::delete('bank-transactions', [BankTransactionController::class, 'destroy']);

    Route::get('units', [UnitController::class, 'index']);
    Route::post('units', [UnitController::class, 'store']);
    Route::put('units/{id}', [UnitController::class, 'update']);
    Route::delete('units', [UnitController::class, 'destroy']);

    Route::get('audits', [AuditController::class, 'index']);
    Route::post('audits', [AuditController::class, 'store']);
    Route::delete('audits', [AuditController::class, 'destroy']);

    Route::get('customers', [CustomerController::class, 'index']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::put('customers/{id}', [CustomerController::class, 'update']);
    Route::delete('customers', [CustomerController::class, 'destroy']);

    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::post('suppliers', [SupplierController::class, 'store']);
    Route::put('suppliers/{id}', [SupplierController::class, 'update']);
    Route::delete('suppliers', [SupplierController::class, 'destroy']);

    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
    Route::post('payment-methods', [PaymentMethodController::class, 'store']);
    Route::put('payment-methods/{id}', [PaymentMethodController::class, 'update']);
    Route::delete('payment-methods', [PaymentMethodController::class, 'destroy']);

    Route::get('sales', [SaleController::class, 'index']);
    Route::post('sales', [SaleController::class, 'store']);
    Route::get('sales/{id}', [SaleController::class, 'show']);
    Route::put('sales/{id}', [SaleController::class, 'update']);
    Route::delete('sales', [SaleController::class, 'destroy']);

    Route::get('purchases', [PurchaseController::class, 'index']);
    Route::post('purchases', [PurchaseController::class, 'store']);
    Route::put('purchases/{id}', [PurchaseController::class, 'update']);
    Route::delete('purchases', [PurchaseController::class, 'destroy']);

    Route::get('cart-items', [CartController::class, 'index']);
    Route::post('cart-items', [CartController::class, 'store']);
    Route::put('cart-items/{id}', [CartController::class, 'update']);
    Route::delete('cart-items', [CartController::class, 'destroy']);

    Route::get('customer-payments', [CustomerPaymentController::class, 'index']);
    Route::post('customer-payments', [CustomerPaymentController::class, 'store']);
    Route::delete('customer-payments', [CustomerPaymentController::class, 'destroy']);

    Route::get('supplier-payments', [SupplierPaymentController::class, 'index']);
    Route::post('supplier-payments', [SupplierPaymentController::class, 'store']);
    Route::delete('supplier-payments', [SupplierPaymentController::class, 'destroy']);

    Route::get('expenses', [ExpenseController::class, 'index']);
    Route::post('expenses', [ExpenseController::class, 'store']);
    Route::put('expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('expenses', [ExpenseController::class, 'destroy']);

    Route::get('expense-categories', [ExpenseCategoryController::class, 'index']);
    Route::post('expense-categories', [ExpenseCategoryController::class, 'store']);
    Route::put('expense-categories/{id}', [ExpenseCategoryController::class, 'update']);
    Route::delete('expense-categories', [ExpenseCategoryController::class, 'destroy']);

    Route::post('direct-entries', [DirectEntryController::class, 'store']);

    Route::get('feeds', [FeedController::class, 'index']);
    Route::post('feeds', [FeedController::class, 'store']);
    Route::put('feeds/{id}', [FeedController::class, 'update']);
    Route::delete('feeds', [FeedController::class, 'destroy']);
    Route::get('feedConsumption', [FeedController::class, 'feedConsumption']);

    Route::get('animals', [AnimalController::class, 'index']);
    Route::get('animal-stats', [AnimalController::class, 'totalAnimalsStats']);
    Route::post('animals', [AnimalController::class, 'store']);
    Route::delete('animals', [AnimalController::class, 'destroy']);

    Route::post('weights', [WeightController::class, 'store']);
    Route::get('weights', [WeightController::class, 'index']);

    Route::post('feedDoses', [FeedDoseController::class, 'store']);
    Route::get('feedDoses', [FeedDoseController::class, 'index']);

    Route::get('/tanaza/total-users', [TanazaApiController::class, 'getTotalUsers']);
    Route::get('/tanaza/get-customers', [TanazaApiController::class, 'getCustomers']);
    Route::get('/tanaza/account', [TanazaApiController::class, 'getAccountInfo']);


    Route::post('tanaza-account', [NetworkInfoController::class, 'store']);
    Route::post('save-tanaza-network', [NetworkInfoController::class, 'storeNetwork']);



    Route::post('save-employee', [EmployeeController::class, 'store']);
    Route::get('get-employees', [EmployeeController::class, 'show']);
    Route::delete('delete-employee', [EmployeeController::class, 'destroy']);
    Route::post('update-employee', [EmployeeController::class, 'update']);
    Route::get('get-employee-details', [EmployeeController::class, 'getEmployeeDetails']);


    Route::post('save-company', [CompanyController::class, 'store']);
    Route::get('get-compnies', [CompanyController::class, 'show']);
    Route::delete('delete-company', [CompanyController::class, 'destroy']);
    Route::get('get-compny-details', [CompanyController::class, 'getCompanyDetails']);
    Route::post('update-company', [CompanyController::class, 'update']);

    Route::get('get-dashboard-analytics', [DashboardAnalyticsController::class, 'index']);









});