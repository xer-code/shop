<?php

/**
 * ShopX Global — Route Definitions
 * All application routes are defined here
 */

// ============================================
// PUBLIC ROUTES
// ============================================

// Home
$router->get('/', 'HomeController@index');

// Offline page
$router->get('/offline', 'HomeController@offline');

// Download App page
$router->get('/download', 'HomeController@download');
$router->get('/app-release.apk', 'HomeController@downloadApk');

// Shop
$router->get('/shop', 'ShopController@index');
$router->get('/shop/{id}', 'ShopController@show');

// Gift Cards
$router->get('/gift-cards', 'GiftCardController@index');

// Track Order (public)
$router->get('/track-order', 'OrderController@track');
$router->post('/track-order', 'OrderController@trackSearch');
$router->get('/track-order/invoice/{code}', 'OrderController@invoice');

// Virtual Stores
$router->get('/virtual-stores', 'VirtualStoreController@index');
$router->get('/virtual-stores/{id}', 'VirtualStoreController@show');

// ============================================
// AUTH ROUTES (guest only)
// ============================================
$router->group(['middleware' => 'guest'], function ($router) {
    $router->get('/login', 'AuthController@loginForm');
    $router->post('/login', 'AuthController@login');
    $router->get('/register', 'AuthController@registerForm');
    $router->post('/register', 'AuthController@register');
});

$router->get('/logout', 'AuthController@logout');

// Live Chat (accessible to guests and authenticated users)
$router->post('/chat/send', 'ChatController@send');
$router->get('/chat/messages', 'ChatController@messages');
$router->get('/chat/admin-status', 'ChatController@adminStatus');
$router->post('/pusher/auth', 'PusherAuthController@auth');


// ============================================
// AUTHENTICATED ROUTES
// ============================================
$router->group(['middleware' => 'auth'], function ($router) {
    // Cart
    $router->get('/cart', 'CartController@index');
    $router->post('/cart/add', 'CartController@add');
    $router->post('/cart/update', 'CartController@update');
    $router->post('/cart/remove', 'CartController@remove');

    // Checkout
    $router->get('/checkout', 'CheckoutController@index');
    $router->post('/checkout', 'CheckoutController@process');
    $router->get('/checkout/success/{id}', 'CheckoutController@success');

    // Orders
    $router->get('/my-orders', 'OrderController@myOrders');
    $router->get('/orders/{id}', 'OrderController@show');

    // Wallet
    $router->get('/wallet', 'WalletController@index');
    $router->post('/wallet/add-funds', 'WalletController@addFunds');

    // Gift Cards (purchase/redeem)
    $router->post('/gift-cards/purchase', 'GiftCardController@purchase');
    $router->post('/gift-cards/redeem', 'GiftCardController@redeem');

    // Wishlist
    $router->post('/wishlist/toggle', 'ShopController@toggleWishlist');
    // Profile
    $router->get('/profile', 'ProfileController@index');
    $router->post('/profile/update', 'ProfileController@update');

    // Customer Dashboard
    $router->get('/dashboard', 'CustomerDashboardController@index');
    $router->post('/dashboard/quote/request', 'CustomerDashboardController@requestQuote');
    $router->post('/dashboard/support/create', 'CustomerDashboardController@createSupportTicket');
    $router->post('/dashboard/support/reply/{id}', 'CustomerDashboardController@replySupportTicket');
    $router->post('/dashboard/gift-cards/redeem', 'CustomerDashboardController@redeemGiftCard');
});

// ============================================
// ADMIN ROUTES
// ============================================

// Admin auth (separate login)
$router->get('/admin/login', 'Admin\\AdminController@loginForm');
$router->post('/admin/login', 'Admin\\AdminController@login');

$router->group(['prefix' => 'admin', 'middleware' => 'admin'], function ($router) {
    // Dashboard
    $router->get('/', 'Admin\\AdminController@dashboard');
    $router->get('/dashboard', 'Admin\\AdminController@dashboard');

    // Products
    $router->get('/products', 'Admin\\AdminProductController@index');
    $router->get('/products/create', 'Admin\\AdminProductController@create');
    $router->post('/products/store', 'Admin\\AdminProductController@store');
    $router->get('/products/edit/{id}', 'Admin\\AdminProductController@edit');
    $router->post('/products/update/{id}', 'Admin\\AdminProductController@update');
    $router->post('/products/delete/{id}', 'Admin\\AdminProductController@delete');

    // Users
    $router->get('/users', 'Admin\\AdminUserController@index');
    $router->post('/users/create', 'Admin\\AdminUserController@createUser');
    $router->post('/users/update-role/{id}', 'Admin\\AdminUserController@updateRole');
    $router->post('/users/suspend/{id}', 'Admin\\AdminUserController@suspend');

    // Orders
    $router->get('/orders', 'Admin\\AdminOrderController@index');
    $router->get('/orders/{id}', 'Admin\\AdminOrderController@show');
    $router->post('/orders/update-status/{id}', 'Admin\\AdminOrderController@updateStatus');
    $router->post('/orders/delete/{id}', 'Admin\\AdminOrderController@delete');

    // Gift Cards
    $router->get('/gift-cards', 'Admin\\AdminGiftCardController@index');
    $router->post('/gift-cards/issue', 'Admin\\AdminGiftCardController@issue');
    $router->post('/gift-cards/void/{id}', 'Admin\\AdminGiftCardController@void');
    $router->post('/gift-cards/delete/{id}', 'Admin\\AdminGiftCardController@delete');

    // Enterprise Dashboard Modules
    $router->get('/analytics', 'Admin\\AdminEnterpriseController@analytics');
    $router->get('/customers', 'Admin\\AdminEnterpriseController@customers');
    $router->post('/customers/suspend/{id}', 'Admin\\AdminEnterpriseController@suspendCustomer');
    $router->post('/customers/fund/{id}', 'Admin\\AdminEnterpriseController@fundCustomer');
    
    $router->get('/suppliers', 'Admin\\AdminEnterpriseController@suppliers');
    $router->post('/suppliers/create', 'Admin\\AdminEnterpriseController@createSupplier');
    $router->post('/suppliers/delete/{id}', 'Admin\\AdminEnterpriseController@deleteSupplier');
    
    $router->get('/categories', 'Admin\\AdminEnterpriseController@categories');
    $router->post('/categories/create', 'Admin\\AdminEnterpriseController@createCategory');
    $router->post('/categories/delete/{id}', 'Admin\\AdminEnterpriseController@deleteCategory');
    
    $router->get('/payments', 'Admin\\AdminEnterpriseController@payments');
    
    $router->get('/invoices', 'Admin\\AdminEnterpriseController@invoices');
    $router->get('/invoices/{id}', 'Admin\\AdminEnterpriseController@showInvoice');
    
    $router->get('/quotes', 'Admin\\AdminEnterpriseController@quotes');
    $router->post('/quotes/update-status/{id}', 'Admin\\AdminEnterpriseController@updateQuoteStatus');
    
    $router->get('/warehouses', 'Admin\\AdminEnterpriseController@warehouses');
    $router->post('/warehouses/create', 'Admin\\AdminEnterpriseController@createWarehouse');
    $router->post('/warehouses/delete/{id}', 'Admin\\AdminEnterpriseController@deleteWarehouse');
    
    $router->get('/shipments', 'Admin\\AdminEnterpriseController@shipments');
    $router->post('/shipments/create', 'Admin\\AdminEnterpriseController@createShipment');
    $router->post('/shipments/update-status/{id}', 'Admin\\AdminEnterpriseController@updateShipmentStatus');
    $router->post('/shipments/delete/{id}', 'Admin\\AdminEnterpriseController@deleteShipment');
    
    $router->get('/tracking', 'Admin\\AdminEnterpriseController@tracking');
    $router->post('/tracking/add-update', 'Admin\\AdminEnterpriseController@addTrackingUpdate');
    $router->get('/tracking/manage/{code}', 'Admin\\AdminEnterpriseController@manageTracking');
    $router->post('/tracking/manage/{code}', 'Admin\\AdminEnterpriseController@updateTracking');
    
    $router->get('/support', 'Admin\\AdminEnterpriseController@support');
    $router->post('/support/reply/{id}', 'Admin\\AdminEnterpriseController@replySupport');
    $router->post('/support/close/{id}', 'Admin\\AdminEnterpriseController@closeSupport');
    
    $router->get('/promotions', 'Admin\\AdminEnterpriseController@promotions');
    $router->post('/promotions/create', 'Admin\\AdminEnterpriseController@createPromotion');
    $router->post('/promotions/delete/{id}', 'Admin\\AdminEnterpriseController@deletePromotion');
    
    $router->get('/coupons', 'Admin\\AdminEnterpriseController@coupons');
    $router->post('/coupons/create', 'Admin\\AdminEnterpriseController@createCoupon');
    $router->post('/coupons/delete/{id}', 'Admin\\AdminEnterpriseController@deleteCoupon');
    
    $router->get('/notifications', 'Admin\\AdminEnterpriseController@notifications');
    $router->post('/notifications/send', 'Admin\\AdminEnterpriseController@sendNotification');
    
    $router->get('/audit-logs', 'Admin\\AdminEnterpriseController@auditLogs');
    
    $router->get('/reports', 'Admin\\AdminEnterpriseController@reports');
    
    $router->get('/settings', 'Admin\\AdminEnterpriseController@settings');
    $router->post('/settings/update', 'Admin\\AdminEnterpriseController@updateSettings');
    
    $router->get('/email-settings', 'Admin\\AdminEnterpriseController@emailSettings');
    $router->post('/email-settings/update', 'Admin\\AdminEnterpriseController@updateEmailSettings');
    $router->post('/email-settings/test', 'Admin\\AdminEnterpriseController@sendTestEmail');
    $router->post('/email-settings/templates/update', 'Admin\\AdminEnterpriseController@updateEmailTemplates');
    
    $router->get('/roles', 'Admin\\AdminEnterpriseController@roles');
    $router->post('/roles/create', 'Admin\\AdminEnterpriseController@createRole');
    $router->post('/roles/delete/{roleName}', 'Admin\\AdminEnterpriseController@deleteRole');
    $router->post('/roles/update/{id}', 'Admin\\AdminEnterpriseController@updateRolePermissions');
    
    $router->get('/permissions', 'Admin\\AdminEnterpriseController@permissions');
    $router->post('/permissions/update', 'Admin\\AdminEnterpriseController@updateAllPermissions');
    
    $router->get('/api-keys', 'Admin\\AdminEnterpriseController@apiKeys');
    $router->post('/api-keys/create', 'Admin\\AdminEnterpriseController@createApiKey');
    $router->post('/api-keys/toggle/{id}', 'Admin\\AdminEnterpriseController@toggleApiKey');
    $router->post('/api-keys/delete/{id}', 'Admin\\AdminEnterpriseController@deleteApiKey');

    // Payment Gateways (System & Security)
    $router->get('/payment-gateways', 'Admin\\AdminEnterpriseController@paymentGateways');
    $router->post('/payment-gateways/update/{id}', 'Admin\\AdminEnterpriseController@updatePaymentGateway');

    // Deposit Requests (Sales & Catalog)
    $router->get('/deposits', 'Admin\\AdminEnterpriseController@deposits');
    $router->post('/deposits/approve/{id}', 'Admin\\AdminEnterpriseController@approveDeposit');
    $router->post('/deposits/reject/{id}', 'Admin\\AdminEnterpriseController@rejectDeposit');

    // Customer Management
    $router->get('/customers/manage/{id}', 'Admin\\AdminEnterpriseController@manageCustomer');
    $router->post('/customers/update-profile/{id}', 'Admin\\AdminEnterpriseController@updateCustomerProfile');
    $router->post('/customers/update-balance/{id}', 'Admin\\AdminEnterpriseController@updateCustomerBalance');
    $router->post('/customers/update-status/{id}', 'Admin\\AdminEnterpriseController@updateCustomerStatus');
    $router->post('/customers/email/{id}', 'Admin\\AdminEnterpriseController@emailCustomer');
    $router->post('/customers/create', 'Admin\\AdminEnterpriseController@createCustomer');
    $router->post('/customers/delete/{id}', 'Admin\\AdminEnterpriseController@deleteCustomer');

    // Admin Profile Settings
    $router->get('/profile', 'Admin\\AdminEnterpriseController@profile');
    $router->post('/profile/update', 'Admin\\AdminEnterpriseController@updateProfile');

    // Live Chat & Heartbeat
    $router->get('/live-chat', 'Admin\AdminEnterpriseController@liveChat');
    $router->post('/live-chat/reply/{identifier}', 'Admin\AdminEnterpriseController@liveChatReply');
    $router->post('/live-chat/delete/{identifier}', 'Admin\AdminEnterpriseController@liveChatDelete');
    $router->post('/live-chat/toggle-status', 'Admin\AdminEnterpriseController@toggleChatStatus');
    $router->post('/heartbeat', 'Admin\\AdminEnterpriseController@adminHeartbeat');
});
