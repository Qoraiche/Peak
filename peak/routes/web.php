<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;
use Qoraiche\Peak\Http\Controllers\Admin\AdminOverviewController;
use Qoraiche\Peak\Http\Controllers\Admin\Api\SearchUserController;
use Qoraiche\Peak\Http\Controllers\Admin\DeleteUserProfilePhoto;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\ExportManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\PermissionExportController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\RoleExportController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\TeamExportController;
use Qoraiche\Peak\Http\Controllers\Admin\Exports\UserExportController;
use Qoraiche\Peak\Http\Controllers\Admin\NoteManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\PermissionManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\RoleManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Frontend\CustomCodeSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Frontend\ThemeSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\System\CacheController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\System\LogController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Website\WebsiteFileStorageSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Website\WebsiteGeneralSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\Settings\Website\WebsiteMailSettingsController;
use Qoraiche\Peak\Http\Controllers\Admin\TeamManagementController;
use Qoraiche\Peak\Http\Controllers\Admin\UpdateUserPassword;
use Qoraiche\Peak\Http\Controllers\Admin\UpdateUserProfilePhoto;
use Qoraiche\Peak\Http\Controllers\Admin\UserManagementController;
use Qoraiche\Peak\Http\Controllers\Frontend\FrontController;
use Qoraiche\Peak\Http\Controllers\Frontend\HomeController;
use Qoraiche\Peak\Http\Controllers\User\ApiTokenController;
use Qoraiche\Peak\Http\Controllers\User\DashboardController;
use Qoraiche\Peak\Http\Controllers\User\NotificationController;
use Qoraiche\Peak\Http\Controllers\User\ProfileController;
use Qoraiche\Peak\Http\Controllers\User\SecurityController;
use Spatie\Robots\RobotsTxt;

/**
 * Frontend
 */
Route::middleware(config('peak.middlewares.front'))->group(callback: function () {

    Route::get('/', action: FrontController::class)->name('home');

    /*Route::prefix(config('peak.paths.blog', 'blog'))->name('blog.')->group(function () {
        Route::get('/', BlogController::class)->name('index');
        Route::get('/category/{postCategory}', [BlogController::class, 'showCategoryPosts'])->name('category.index');
        Route::get('/topic/{tag}', [BlogController::class, 'showTopicPosts'])->name('topic.index');
        Route::get('/{post}', [BlogController::class, 'show'])->name('show');
    });*/

    /*Route::prefix(config('peak.paths.roadmap', 'roadmap'))->name('roadmap.')->group(function () {
        Route::get('/', RoadmapController::class)->name('index');
        Route::post('/', [RoadmapController::class, 'store'])->name('store');
        Route::get('/{roadmap}', [RoadmapController::class, 'show'])->name('show');
        Route::put('/{roadmap}/vote', [RoadmapController::class, 'vote'])->name('vote')->middleware('auth:sanctum');
    });*/

    Route::get(config('peak.paths.contact', 'contact'), [HomeController::class, 'showContact'])->name('contact.index');

//    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

    /*Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.login');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');*/

    /*Route::post('/login/magic', SendLoginMagicLink::class)
        ->middleware('throttle:5,1')
        ->name('login.magic');*/
});

/**
 * User
 */
Route::prefix(config('peak.paths.user', 'dashboard'))
    ->name('dashboard.')
    ->middleware(config('peak.middlewares.user'))->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        /*Route::get('/impersonate-stop', [ImpersonationController::class, 'stop'])
            ->middleware(['can:impersonate-users'])
            ->name('impersonate.stop');*/

        /*Route::prefix('support')->name('support.')->group(function () {
            Route::resource('tickets', SupportController::class);
            Route::post('/{ticket}/comment', [SupportController::class, 'storeComment'])->name('ticket.comment.store');
            Route::put('/{ticketComment}/comment', [SupportController::class, 'updateComment'])->name('ticket.comment.update');
            Route::post('/{ticketComment}/report', [SupportController::class, 'reportComment'])->name('ticket.comment.report');
        });*/

        Route::prefix('account')->name('account.')->group(function () {

//            Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');

//            Route::get('/subscription', [BillingController::class, 'manage'])->name('subscription.manage');

            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
                Route::get('/read', [NotificationController::class, 'markAsRead'])->name('read');
            });

            Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

            Route::get('/security', [SecurityController::class, 'show'])->name('security');

            Route::get('/api', [ApiTokenController::class, 'index'])->name('api');

            Route::prefix('api-tokens')->name('api-tokens.')->group(function () {
                Route::get('/', [ApiTokenController::class, 'index'])->name('index');
                Route::post('/', [ApiTokenController::class, 'store'])->name('store');
                Route::put('/{token}', [ApiTokenController::class, 'update'])->name('update');
                Route::delete('/{token}', [ApiTokenController::class, 'destroy'])->name('destroy');
            });

            /*Route::prefix('billing')->name('billing.')->group(function () {
                Route::get('/', [BillingController::class, 'index'])->name('index');
                Route::get('/manage', [BillingController::class, 'manage'])->name('manage');
                Route::get('checkout/{subscriptionPlanPricing}', [BillingController::class, 'checkout'])->name('checkout');

                // subscription
                Route::prefix('subscription')->name('subscription.')->group(function () {
                    Route::post('/{subscriptionPlanPricing}/{provider?}', NewSubscriptionController::class)->name('new');
                    // Pending Checkouts...
                    //                    Route::post('/pending-checkout', NewPendingCheckoutController::class)->name('paddle.pending-checkout');
                    Route::put('/subscription', UpdateSubscriptionController::class)->name('update');
                    Route::put('/billing-information', UpdateBillingInformationController::class)->name('billing-information.update');
                    Route::put('/subscription/cancel/{subscription?}', CancelSubscriptionController::class)->name('cancel');
                    Route::put('/subscription/resume/{subscription?}', ResumeSubscriptionController::class)->name('resume');
                });

                // Invoice Emails...
                Route::put('/invoice-emails', UpdateInvoiceEmailsController::class)->name('invoice-emails.update');
                Route::get('/invoices/{provider}/{invoiceId}/download', DownloadInvoiceController::class)->name('invoices.download');

                // Payment Methods...
                Route::prefix('payment-method')->name('payment-method.')->group(function () {
                    Route::post('/', [PaymentMethodsController::class, 'setup'])->name('setup');
                    Route::put('/default', [PaymentMethodsController::class, 'default'])->name('set-default');
                    Route::delete('/payment-method', [PaymentMethodsController::class, 'delete'])->name('delete');
                    Route::put('/{provider?}', UpdatePaymentMethodController::class)->name('update');
                });
            });*/
        });
    });

/**
 * Admin
 */
Route::prefix(config('peak.paths.admin', 'admin'))
    ->name('admin.')
    ->middleware(config('peak.middlewares.admin'))
    ->group(function () {

        Route::get('/', [AdminOverviewController::class, 'index'])->name('dashboard');

        Route::prefix('settings')->middleware(config('peak.middlewares.settings'))->name('settings.')->group(function () {

            /** Todo use prefix for settings names (website, stream, etc...) */
            /* WEBSITE */
            Route::get('/website/general', [WebsiteGeneralSettingsController::class, 'settings'])->name('website.general');
            Route::put('/website/general', [WebsiteGeneralSettingsController::class, 'update'])->name('website.general.update');

            /*Route::get('/website/site-availability', [WebsiteSiteAvailabilitySettingsController::class, 'settings'])->name('website.site-availability');
            Route::put('/website/site-availability', [WebsiteSiteAvailabilitySettingsController::class, 'update'])->name('website.site-availability.update');*/

            Route::get('/website/mail', [WebsiteMailSettingsController::class, 'settings'])->name('website.mail');
            Route::put('/website/mail', [WebsiteMailSettingsController::class, 'update'])->name('website.mail.update');

            Route::get('/website/file-storage', [WebsiteFileStorageSettingsController::class, 'settings'])->name('website.file-storage');
            Route::put('/website/file-storage', [WebsiteFileStorageSettingsController::class, 'update'])->name('website.file-storage.update');

            /*Route::get('/website/auth-providers', [WebsiteAuthProvidersSettingsController::class, 'settings'])->name('website.auth-providers');
            Route::put('/website/auth-providers/facebook', [WebsiteAuthProvidersSettingsController::class, 'updateFacebook'])->name('website.auth-providers.update.facebook');
            Route::put('/website/auth-providers/x', [WebsiteAuthProvidersSettingsController::class, 'updateX'])->name('website.auth-providers.update.x');
            Route::put('/website/auth-providers/linkedin', [WebsiteAuthProvidersSettingsController::class, 'updateLinkedin'])->name('website.auth-providers.update.linkedin');
            Route::put('/website/auth-providers/google', [WebsiteAuthProvidersSettingsController::class, 'updateGoogle'])->name('website.auth-providers.update.google');
            Route::put('/website/auth-providers/github', [WebsiteAuthProvidersSettingsController::class, 'updateGithub'])->name('website.auth-providers.update.github');
            Route::put('/website/auth-providers/gitlab', [WebsiteAuthProvidersSettingsController::class, 'updateGitlab'])->name('website.auth-providers.update.gitlab');
            Route::put('/website/auth-providers/bitbucket', [WebsiteAuthProvidersSettingsController::class, 'updateBitbucket'])->name('website.auth-providers.update.bitbucket');
            Route::put('/website/auth-providers/slack', [WebsiteAuthProvidersSettingsController::class, 'updateSlack'])->name('website.auth-providers.update.slack');*/
            /*
                        Route::get('/website/analytics', [WebsiteAnalyticsSettingsController::class, 'settings'])->name('website.analytics');
                        Route::put('/website/analytics', [WebsiteAnalyticsSettingsController::class, 'update'])->name('website.analytics.update');

                        Route::get('/website/social-links', [WebsiteSocialLinksSettingsController::class, 'settings'])->name('website.social-links');
                        Route::put('/website/social-links', [WebsiteSocialLinksSettingsController::class, 'update'])->name('website.social-links.update');

                        Route::get('/website/mobile-app', [WebsitePwaSettingsController::class, 'settings'])->name('website.pwa');
                        Route::put('/website/mobile-app', [WebsitePwaSettingsController::class, 'update'])->name('website.pwa.update');

                        Route::get('/website/cookie', [WebsiteCookieSettingsController::class, 'settings'])->name('website.cookie');
                        Route::put('/website/cookie', [WebsiteCookieSettingsController::class, 'update'])->name('website.cookie.update');*/

            // email verification enable, reset password enable, registration enable, login enable, twofactor_auth enable, auth.password_timeout, , auth pass expire
            /* Route::get('/website/security', [WebsiteSecuritySettingsController::class, 'settings'])->name('website.security');
             Route::put('/website/security', [WebsiteSecuritySettingsController::class, 'update'])->name('website.security.update');*/

            Route::prefix('frontend')->name('frontend.')->middleware(['can:edit-frontend-settings'])->group(function () {

                Route::get('theme', [ThemeSettingsController::class, 'settings'])->name('theme');
                Route::put('theme', [ThemeSettingsController::class, 'update'])->name('theme.update');

                /*Route::prefix('logo')->name('logo.')->group(function () {
                    Route::get('/', LogoSettingsController::class)->name('edit');
                    Route::post('/', [LogoSettingsController::class, 'update'])->name('update');
                });

                Route::prefix('hero')->name('hero.')->group(function () {
                    Route::get('/', HeroSettingsController::class)->name('edit');
                    Route::delete('/', [HeroSettingsController::class, 'deleteImage'])->name('image.delete');
                    Route::put('/', [HeroSettingsController::class, 'update'])->name('update');
                });

                Route::prefix('appearance')->name('appearance.')->group(function () {
                    Route::get('/', AppearanceSettingsController::class)->name('edit');
                    Route::put('/', [AppearanceSettingsController::class, 'update'])->name('update');
                });

                Route::prefix('header')->name('header.')->group(function () {
                    Route::get('/', HeaderSettingsController::class)->name('edit');
                    Route::put('/', [HeaderSettingsController::class, 'updateContent'])->name('update');

                    Route::patch('{menu}/move-up', [HeaderSettingsController::class, 'moveUp'])->name('menu.move-up');
                    Route::patch('{menu}/move-down', [HeaderSettingsController::class, 'moveDown'])->name('menu.move-down');

                    Route::post('/menu', [HeaderSettingsController::class, 'addMenuItem'])->name('menu.add');
                    Route::put('/menu/{menu}', [HeaderSettingsController::class, 'updateMenuItem'])->name('menu.update');
                    Route::delete('/menu/{menu}', [HeaderSettingsController::class, 'deleteMenuItem'])->name('menu.delete');
                });

                Route::prefix('footer')->name('footer.')->group(function () {
                    Route::get('/', FooterSettingsController::class)->name('edit');
                    Route::put('/', [FooterSettingsController::class, 'updateContent'])->name('update');

                    Route::patch('{menu}/move-up', [FooterSettingsController::class, 'moveUp'])->name('menu.move-up');
                    Route::patch('{menu}/move-down', [FooterSettingsController::class, 'moveDown'])->name('menu.move-down');

                    Route::post('/menu', [FooterSettingsController::class, 'addMenuItem'])->name('menu.add');
                    Route::put('/menu/{menu}', [FooterSettingsController::class, 'updateMenuItem'])->name('menu.update');
                    Route::delete('/menu/{menu}', [FooterSettingsController::class, 'deleteMenuItem'])->name('menu.delete');
                    //                    Route::put('/menu/', [HeaderSettingsController::class, 'reorderMenuItem'])->name('menu.reorder');
                });

                Route::prefix('features')->name('features.')->group(function () {
                    Route::get('/', FeaturesSettingsController::class)->name('edit');
                    Route::post('/', [FeaturesSettingsController::class, 'store'])->name('create');
                    Route::patch('{feature}/move-up', [FeaturesSettingsController::class, 'moveUp'])->name('move-up');
                    Route::patch('{feature}/move-down', [FeaturesSettingsController::class, 'moveDown'])->name('move-down');
                    Route::put('/{feature}', [FeaturesSettingsController::class, 'update'])->name('update');
                    Route::delete('/{feature}', [FeaturesSettingsController::class, 'delete'])->name('delete');
                    Route::delete('/{feature}/image', [FeaturesSettingsController::class, 'deleteImage'])->name('image.delete');
                });

                Route::prefix('testimonials')->name('testimonials.')->group(function () {
                    Route::get('/', TestimonialsSettingsController::class)->name('edit');
                    Route::post('/', [TestimonialsSettingsController::class, 'store'])->name('create');
                    Route::patch('{testimonial}/move-up', [TestimonialsSettingsController::class, 'moveUp'])->name('move-up');
                    Route::patch('{testimonial}/move-down', [TestimonialsSettingsController::class, 'moveDown'])->name('move-down');
                    Route::put('/{testimonial}', [TestimonialsSettingsController::class, 'update'])->name('update');
                    Route::delete('/{testimonial}', [TestimonialsSettingsController::class, 'delete'])->name('delete');
                    Route::delete('/{testimonial}/image', [TestimonialsSettingsController::class, 'deleteImage'])->name('image.delete');
                });

                Route::prefix('faqs')->name('faqs.')->group(function () {
                    Route::get('/', FaqsSettingsController::class)->name('edit');
                    Route::post('/', [FaqsSettingsController::class, 'store'])->name('create');
                    Route::patch('{faq}/move-up', [FaqsSettingsController::class, 'moveUp'])->name('move-up');
                    Route::patch('{faq}/move-down', [FaqsSettingsController::class, 'moveDown'])->name('move-down');
                    Route::put('/{faq}', [FaqsSettingsController::class, 'update'])->name('update');
                    Route::delete('/{faq}', [FaqsSettingsController::class, 'delete'])->name('delete');
                });
                */

                Route::prefix('custom-code')->name('custom-code.')->group(function () {
                    Route::get('/', CustomCodeSettingsController::class)->name('edit');
                    Route::put('/css', [CustomCodeSettingsController::class, 'updateCss'])->name('css.update');
                    Route::put('/js', [CustomCodeSettingsController::class, 'updateJs'])->name('js.update');
                });
            });

            /*Route::prefix('selling')->name('selling.')->group(function () {
                Route::prefix('business-information')->name('business-information.')->group(function () {
                    Route::get('/', BusinessInformationSettingsController::class)->name('edit');
                    Route::put('/', [BusinessInformationSettingsController::class, 'update'])->name('update');
                });

                Route::prefix('payments')->name('payments.')->group(function () {
                    Route::get('/', [PaymentsSettingsController::class, 'edit'])->name('edit');
                    Route::put('/stripe', [PaymentsSettingsController::class, 'updateStripe'])->name('update.stripe');
                    Route::put('/paddle', [PaymentsSettingsController::class, 'updatePaddle'])->name('update.paddle');
                });

                Route::prefix('settings')->name('settings.')->group(function () {
                    Route::get('/', [SellingSettingsController::class, 'edit'])->name('edit');
                    Route::put('/', [SellingSettingsController::class, 'update'])->name('update');
                });
            });*/

            /*Route::prefix('marketing')->name('marketing.')->group(function () {
                Route::prefix('newsletter')->name('newsletter.')->group(function () {
                    Route::get('/', [NewsLetterSettingsController::class, 'edit'])->name('edit');
                    Route::put('/mailchimp', [NewsLetterSettingsController::class, 'updateMailChimp'])->name('update.mailchimp');
                });

                Route::prefix('announcement')->name('announcement.')->group(function () {
                    Route::get('/', [AnnouncementSettingsController::class, 'edit'])->name('edit');
                    Route::put('/', [AnnouncementSettingsController::class, 'update'])->name('update');
                });
            });*/

            Route::prefix('system')->name('system.')->group(function () {
                Route::prefix('cache')->name('cache.')->middleware(['can:clear-cache'])->group(function () {
                    Route::get('/', CacheController::class)->name('edit');
                    Route::delete('/clear', [CacheController::class, 'clear'])->name('clear');
                });

                Route::prefix('log')->name('log.')->middleware(['can:view-app-logs'])->group(function () {
                    Route::get('/', LogController::class)->name('edit');
                });

                /*Route::prefix('file-manager')->name('file-manager.')->middleware(['can:access-file-manager'])->group(function () {
                    Route::get('/', FileManagerController::class)->name('edit');
                });

                Route::group(['prefix' => 'filemanager', 'as' => 'filemanager.', 'middleware' => ['can:access-file-manager']], function () {
                    Lfm::routes();
                });*/
            });
        });

        Route::prefix('/api')->name('api.')->group(function () {
            Route::get('search-users', SearchUserController::class)->name('search-users');
        });

        /*
                Route::post('/impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate.start');
                Route::get('/impersonate-stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop');*/

        Route::get('/', [AdminOverviewController::class, 'index'])->name('dashboard');

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [\Qoraiche\Peak\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
            Route::get('/{id}', [\Qoraiche\Peak\Http\Controllers\Admin\NotificationController::class, 'show'])->name('show');
            Route::get('/read', [\Qoraiche\Peak\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('read');
        });

        // Finance (overview, transactions, plans, subscriptions)
        /*Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('overview', BillingOverviewController::class)->name('overview');
            Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
            Route::resource('plans', PlanManagementController::class);
            Route::delete('plans', [PlanManagementController::class, 'bulkDestroy'])->name('plans.bulk-destroy');

            Route::resource('subscriptions', SubscriptionController::class)->only('index', 'edit');

            Route::prefix('export')->name('export.')->middleware(['can:export'])->group(function () {
                Route::get('subscriptions', SubscriptionExportController::class)->name('subscriptions');
                Route::get('transactions', TransactionExportController::class)->name('transactions');
                Route::get('plans', SubscriptionPlanExportController::class)->name('plans');
            });
        });*/

        // Users management
        Route::prefix('user-management')->name('user-management.')->group(function () {
            /*Route::post('/login/magic', SendLoginMagicLink::class)
                ->middleware(['throttle:5,1', 'can:send-users-magic-links'])
                ->name('login.magic');

            Route::post('/impersonate/{user}', [ImpersonationController::class, 'start'])
                ->middleware(['can:impersonate-users'])
                ->name('impersonate.start');*/

            // users
            Route::put('users/{user}/password', UpdateUserPassword::class)->name('users.update.password');
            Route::put('users/{user}/photo', UpdateUserProfilePhoto::class)->name('users.update.photo');
            Route::delete('users/{user}/photo', DeleteUserProfilePhoto::class)->name('users.delete.photo');
            Route::delete('users', [UserManagementController::class, 'bulkDestroy'])->name('users.bulk-destroy');
            Route::resource('users', UserManagementController::class);

            //user notes
            Route::delete('notes/{note}', [NoteManagementController::class, 'destroy'])->name('notes.destroy');
            Route::put('notes/{note}', [NoteManagementController::class, 'update'])->name('notes.update');
            Route::post('notes/{user}', [NoteManagementController::class, 'store'])->name('notes.store');

            // roles
            Route::delete('roles', [RoleManagementController::class, 'bulkDestroy'])->name('roles.bulk-destroy');
            Route::resource('roles', RoleManagementController::class);

            // permissions
            Route::delete('permissions', [PermissionManagementController::class, 'bulkDestroy'])->name('permissions.bulk-destroy');
            Route::resource('permissions', PermissionManagementController::class);

            // teams
            Route::delete('teams', [TeamManagementController::class, 'bulkDestroy'])->name('teams.bulk-destroy');
            Route::resource('teams', TeamManagementController::class);

            // exports
            Route::prefix('export')->name('export.')->group(function () {
                Route::get('users', UserExportController::class)->name('users');
                Route::get('roles', RoleExportController::class)->name('roles');
                Route::get('teams', TeamExportController::class)->name('teams');
                Route::get('permissions', PermissionExportController::class)->name('permissions');
            });
        });

        // Support Tickets management
        /*Route::prefix('support')->name('support.')->group(function () {
            Route::resource('tickets', SupportTicketManagementController::class);
            Route::put('tickets/{ticket}/status', [SupportTicketManagementController::class, 'updateStatus'])->name('ticket.status.update');
            Route::post('tickets/{ticket}/comment', [SupportTicketManagementController::class, 'storeComment'])->name('ticket.comment.store');
            Route::put('tickets/{ticketComment}/comment', [SupportTicketManagementController::class, 'updateComment'])->name('ticket.comment.update');
            Route::delete('tickets/{ticketComment}/comment', [SupportTicketManagementController::class, 'destroyComment'])->name('ticket.comment.destroy');
            Route::post('tickets/{ticketComment}/report', [SupportTicketManagementController::class, 'reportComment'])->name('ticket.comment.report');

            Route::prefix('tickets')->name('tickets.')->group(function () {
                Route::delete('/delete', [SupportTicketManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
                Route::get('export', SupportTicketExportController::class)->name('export');
            });
        });

        Route::prefix('changelogs')->name('changelogs.')->group(function () {
            Route::delete('/delete', [ChangelogManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('/export', ChangelogExportController::class)->name('export');
        });

        Route::resource('changelogs', ChangelogManagementController::class);

        Route::prefix('roadmaps')->name('roadmaps.')->group(function () {
            Route::delete('/delete', [PageManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('/export', RoadmapExportController::class)->name('export');
            Route::put('{roadmap}/upvote', [RoadmapManagementController::class, 'toggleUpvote'])->name('upvote.toggle');
        });

        Route::resource('roadmaps', controller: RoadmapManagementController::class);

        Route::prefix('pages')->name('pages.')->group(function () {
            Route::delete('/delete', [PageManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('/export', PageExportController::class)->name('export');
        });

        Route::resource('pages', PageManagementController::class);

        Route::prefix('referrals')->name('referrals.')->group(function () {
            Route::patch('/{referral}/approve', [ReferralManagementController::class, 'approveReferral'])->name('approve');
            Route::patch('/{referral}/pending', [ReferralManagementController::class, 'pendingReferral'])->name('pending');
            Route::patch('/{referral}/pay', [ReferralManagementController::class, 'payReferral'])->name('pay');

            Route::delete('/bulk-delete', [ReferralManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('/export', ReferralExportController::class)->name('export');
        });

        Route::resource('referrals', ReferralManagementController::class);*/

        /*Route::prefix('blog')->name('blog.')->group(function () {
            Route::resource('articles', BlogManagement::class);
            Route::resource('categories', BlogCategoryManagement::class);
            Route::delete('categories', [BlogCategoryManagement::class, 'bulkDestroy'])->name('categories.bulk-destroy');
            Route::delete('/image/{article}', [BlogManagement::class, 'deleteImage'])->name('image.delete');
            Route::delete('articles', [BlogManagement::class, 'bulkDestroy'])->name('articles.bulk-destroy');
            Route::post('/verify-slug/{blogPost?}', [BlogManagement::class, 'verifySlug'])->name('verify-slug');

            Route::prefix('export')->name('export.')->group(function () {
                Route::get('posts', PostExportController::class)->name('posts');
                Route::get('categories', PostExportController::class)->name('categories');
            });
        });*/

        /*
            Route::prefix('languages')->name('languages.')->group(function () {
            Route::patch('{language}/move-up', [LanguageManagementController::class, 'moveUp'])->name('move-up');
            Route::patch('{language}/move-down', [LanguageManagementController::class, 'moveDown'])->name('move-down');
            Route::patch('{language}/default', [LanguageManagementController::class, 'makeDefault'])->name('make-default');
            Route::post('{language}/translation', [LanguageManagementController::class, 'storeTranslation'])->name('translation.store');
            Route::put('{language}/translation', [LanguageManagementController::class, 'updateTranslation'])->name('translation.update');
            Route::delete('translation/{group}/{key}', [LanguageManagementController::class, 'destroyLanguageLine'])->name('translation.delete');

            Route::delete('/delete', [LanguageManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('/export', LanguageExportController::class)->name('export');
        });

        Route::resource('languages', LanguageManagementController::class);

        // Documentation
        Route::prefix('docs')->name('docs.')->group(function () {
            // docs
            Route::patch('/{doc}/move-up', [DocManagementController::class, 'moveUp'])->name('move-up');
            Route::patch('/{doc}/move-down', [DocManagementController::class, 'moveDown'])->name('move-down');

            // docs categories
            Route::resource('categories', DocCategoryManagement::class);
            Route::patch('categories/{category}/move-up', [DocCategoryManagement::class, 'moveUp'])->name('categories.move-up');
            Route::patch('categories/{category}/move-down', [DocCategoryManagement::class, 'moveDown'])->name('categories.move-down');
            Route::delete('categories', [DocCategoryManagement::class, 'bulkDestroy'])->name('categories.bulk-destroy');
            Route::get('categories/export', DocCategoryExportController::class)->name('categories.export');
            Route::post('/verify-slug/{doc?}', [DocManagementController::class, 'verifySlug'])->name('verify-slug');
            Route::delete('delete', [DocManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('export', DocExportController::class)->name('export');
            Route::get('url', [DocManagementController::class, 'getUrlDataInfo'])->name('url.info');
            Route::post('image', [DocManagementController::class, 'uploadImage'])->name('image.upload');
        });

        Route::resource('docs', DocManagementController::class);
        */

        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/', [ExportManagementController::class, 'index'])->name('index');
            Route::delete('items', [ExportManagementController::class, 'bulkDestroy'])->name('items.bulk-delete');
            Route::delete('/{export}', [ExportManagementController::class, 'destroy'])->name('delete');
        });

        // reports
        /*Route::resource('reports', ReportManagementController::class);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::delete('delete', [ReportManagementController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('export', ReportExportController::class)->name('export');
        });*/
    });
//}


/**
 * Webhooks
 */
/*Route::middleware(config('peak.middlewares.webhooks'))->group(callback: function () {
    Route::as('webhook.')->group(function () {
        Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe');

        Route::post('paddle/webhook', PaddleWebhookController::class)->name('paddle');
    });
});*/

Route::get('/robots.txt', function () {
    $adminPath = trim(config('peak.paths.admin', 'admin'), '/');

    $robots = RobotsTxt::create()
        ->addUserAgent('*')
        ->addDisallow("/$adminPath")
        ->addDisallow(RoutePath::for('login', '/login'))
        ->addDisallow(RoutePath::for('register', '/register'));

    return response((string)$robots, 200)
        ->header('Content-Type', 'text/plain');
});