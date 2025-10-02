<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Courses\Http\Requests\CourseRequest;
use Botble\Courses\Models\Course;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Courses\Tables\CourseTable;
use Botble\Courses\Forms\CourseForm;

class CourseController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans(trans('plugins/courses::courses.course.name')), route('course.index'));
    }

    public function index(CourseTable $table)
    {
        $this->pageTitle(trans('plugins/courses::courses.course.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/courses::courses.course.create'));

        return CourseForm::create()->renderForm();
    }

    public function store(CourseRequest $request)
    {
        $form = CourseForm::create()->setRequest($request);

        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('course.index'))
            ->setNextUrl(route('course.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Course $course)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $course->name]));

        return CourseForm::createFromModel($course)->renderForm();
    }

    public function update(Course $course, CourseRequest $request)
    {
        CourseForm::createFromModel($course)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('course.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Course $course)
    {
        return DeleteResourceAction::make($course);
    }
}
