<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\FeedbackCategory;

class Category extends BaseController
{
    public function index()
    {
        $list = FeedbackCategory::order('sort_order', 'asc')->select();

        return json([
            'code' => 200,
            'msg'  => '操作成功',
            'data' => $list,
        ]);
    }
}
