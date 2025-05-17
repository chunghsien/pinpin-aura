<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\ShopCartController;
use App\Http\Controllers\ShopCategoryController;
use App\Http\Controllers\ShopCheckoutController;
use App\Http\Controllers\ShopProductController;
use App\Http\Controllers\ShopWishlistController;
use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

Route::prefix('{lang}/admin')->group(function () {
    Route::get('/{any?}', [AdminIndexController::class, 'query'])
        ->where('any', '.*')
        ->name('admin');
});

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        $directories = collect(File::directories(base_path('packages')))
            ->filter(function ($dir) {
                return basename($dir) !== 'core-ui-admin';
            })
            ->values(); // 重建索引
        if ($directories->count() == 0) {
            return view('welcome');
        }
        $defaultLang = str_replace('_', '-', env('APP_LOCALE'));
        return Redirect::to("/{$defaultLang}");
    });
    Route::get('/{lang}', [IndexController::class, 'query'])->name('home');
    Route::get('/{lang}/about-us', [AboutUsController::class, 'query'])->name('about-us');
    Route::get('/{lang}/my-account', [MyAccountController::class, 'query'])->name('my-account');
    Route::get('/{lang}/{customSlug}', [CustomController::class, 'query'])->name('custom-page');

    Route::get('/{lang}/blog/category/{slug?}', [BlogCategoryController::class, 'query'])->name('blog-category');
    Route::get('/{lang}/blog/post/{slug?}', [BlogPostController::class, 'query'])->name('blog-post');
    Route::get('/{lang}/shop/cart', [ShopCartController::class, 'query'])->name('shop-cart');
    Route::get('/{lang}/shop/category/{slug?}', [ShopCategoryController::class, 'query'])->name('shop-category');
    Route::get('/{lang}/shop/checkout', [ShopCheckoutController::class, 'query'])->name('shop-checkout');
    Route::get('/{lang}/shop/product/{slug?}', [ShopProductController::class, 'query'])->name('shop-product');
    Route::get('/{lang}/shop/wishlist', [ShopWishlistController::class, 'query'])->name('shop-wishlist');
});
