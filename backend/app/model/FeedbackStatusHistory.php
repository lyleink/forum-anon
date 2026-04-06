<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class FeedbackStatusHistory extends Model
{
    protected $name = 'feedback_status_history';
    protected $pk = 'id';

    protected $type = [
        'id' => 'integer',
        'feedback_id' => 'integer',
    ];
}
