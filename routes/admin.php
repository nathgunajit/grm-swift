<?php

use App\Http\Controllers\Admin\BeelController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\CpiuController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\GrievanceAdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RevenueCircleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserTypeController;
use App\Http\Controllers\Admin\ZoneController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Grievance queue + workflow (all authenticated roles; jurisdiction-scoped in controller)
    Route::get('grievances', [GrievanceAdminController::class, 'index'])->name('grievances.index');
    Route::get('grievances/create', [GrievanceAdminController::class, 'create'])
        ->middleware('role:beel_animator,bdc_facilitator,ssgc,dfdo,pmu_admin,super_admin')->name('grievances.create');
    Route::post('grievances', [GrievanceAdminController::class, 'store'])
        ->middleware('role:beel_animator,bdc_facilitator,ssgc,dfdo,pmu_admin,super_admin')->name('grievances.store');
    Route::get('grievances/{grievance}', [GrievanceAdminController::class, 'show'])->name('grievances.show');
    Route::post('grievances/{grievance}/review', [GrievanceAdminController::class, 'review'])->name('grievances.review');
    Route::post('grievances/{grievance}/comment', [GrievanceAdminController::class, 'comment'])->name('grievances.comment');
    Route::post('grievances/{grievance}/escalate', [GrievanceAdminController::class, 'escalate'])->name('grievances.escalate');
    Route::post('grievances/{grievance}/resolve', [GrievanceAdminController::class, 'resolve'])->name('grievances.resolve');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.csv');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');

    // Administration (Super Admin / PMU Admin only)
    Route::middleware('role:super_admin,pmu_admin')->group(function () {
        Route::resource('zones', ZoneController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('districts', DistrictController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('blocks', BlockController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('revenue-circles', RevenueCircleController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('cpius', CpiuController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('beels', BeelController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('user-types', UserTypeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/assign', [UserController::class, 'assign'])->name('users.assign');

        Route::resource('committees', CommitteeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('committees/{committee}/members', [CommitteeController::class, 'addMember'])->name('committees.members.add');
        Route::delete('committees/{committee}/members/{member}', [CommitteeController::class, 'removeMember'])->name('committees.members.remove');
    });
});
