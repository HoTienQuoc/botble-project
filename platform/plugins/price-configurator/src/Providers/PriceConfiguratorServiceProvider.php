<?php

namespace Botble\PriceConfigurator\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\Base\Facades\DashboardMenu;
use Illuminate\Support\Facades\Schema;
use Botble\Hotel\Http\Controllers\PublicController as HotelPublicController;
use Botble\Hotel\Http\Controllers\BookingController as HotelBookingController;
use Botble\Hotel\Services\GetRoomService as HotelGetRoomService;
use Botble\PriceConfigurator\Http\Controllers\PublicController as PCPublicController;
use Botble\PriceConfigurator\Http\Controllers\BookingController as PCBookingController;
use Botble\PriceConfigurator\Services\PriceConfigurator;

class PriceConfiguratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PriceConfigurator::class, function () {
            return new PriceConfigurator();
        });

        if (class_exists(HotelPublicController::class)) {
            $this->app->bind(HotelPublicController::class, PCPublicController::class);
        }
        if (class_exists(HotelBookingController::class)) {
            $this->app->bind(HotelBookingController::class, PCBookingController::class);
        }

        if (class_exists(HotelGetRoomService::class)) {
            $this->app->extend(HotelGetRoomService::class, function ($service, $app) {
                return new class($service, $app->make(PriceConfigurator::class)) extends HotelGetRoomService {
                    public function __construct(
                        private \Botble\Hotel\Services\GetRoomService $inner,
                        private PriceConfigurator $configurator
                    ) {}

                    public function __call($name, $arguments)
                    {
                        return $this->inner->$name(...$arguments);
                    }

                    public function getRooms(\Botble\Hotel\DataTransferObjects\RoomSearchParams $params): \Illuminate\Pagination\LengthAwarePaginator
                    {
                        $paginator = $this->inner->getRooms($params);
                        $customerCategory = \Botble\Hotel\Facades\HotelHelper::getCurrentCustomer()?->customer_category ?? 'STANDARD';
                        $items = $paginator->getCollection()->map(function ($room) use ($customerCategory) {
                            if (isset($room->total_price)) {
                                $room->total_price = $this->configurator->adjustRoomTotalPrice($room, (float) $room->total_price, $customerCategory);
                            }
                            return $room;
                        });
                        $paginator->setCollection($items);
                        return $paginator;
                    }
                };
            });
        }
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'price-configurator');

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-price-configurator',
            'priority' => 56,
            'parent_id' => null,
            'name' => 'Price Configurator',
            'icon' => 'fa fa-sliders-h',
            'route' => 'pc.admin.index',
            'permissions' => [],
        ]);

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-price-configurator-tiers',
            'priority' => 57,
            'parent_id' => 'cms-plugins-price-configurator',
            'name' => 'Preisstufen',
            'icon' => 'fa fa-layer-group',
            'route' => 'pc.tiers.index',
            'permissions' => [],
        ]);

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-price-configurator-rules',
            'priority' => 58,
            'parent_id' => 'cms-plugins-price-configurator',
            'name' => 'Regeln',
            'icon' => 'fa fa-list',
            'route' => 'pc.rules.index',
            'permissions' => [],
        ]);

        DashboardMenu::registerItem([
            'id' => 'cms-plugins-price-configurator-categories',
            'priority' => 59,
            'parent_id' => 'cms-plugins-price-configurator',
            'name' => 'Kundenkategorien',
            'icon' => 'fa fa-user-tag',
            'route' => 'pc.categories.index',
            'permissions' => [],
        ]);

        // Seed initial data once
        if (Schema::hasTable('pc_price_tiers') && Schema::hasTable('pc_price_rules')) {
            $hasAny = \DB::table('pc_price_tiers')->count();
            if (! $hasAny) {
                if (Schema::hasTable('pc_customer_categories')) {
                    \DB::table('pc_customer_categories')->insertOrIgnore([
                        ['code' => 'STANDARD', 'label' => 'Standard', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                        ['code' => 'VIP', 'label' => 'VIP', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                    ]);
                }

                \DB::table('pc_price_tiers')->insert([
                    'name' => 'VIP Tier',
                    'priority' => 10,
                    'status' => 'active',
                    'is_exclusive' => 0,
                    'starts_at' => null,
                    'ends_at' => null,
                    'notes' => 'Auto-created',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $tierId = \DB::getPdo()->lastInsertId();

                \DB::table('pc_price_rules')->insert([
                    'price_tier_id' => $tierId,
                    'customer_category' => 'VIP',
                    'scope' => 'all_rooms',
                    'calculation_type' => 'percent',
                    'calculation_value' => -50,
                    'rounding_mode' => 'none',
                    'round_to' => 0.01,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
