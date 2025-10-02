<?php

namespace Botble\Courses\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Base\Facades\DashboardMenu;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\Instructor;
use Botble\Courses\Models\CourseCategory;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Slug\Facades\SlugHelper;

class CourseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/courses')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Course::class, fn () => trans('plugins/courses::courses.course.name'));
            SlugHelper::setPrefix(Course::class, 'courses');
        });

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(CourseCategory::class, fn () => trans('plugins/courses::courses.course-category.name'));
            SlugHelper::setPrefix(CourseCategory::class, 'courses');
        });

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Instructor::class, fn () => trans('plugins/courses::courses.instructor.name'));
            SlugHelper::setPrefix(Instructor::class, 'courses');
        });

        if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Course::class, ['name']);
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Instructor::class, ['name']);
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(CourseCategory::class, ['name']);
        }


        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-courses')
                        ->priority(5)
                        ->name('plugins/courses::courses.manage')
                        ->icon('ti ti-school')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-courses-course')
                        ->priority(1)
                        ->parentId('cms-plugins-courses')
                        ->name('plugins/courses::courses.course.name')
                        ->icon('ti ti-book')
                        ->route('course.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-courses-instructors')
                        ->priority(2)
                        ->parentId('cms-plugins-courses')
                        ->name('plugins/courses::courses.instructor.name')
                        ->icon('ti ti-user')
                        ->route('instructor.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-courses-categories')
                        ->priority(3)
                        ->parentId('cms-plugins-courses')
                        ->name('plugins/courses::courses.course-category.name')
                        ->icon('ti ti-category')
                        ->route('course-category.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-courses-bookings')
                        ->priority(4)
                        ->parentId('cms-plugins-courses')
                        ->name('plugins/courses::courses.course.booking')
                        ->icon('ti ti-calendar')
                        ->route('course-booking.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-courses-reviews')
                        ->priority(4)
                        ->parentId('cms-plugins-courses')
                        ->name('plugins/courses::courses.course.review')
                        ->icon('ti ti-star')
                        ->route('course-review.index')
                )
            ;
        });
    }
}
