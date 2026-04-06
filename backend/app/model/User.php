<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class User extends Model
{
    protected $name = 'users';
    protected $pk = 'id';

    protected $type = [
        'id' => 'integer',
    ];

    protected $hidden = ['password'];

    public function getRoleAttr($value)
    {
        return [
            'admin' => 'admin',
            'student' => 'student',
        ][$value] ?? $value;
    }

    public function getStatusAttr($value)
    {
        return [
            'active' => 'active',
            'inactive' => 'inactive',
            'pending' => 'pending',
        ][$value] ?? $value;
    }
}
