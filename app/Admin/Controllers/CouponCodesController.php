<?php

namespace App\Admin\Controllers;

use App\Models\CouponCode;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponCodesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'CouponCode';

    public function index(Content $content)
    {
        return $content->header('优惠券列表')
            ->body($this->grid());
    }

    public function create(Content $content)
    {
        return $content->header('新增优惠券')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CouponCode());

        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('名称'));
        $grid->column('code', __('优惠码'));
        // $grid->column('type', __('类型'))->display(function ($value) {
        //     return CouponCode::$typeMap[$value];
        // });
        // 根据不同的折扣类型用对应的方式来展示
        // $grid->column('value', __('折扣'))->display(function ($value) {
        //     return $this->type === CouponCode::TYPE_FIXED ? '￥' . $value : $value . '%';
        // });
        // $grid->column('min_amount', __('最低金额'));
        // $grid->column('total', __('总量'));
        $grid->column('description', __('描述'));
        $grid->column('usage', __('用量'))->display(function ($value) {
            return "{$this->used} / {$this->total}";
        });
        $grid->column('not_before', __('使用开始时间'));
        $grid->column('not_after', __('使用结束时间'));
        $grid->column('enabled', __('是否启用'))->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->column('created_at', __('创建时间'));
        // $grid->column('updated_at', __('Updated at'));

        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        return $grid;
    }

    public function edit($id, Content $content)
    {
        return $content->header('编辑优惠券')
            ->body($this->form()->edit($id));
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CouponCode::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('code', __('Code'));
        $show->field('type', __('Type'));
        $show->field('value', __('Value'));
        $show->field('total', __('Total'));
        $show->field('used', __('Used'));
        $show->field('min_amount', __('Min amount'));
        $show->field('not_before', __('Not before'));
        $show->field('not_after', __('Not after'));
        $show->field('enabled', __('Enabled'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CouponCode());

        $form->display('id', __('ID'));
        $form->text('name', __('名称'))->rules('required');
        $form->text('code', __('优惠码'))->rules(function ($form) {
            // 如果 $form->model()->id 不为空，代表是编辑操作
            if ($id = $form->model()->id) {
                return 'nullable|unique:coupon_codes,code,' . $id . ',id';
            } else {
                return 'nullable|unique:coupon_codes';
            }
        });
        $form->radio('type', __('类型'))->options(CouponCode::$typeMap)->rules('required');
        $form->decimal('value', __('折扣'))->rules(function ($form) {
            if ($form->type === CouponCode::TYPE_PERCENT) {
                // 如果选择了百分比折扣类型，那么折扣范围只能是 1 ~ 99
                return 'required|numeric|between:1,99';
            } else {
                // 否则只要大等于 0.01 即可
                return 'required|numeric|min:0.01';
            }
        });
        $form->number('total', __('总量'))->rules('required|numeric|min:0');
        // $form->number('used', __('Used'));
        $form->decimal('min_amount', __('最低金额'))->rules('required|numeric|min:0');
        $form->datetime('not_before', __('开始时间'))->default(date('Y-m-d H:i:s'));
        $form->datetime('not_after', __('结束时间'))->default(date('Y-m-d H:i:s'));
        $form->switch('enabled', __('启用'))->default(true);
        // $form->saving() 方法用来注册一个事件处理器，在表单的数据被保存前会被触发
        $form->saving(function (Form $form) {
            if (!$form->code) {
                $form->code = CouponCode::findAvailableCode();
            }
        });

        return $form;
    }
}
