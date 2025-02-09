<?php


use Laravel\Cashier\PromotionCode;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Public\UserController;
use App\Http\Controllers\Api\Vendor\PostController;
use App\Http\Controllers\Api\Vendor\SaleController;
use App\Http\Controllers\Api\Admin\GenderController;
use App\Http\Controllers\Api\Vendor\OrderController;
use App\Http\Controllers\Api\Vendor\PromoController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Vendor\PolicyController;
use App\Http\Controllers\Api\Vendor\VendorController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Vendor\ArticleController;
use App\Http\Controllers\Api\Vendor\ProductController;
use App\Http\Controllers\Api\Admin\VariationController;
use App\Http\Controllers\Api\Customer\ReviewController;
use App\Http\Controllers\Api\Public\HomePageController;
use App\Http\Controllers\Api\Vendor\CustomerController;
use App\Http\Controllers\Api\Vendor\ZoneRateController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\Auth\VendorLoginController;
use App\Http\Controllers\Api\Vendor\DashboardController;
use App\Http\Controllers\Api\Admin\SubcategoryController;
use App\Http\Controllers\Api\Auth\SocialVendorController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Customer\CheckoutController;
use App\Http\Controllers\Api\Admin\BusinessTypeController;
use App\Http\Controllers\Api\Admin\DiscountCodeController;
use App\Http\Controllers\Api\Auth\CustomerLoginController;
use App\Http\Controllers\Api\Customer\SeeReviewController;
use App\Http\Controllers\Api\Vendor\LocalPickupController;
use App\Http\Controllers\Api\Vendor\OrderSearchController;
use App\Http\Controllers\Api\Admin\AdminShippingController;
use App\Http\Controllers\Api\Auth\SocialRegisterController;
use App\Http\Controllers\Api\Auth\VendorRegisterController;
use App\Http\Controllers\Api\Customer\AllProductController;
use App\Http\Controllers\Api\Customer\VendorPageController;
use App\Http\Controllers\Api\Vendor\NotificationController;
use App\Http\Controllers\Api\Vendor\ReviewSearchController;
use App\Http\Controllers\Api\Vendor\ShippingZoneController;
use App\Http\Controllers\Api\Vendor\SubscriptionController;
use App\Http\Controllers\Api\Admin\ShippingMethodController;
use App\Http\Controllers\Api\Public\DiscoveryPageController;
use App\Http\Controllers\Api\Public\ProductSearchController;
use App\Http\Controllers\Api\Vendor\LocalDeliveryController;
use App\Http\Controllers\Api\Vendor\StripeConnectController;
use App\Http\Controllers\Api\Admin\VariationOptionController;
use App\Http\Controllers\Api\Auth\CustomerRegisterController;
use App\Http\Controllers\Api\Auth\VendorVerifyCodeController;
use App\Http\Controllers\Api\Customer\PaymentMethodController;
use App\Http\Controllers\Api\Vendor\ProductVariationController;
use App\Http\Controllers\Api\Auth\ResetPasswordVendorController;
use App\Http\Controllers\Api\Auth\VendorPasswordSetupController;
use App\Http\Controllers\Api\Customer\ShippingAddressController;
use App\Http\Controllers\Api\Auth\ForgetVendorPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordCustomerController;
use App\Http\Controllers\Api\Auth\ForgetCustomerPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('auth')->group(function () {
    Route::post('register_customer', [CustomerRegisterController::class, '__invoke']);
    Route::post('register_vendor', [VendorRegisterController::class, 'register']);
    Route::post('vendor_login', [VendorLoginController::class, '__invoke']);
    Route::post('customer_login', [CustomerLoginController::class, '__invoke']);
    Route::post('logout', [LogoutController::class, '__invoke'])->middleware('auth:api');
    Route::post('verify', [VendorVerifyCodeController::class, '__invoke']);
    Route::post('resend_code', [VerificationController::class, '__invoke']);
    
    Route::post('forget_customer_password', [ForgetCustomerPasswordController::class, '__invoke']);
    Route::post('forget_vendor_password', [ForgetVendorPasswordController::class, '__invoke']);
    Route::post('reset_customer_password', [ResetPasswordCustomerController::class, '__invoke'])->name('auth.reset_customer_password');
    Route::post('reset_vendor_password', [ResetPasswordVendorController::class, '__invoke'])->name('auth.reset_vendor_password');
    // social registrations still be worked on for vendor registration part
    
    Route::get('{provider}/redirect', [SocialRegisterController::class, 'redirect']);
    Route::get('{provider}/callback', [SocialRegisterController::class, 'callback']);
    Route::get('{provider}/vendor/redirect', [SocialVendorController::class, 'vendor_redirect']);
    Route::get('{provider}/vendor/callback', [SocialVendorController::class, 'vendor_callback']);


    
    
});



Route::middleware('auth:api')->group(function () {
    Route::get('users/{user}', [UserController::class, 'show']);
    // still working on the edit part 
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    Route::prefix('admin')->group(function () {
        Route::apiResource('categories', CategoryController::class);
        Route::post('categories/upload', [CategoryController::class, 'upload']);
        Route::apiResource('sub_categories', SubcategoryController::class);
        Route::post('sub_categories/upload', [SubcategoryController::class, 'upload']); 
        Route::apiResource('variations', VariationController::class);
        Route::apiResource('variations_option', VariationOptionController::class);
        Route::apiResource('discount_codes', DiscountCodeController::class);
        Route::apiResource('business_types', BusinessTypeController::class);
        Route::apiResource('shippingMethods', ShippingMethodController::class);
        Route::apiResource('genders', GenderController::class); 

    });
    Route::prefix('vendor')->group(function () {
        Route::post('setup/{userId}', [VendorController::class, 'setupAccount']);
        Route::put('update_account/{userId}', [VendorController::class, 'updateAccount']);
        Route::post('upload', [VendorController::class, 'upload']);
        Route::post('upload/cover_image', [VendorController::class, 'uploadCoverImage']);
        // Route::get('subscribe/{plan?}', [SubscriptionController::class, 'subscribe']);
        Route::post('subscribe', [SubscriptionController::class, 'subscribe']);
        Route::get('subscribe/success', [SubscriptionController::class, 'success'])->name('vendor.subscription_success');
        Route::post('subscribe/cancel', [SubscriptionController::class, 'cancel'])->name('vendor.subscription_cancel');
         Route::delete('products/{product_id}/delete_perm', [ProductController::class, 'delete_perm']);
         Route::put('products/{product_id}/restore', [ProductController::class, 'restore']);
         Route::apiResource('products', ProductController::class);
         Route::post('products/upload', [ProductController::class, 'upload']);
         Route::get('discount_price', [PromoController::class, '__invoke']);
         Route::apiResource('products.variations', ProductVariationController::class)->except(['create', 'edit']);
         Route::post('/import/products', [ProductController::class, 'import']);
         Route::get('/export/products', [ProductController::class, 'export']);   
         Route::apiResource('orders', OrderController::class); 
         Route::get('orders/search', [OrderSearchController::class, '__invoke']); 
         Route::apiResource('posts', PostController::class); 
         Route::post('posts/upload', [PostController::class, 'upload']); 
         Route::post('posts/draft', [PostController::class, 'draft']);
         Route::post('posts/schedule', [PostController::class, 'schedule']);
         Route::post('posts/{post}/publish', [PostController::class, 'publish']);
         Route::post('posts/{post}/like', [PostController::class, 'like']);
         Route::post('posts/{post}/view', [PostController::class, 'view']);
         Route::post('posts/{post}/unlike', [PostController::class, 'unlike']);
         Route::get('posts/{post}/has_liked', [PostController::class, 'hasLiked']); 
        //  Route::post('posts/{post}/unview', [PostController::class, 'unview']);
         Route::apiResource('articles', ArticleController::class);
        // Route::get('articles/{article}', [ArticleController::class, 'show']);  
        // Route::get('articles', [ArticleController::class, 'index']); 
        // Route::post('articles', [ArticleController::class, 'store']);
        // Route::put('articles/{article}', [ArticleController::class, 'update']);
        // Route::delete('articles/{article}', [ArticleController::class, 'destroy']);
         Route::post('articles/upload', [ArticleController::class, 'upload']);
         Route::get('sold_products', [SaleController::class, 'soldProducts']); 
         Route::get('top_categories',[SaleController::class, 'topCategories']); 
         Route::get('revenue_growth',[SaleController::class, 'monthlyRevenue']); 
         Route::get('purchased_catgeory',[SaleController::class, 'allCategories']); 
         Route::get('popular_products',[SaleController::class, 'popularProducts']); 
         Route::post('{vendorId}/follow', [CustomerController::class, 'followVendor']);
         Route::post('{vendorId}/unfollow', [CustomerController::class, 'unfollowVendor']);
         Route::get('{vendorId}/followers_count', [CustomerController::class, 'getVendorFollowersCount']);
         Route::get('{vendorId}/followers', [CustomerController::class, 'getVendorFollowers']);
         Route::get('weekly_revenue', [DashboardController::class, 'weeklyRevenue']);
         Route::get('total_revenue', [DashboardController::class, 'weeklyTotalRevenue']);
         Route::get('weekly_transactions', [DashboardController::class, 'weeklyTotalTransactions']);
         Route::get('weekly_products_sold', [DashboardController::class, 'weeklyTotalProductsSold']);
         Route::get('top_weekly_transactions', [DashboardController::class, 'topWeeklyTransactions']);
         Route::get('top_weekly_products', [DashboardController::class, 'topWeeklyProducts']);
         Route::get('weekly_out_of_stock_products', [DashboardController::class, 'weeklyOutOfStockProducts']);
         Route::get('weekly_orders_by_country', [DashboardController::class, 'weeklyOrdersByCountry']);
         Route::get('weekly_total_users', [DashboardController::class, 'weeklyVendorTotalUsers']);
         Route::get('recent_weekly_orders', [DashboardController::class, 'recentWeeklyOrders']);
         Route::get('vendor_stats', [DashboardController::class, 'vendorStats']);
         Route::get('vendor_allstats', [DashboardController::class, 'consolidatedVendorStats']);
         Route::get('notifications', [NotificationController::class, 'index']); 
         Route::get('notifications/orders', [NotificationController::class, 'orderNotifications']); 
         Route::get('notifications/customers', [NotificationController::class, 'followNotifications']);
         Route::get('notifications/reviews', [NotificationController::class, 'reviewNotifications']);
         Route::get('notifications/products', [NotificationController::class, 'stockNotifications']);
         Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']); 
         Route::apiResource('shipping_zones', ShippingZoneController::class);
         Route::apiResource('shipping_zones.zone_rates', ZoneRateController::class);
         Route::apiResource('local_deliveries', LocalDeliveryController::class);
         Route::apiResource('local_pickups', LocalPickupController::class);
         Route::apiResource('policies', PolicyController::class);
         Route::apiResource('shippingMethods', ShippingMethodController::class);
         Route::apiResource('admin_shippings', AdminShippingController::class);
         Route::post('stripe/onboard/{userId}', [StripeConnectController::class, 'onboard']); 
         Route::get('stripe/save_account/{token}', [StripeConnectController::class, 'saveStripeAccount']);
         Route::get('stripe/refresh_account_link', [StripeConnectController::class, 'refreshAccountLink']);
        //  Route::post('shippingMethods', [ShippingMethodController::class, 'store']);
        //  Route::put('shippingMethods/{id}', [ShippingMethodController::class, 'update']);


        
    });
    Route::prefix('customer')->group(function () {
        Route::post('products/{product}/addcart', [CartController::class, 'addCart']);
        Route::delete('products/{productId}/cartItems/{cartItemId}', [CartController::class, 'removecart']);
        Route::put('cartItems/{cartItem}', [CartController::class, 'updateCartItemQuantity']);
        Route::delete('cartItems/clear', [CartController::class, 'clearCart']);
        Route::get('cartItems', [CartController::class, 'getCartItems']);
        Route::post('apply_discount', [DiscountCodeController::class, 'applyDiscount']);
        Route::apiResource('shippingAddresses', ShippingAddressController::class);
        Route::apiResource('paymentMethods', PaymentMethodController::class);
        Route::post('checkout', [CheckoutController::class, 'checkout']);
        // Route::apiResource('reviews', ReviewController::class);
        Route::get('reviews', [ReviewController::class, 'index']);
        Route::post('reviews', [ReviewController::class, 'store']);
        Route::put('reviews/{review}', [ReviewController::class, 'update']);
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);
        Route::get('review_search', [ReviewSearchController::class, 'search']);
        Route::get('review_filter', [ReviewSearchController::class, 'filter']);

        // Route::get('reviews/all', [ReviewController::class, 'allreview']);
        Route::post('reviews/{review}/reply', [ReviewController::class, 'reply']);
        Route::post('reviews/{review}/mark_as_pending', [ReviewController::class, 'markAsPending']);
        Route::put('reviews/{review}/reply', [ReviewController::class, 'editReply']);
        Route::delete('reviews/{review}/reply', [ReviewController::class, 'deleteReply']);
        Route::post('reviews/{review}/like', [ReviewController::class, 'like']);
        Route::post('reviews/{review}/unlike', [ReviewController::class, 'unlike']);
        Route::get('following_count', [CustomerController::class, 'getFollowingCount']);
        Route::get('following_vendors', [CustomerController::class, 'getFollowedVendors']);
        Route::get('has_followed/{vendor}', [CustomerController::class, 'hasFollowed']);
       
      
       
        

    });

   
});
Route::get('search', [ProductSearchController::class, '__invoke']);
Route::get('stores', [HomePageController::class, 'getBestSellingStores']);
Route::get('home_popular_products', [HomePageController::class, 'homepopularProducts']);
Route::get('latest_articles', [HomePageController::class, 'getLatestArticles']);
Route::get('most_selling_products', [HomePageController::class, 'getMostSellingProducts']);
Route::get('products_on_promo', [HomePageController::class, 'getProductsCompareAtPrice']);
Route::get('dis_popular_products', [DiscoveryPageController::class, 'popProducts']);
Route::get('top_selling_products', [DiscoveryPageController::class, 'getTopSellingProducts']);
Route::get('dis_products_on_promo', [DiscoveryPageController::class, 'productsOnPromo']);
Route::get('allproducts', [AllProductController::class, '__invoke']);
Route::get('allarticles', [ArticleController::class, 'allarticles']);
Route::get('allposts', [PostController::class, 'allposts']);
Route::get('vendor/posts/{post}/show_post', [PostController::class, 'showPost']);
Route::get('vendor/products/{product}/show_product', [ProductController::class, 'showProduct']);
Route::get('sub_categories/category/{category_id}', [SubcategoryController::class, 'bycategory']);
Route::get('allcategory', [CategoryController::class, 'allcategory']);
Route::get('allbusinesstypes', [BusinessTypeController::class, 'allbusinesstypes']);
Route::get('allgenders', [GenderController::class, 'allgenders']);
Route::get('{vendorId}/articles', [VendorPageController::class, 'vendors_article']);
Route::get('{vendorId}/posts', [VendorPageController::class, 'vendors_posts']);
Route::get('{vendorId}/promos', [VendorPageController::class, 'vendors_promos']);
Route::get('{vendorId}/products', [VendorPageController::class, 'vendors_products']);
Route::get('{vendorId}/details', [VendorPageController::class, 'vendors_details']);
Route::get('vendor/articles/{article}/show_article', [ArticleController::class, 'showArticle']); 
Route::get('customer/allreviews', [SeeReviewController::class, '__invoke']); 
Route::get('customer/reviews/{review}', [ReviewController::class, 'show']);
Route::stripeWebhooks('webhook');
