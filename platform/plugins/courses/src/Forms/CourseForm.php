<?php

namespace Botble\Courses\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\DateTimeField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\Courses\Http\Requests\CourseRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\Instructor;
use Botble\Courses\Models\CourseCategory;

class CourseForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Course::class)
            ->setValidatorClass(CourseRequest::class)
            ->add('category_id', SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/courses::courses.course-category.category'))
                    ->choices(
                        CourseCategory::query()
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->pluck('name', 'id')
                            ->all()
                    )
                    ->searchable()
                    ->required()
            )
            ->add('instructor_id', SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/courses::courses.instructor.instructor'))
                    ->choices(
                        Instructor::query()
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->pluck('name', 'id')
                            ->all()
                    )
                    ->searchable()
                    ->required()
            )
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('core/base::forms.description'))
                    ->rows(4)
                    ->placeholder('Enter course description')
            )
            ->add('price', NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/courses::courses.course.price'))
                    ->required()
            )
            ->add('duration', TextField::class,
                NameFieldOption::make()
                    ->label(trans('plugins/courses::courses.course.duration'))
                    ->placeholder('e.g. 10 weeks, 40 hours')
            )
            ->add('start_date', DatetimeField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/courses::courses.course.start_date'))
                    ->required()
            )
            ->add('end_date', DatetimeField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/courses::courses.course.end_date'))
            )
            ->add('thumbnail', MediaImageField::class, MediaImageFieldOption::make()->label(trans('core/acl::users.avatar')))
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
                    ->toArray()
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
