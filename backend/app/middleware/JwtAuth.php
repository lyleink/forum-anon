<?php
declare(strict_types=1);

namespace app\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    public function handle($request, \Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return json(['code' => 401, 'msg' => '未提供认证令牌', 'data' => null], 401);
        }

        if (preg_match('/^Bearer\s+(.*)$/i', $token, $matches)) {
            $token = $matches[1];
        }

        try {
            $secret = env('JWT_SECRET', 'forum_anon_secret_key_2024');
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            $request->userId = (int) $decoded->uid;
            $request->userRole = $decoded->role;
        } catch (\Exception $e) {
            return json(['code' => 401, 'msg' => '认证令牌无效或已过期', 'data' => null], 401);
        }

        return $next($request);
    }
}
