<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\Checkout\NovaPostController;
use App\Http\Controllers\Front\LiqPayController;
use Basil832025\FrontendSevia\Http\Controllers\SeviaCatalogController;
use Basil832025\FrontendSevia\Http\Controllers\SeviaAccountController;
use Basil832025\FrontendSevia\Http\Controllers\SeviaCartController;
use App\Http\Controllers\Auth\ClientAuthController;
use Basil832025\FrontendSevia\Http\Controllers\SeviaProductController;
use Basil832025\FrontendSevia\Http\Controllers\SeviaProductReviewController;
use Basil832025\FrontendSevia\Http\Controllers\SeviaSearchController;
use Basil832025\FrontendSevia\Http\Controllers\SeviaTemplatePageController;

Route::get('/', [SeviaCatalogController::class, 'home'])->name('home');

Route::get('/discovery-53', [SeviaCatalogController::class, 'discovery'])->name('discovery-53');
Route::get('/discovery-53/count', [SeviaCatalogController::class, 'discoveryCount'])->name('discovery-53.count');
Route::get('/catalog', [SeviaCatalogController::class, 'index'])->name('catalog.index');
Route::get('/sale', [SeviaCatalogController::class, 'index'])->name('sale.index');
Route::get('/catalog/count', [SeviaCatalogController::class, 'count'])->name('catalog.count');
Route::get('/catalog/{category:slug}', [SeviaCatalogController::class, 'index'])->name('catalog.category');
Route::get('/cart', [SeviaCartController::class, 'page'])->name('cart.page');
Route::post('/cart/add', [SeviaCartController::class, 'add'])->name('cart.add');
Route::post('/cart/quantity', [SeviaCartController::class, 'quantity'])->name('cart.quantity');
Route::post('/cart/remove', [SeviaCartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/discovery-set/quantity', [SeviaCartController::class, 'discoverySetQuantity'])->name('cart.discovery-set.quantity');
Route::post('/cart/discovery-set/remove', [SeviaCartController::class, 'removeDiscoverySet'])->name('cart.discovery-set.remove');
Route::get('/cart/info', [SeviaCartController::class, 'info'])->name('cart.info');
Route::post('/favorites/toggle', [SeviaAccountController::class, 'toggleFavorite'])->name('favorites.toggle');

Route::get('/account', [SeviaAccountController::class, 'overview'])->name('account.overview');
Route::get('/account/orders', [SeviaAccountController::class, 'orders'])->name('account.orders');
Route::get('/account/orders/{order}', [SeviaAccountController::class, 'order'])->name('account.orders.show');
Route::post('/account/orders/{order}/repeat', [SeviaAccountController::class, 'repeatOrder'])->name('account.orders.repeat');
Route::get('/account/profile', [SeviaAccountController::class, 'profile'])->name('account.profile');
Route::get('/account/favorites', [SeviaAccountController::class, 'favorites'])->name('account.favorites');
Route::get('/account/profile/edit', [SeviaAccountController::class, 'editProfile'])->name('account.profile.edit');
Route::patch('/account/profile', [SeviaAccountController::class, 'updateProfile'])->name('account.profile.update');
Route::get('/account/address/edit', [SeviaAccountController::class, 'editAddress'])->name('account.address.edit');
Route::patch('/account/address', [SeviaAccountController::class, 'updateAddress'])->name('account.address.update');
Route::post('/account/logout', [SeviaAccountController::class, 'logout'])->name('account.logout');

Route::get('/auth', [ClientAuthController::class, 'show'])->name('auth.phone');
Route::get('/checkout', [ClientAuthController::class, 'checkout'])->name('checkout');
Route::post('/checkout/recipient', [ClientAuthController::class, 'saveCheckoutRecipient'])->name('checkout.recipient');
Route::post('/checkout/delivery', [ClientAuthController::class, 'saveCheckoutDelivery'])->name('checkout.delivery');
Route::post('/checkout/submit', [ClientAuthController::class, 'submitCheckout'])->name('checkout.submit');
Route::get('/checkout/{order}/pay/liqpay', [ClientAuthController::class, 'payLiqPay'])->name('checkout.pay.liqpay');
Route::post('/checkout/{order}/pay/liqpay/email', [ClientAuthController::class, 'saveLiqPayEmail'])->name('checkout.pay.liqpay.email');
Route::get('/checkout/success/{order}', [ClientAuthController::class, 'checkoutSuccess'])->name('checkout.success');
Route::post('/checkout/success/{order}/send-email', [ClientAuthController::class, 'sendOrderToEmail'])->name('checkout.success.send-email');
Route::post('/liqpay/callback', [LiqPayController::class, 'callback'])
    ->name('liqpay.callback')
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::get('/checkout/nova-post/cities', [NovaPostController::class, 'cities'])->name('checkout.nova-post.cities');
Route::get('/checkout/nova-post/warehouses', [NovaPostController::class, 'warehouses'])->name('checkout.nova-post.warehouses');
Route::get('/checkout/nova-post/streets', [NovaPostController::class, 'streets'])->name('checkout.nova-post.streets');
Route::get('/checkout/nova-post/delivery-prices', [NovaPostController::class, 'deliveryPrices'])->name('checkout.nova-post.delivery-prices');
Route::post('/auth/phone-sms/send-code', [ClientAuthController::class, 'loginPhoneSms'])->name('auth.phone.send');
Route::post('/auth/phone-sms/verify', [ClientAuthController::class, 'verifyPhoneSms'])->name('auth.phone.verify');
Route::get('/search', [SeviaSearchController::class, 'index'])->name('search.index');
Route::get('/product/{product:slug}', [SeviaProductController::class, 'show'])->name('product.show');
Route::post('/product/{product:slug}/reviews', [SeviaProductReviewController::class, 'store'])
    ->name('sevia.product.reviews.store')
    ->middleware('throttle:5,1');

Route::get('/contacts', fn (SeviaTemplatePageController $controller) =>
    $controller->show('contacts', 'front.sevia::contacts')
)->name('contacts');

Route::get('/delivery-payment', fn (SeviaTemplatePageController $controller) =>
    $controller->show('delivery-payment', 'front.sevia::delivery-payment')
)->name('delivery-payment');

Route::get('/faq', fn (SeviaTemplatePageController $controller) =>
    $controller->show('faq', 'front.sevia::page-templates.faq.fallback')
)->name('faq');

Route::fallback(function () {
    return response()->view('front.sevia::errors.404', [], 404);
});
