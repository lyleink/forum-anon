<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class Feedback extends Model
{
    protected $name = 'feedbacks';
    protected $pk = 'id';

    protected $type = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function getStatusAttr($value)
    {
        return [
            'pending' => 'pending',
            'in-progress' => 'in-progress',
            'resolved' => 'resolved',
        ][$value] ?? $value;
    }

    public function statusHistory()
    {
        return $this->hasMany(FeedbackStatusHistory::class, 'feedback_id', 'id');
    }
}
