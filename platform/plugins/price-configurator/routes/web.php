<?php

use Illuminate\Support\Facades\Route;
use Botble\PriceConfigurator\Http\Controllers\AdminController;
use Botble\PriceConfigurator\Http\Controllers\CategoryController;
use Botble\PriceConfigurator\Http\Controllers\TierController;
use Botble\PriceConfigurator\Http\Controllers\RuleController;

Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => ['web', 'core']], function () {
    Route::get('price-configurator', [AdminController::class, 'index'])->name('pc.admin.index');
    Route::post('price-configurator/set-customer-category', [AdminController::class, 'setCustomerCategory'])->name('pc.admin.setCustomerCategory');

    Route::get('price-configurator/categories', [CategoryController::class, 'index'])->name('pc.categories.index');
    Route::get('price-configurator/categories/create', [CategoryController::class, 'create'])->name('pc.categories.create');
    Route::post('price-configurator/categories', [CategoryController::class, 'store'])->name('pc.categories.store');
    Route::get('price-configurator/categories/{id}/edit', [CategoryController::class, 'edit'])->name('pc.categories.edit');
    Route::put('price-configurator/categories/{id}', [CategoryController::class, 'update'])->name('pc.categories.update');
    Route::delete('price-configurator/categories/{id}', [CategoryController::class, 'destroy'])->name('pc.categories.destroy');
    Route::post('price-configurator/categories/{id}/toggle', [CategoryController::class, 'toggle'])->name('pc.categories.toggle');

    Route::get('price-configurator/tiers', [TierController::class, 'index'])->name('pc.tiers.index');
    Route::get('price-configurator/tiers/create', [TierController::class, 'create'])->name('pc.tiers.create');
    Route::post('price-configurator/tiers', [TierController::class, 'store'])->name('pc.tiers.store');
    Route::get('price-configurator/tiers/{id}/edit', [TierController::class, 'edit'])->name('pc.tiers.edit');
    Route::put('price-configurator/tiers/{id}', [TierController::class, 'update'])->name('pc.tiers.update');
    Route::delete('price-configurator/tiers/{id}', [TierController::class, 'destroy'])->name('pc.tiers.destroy');
    Route::post('price-configurator/tiers/{id}/toggle', [TierController::class, 'toggle'])->name('pc.tiers.toggle');

    Route::get('price-configurator/rules', [RuleController::class, 'index'])->name('pc.rules.index');
    Route::get('price-configurator/rules/create', [RuleController::class, 'create'])->name('pc.rules.create');
    Route::post('price-configurator/rules', [RuleController::class, 'store'])->name('pc.rules.store');
    Route::get('price-configurator/rules/{id}/edit', [RuleController::class, 'edit'])->name('pc.rules.edit');
    Route::put('price-configurator/rules/{id}', [RuleController::class, 'update'])->name('pc.rules.update');
    Route::delete('price-configurator/rules/{id}', [RuleController::class, 'destroy'])->name('pc.rules.destroy');
    Route::post('price-configurator/rules/{id}/toggle', [RuleController::class, 'toggle'])->name('pc.rules.toggle');
});
