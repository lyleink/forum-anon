<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class FeedbackCategory extends Model
{
    protected $name = 'feedback_categories';
    protected $pk = 'id';

    protected $type = [
        'id' => 'integer',
        'sort_order' => 'integer',
    ];
}
