<?php

namespace App\Providers;

use App\Nova\Metrics\AnnouncementsPerDay;
use App\Nova\Metrics\BenefitsPerDay;
use App\Nova\Metrics\NewAnnouncements;
use App\Nova\Metrics\NewBenefits;
use App\Nova\Metrics\NewNotifications;
use App\Nova\Metrics\NewOrganizations;
use App\Nova\Metrics\NewPayments;
use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\NotificationsPerDay;
use App\Nova\Metrics\OrganizationsPerDay;
use App\Nova\Metrics\PaymentsPerDay;
use App\Nova\Metrics\PaymentsPerPlan;
use App\Nova\Metrics\PaymentsPerStatus;
use App\Nova\Metrics\UsersPerDay;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        \Epigra\NovaSettings\NovaSettingsTool::setSettingsFields([
            Trix::make('Terms & Conditions', 'tnc'),
            Trix::make('Privacy Policy', 'pp'),
            Trix::make('Frequently Asked Questions', 'faq'),
            Text::make('BillPlz Collection ID', 'billplz_collection_id'),
        ]);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->type === "ADMIN";
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new NewOrganizations,
            new OrganizationsPerDay,
            new NewUsers,
            new UsersPerDay,
            new NewAnnouncements,
            new AnnouncementsPerDay,
            new NewBenefits,
            new BenefitsPerDay,
            new NewNotifications,
            new NotificationsPerDay,
            new NewPayments,
            new PaymentsPerPlan,
            new PaymentsPerStatus(),
            new PaymentsPerDay,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new \Epigra\NovaSettings\NovaSettingsTool
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
