<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\User;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
    public function login()
    {
        $username = $this->request->post('username', '');
        $password = $this->request->post('password', '');

        if (empty($username) || empty($password)) {
            return json(['code' => 400, 'msg' => '请输入用户名和密码', 'data' => null]);
        }

        $user = User::where('username', $username)->find();

        if (!$user) {
            return json(['code' => 400, 'msg' => '用户名或密码错误', 'data' => null]);
        }

        if ($password !== $user->password) {
            return json(['code' => 400, 'msg' => '用户名或密码错误', 'data' => null]);
        }

        $secret = env('JWT_SECRET', 'forum_anon_secret_key_2024');
        $expire = (int) env('JWT_EXPIRE', 86400);
        $payload = [
            'iss' => 'forum-anon',
            'iat' => time(),
            'exp' => time() + $expire,
            'uid'  => $user->id,
            'role' => $user->role,
        ];
        $token = JWT::encode($payload, $secret, 'HS256');

        return json([
            'code' => 200,
            'msg'  => '登录成功',
            'data' => [
                'token' => $token,
                'user'  => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                ],
            ],
        ]);
    }

    public function logout()
    {
        return json(['code' => 200, 'msg' => '退出成功', 'data' => null]);
    }
}
