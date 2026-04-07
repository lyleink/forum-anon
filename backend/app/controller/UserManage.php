<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\User;

class UserManage extends BaseController
{
    public function index()
    {
        $page = (int) $this->request->get('page', 1);
        $pageSize = (int) $this->request->get('pageSize', 10);
        $keyword = $this->request->get('keyword', '');

        $query = User::order('id', 'desc');

        if ($keyword) {
            $query->where('username', 'like', "%{$keyword}%");
        }

        $total = $query->count();
        $list = $query->page($page, $pageSize)->select();

        return json([
            'code' => 200,
            'msg'  => '操作成功',
            'data' => [
                'list'     => $list,
                'total'    => $total,
                'page'     => $page,
                'pageSize' => $pageSize,
            ],
        ]);
    }

    public function save()
    {
        $username = $this->request->post('username', '');
        $password = $this->request->post('password', '');
        $email = $this->request->post('email', '');
        $role = $this->request->post('role', 'student');

        if (empty($username) || empty($password)) {
            return json(['code' => 400, 'msg' => '用户名和密码不能为空', 'data' => null]);
        }

        if (User::where('username', $username)->find()) {
            return json(['code' => 400, 'msg' => '用户名已存在', 'data' => null]);
        }

        $user = new User();
        $user->username = $username;
        $user->password = $password;
        $user->email = $email ?: null;
        $user->role = in_array($role, ['admin', 'student']) ? $role : 'student';
        $user->status = 'active';
        $user->save();

        return json([
            'code' => 200,
            'msg'  => '用户创建成功',
            'data' => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
                'status'   => $user->status,
            ],
        ]);
    }

    public function update($id)
    {
        $user = User::find($id);
        if (!$user) {
            return json(['code' => 404, 'msg' => '用户不存在', 'data' => null]);
        }

        $data = $this->request->only(['username', 'email', 'role', 'status']);

        if (!empty($data['username']) && $data['username'] !== $user->username) {
            if (User::where('username', $data['username'])->find()) {
                return json(['code' => 400, 'msg' => '用户名已存在', 'data' => null]);
            }
            $user->username = $data['username'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'] ?: null;
        }
        if (isset($data['role']) && in_array($data['role'], ['admin', 'student'])) {
            $user->role = $data['role'];
        }
        if (isset($data['status']) && in_array($data['status'], ['active', 'inactive', 'pending'])) {
            $user->status = $data['status'];
        }

        $user->save();

        return json(['code' => 200, 'msg' => '用户更新成功', 'data' => $user]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return json(['code' => 404, 'msg' => '用户不存在', 'data' => null]);
        }

        $user->delete();
        return json(['code' => 200, 'msg' => '用户已删除', 'data' => null]);
    }

    public function batchDelete()
    {
        $ids = $this->request->post('ids/a', []);

        if (empty($ids)) {
            return json(['code' => 400, 'msg' => '请选择要删除的用户', 'data' => null]);
        }

        User::destroy($ids);
        return json(['code' => 200, 'msg' => "已删除 " . count($ids) . " 个用户", 'data' => null]);
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
        if (!$user) {
            return json(['code' => 404, 'msg' => '用户不存在', 'data' => null]);
        }

        $defaultPassword = '123456';
        $user->password = $defaultPassword;
        $user->save();

        return json([
            'code' => 200,
            'msg'  => '密码已重置为默认密码',
            'data' => [
                'id'       => $user->id,
                'username' => $user->username,
            ],
        ]);
    }

    public function export()
    {
        $users = User::order('id', 'desc')->select();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', '用户名');
        $sheet->setCellValue('C1', '邮箱');
        $sheet->setCellValue('D1', '角色');
        $sheet->setCellValue('E1', '状态');
        $sheet->setCellValue('F1', '创建时间');

        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->username);
            $sheet->setCellValue('C' . $row, $user->email ?? '');
            $sheet->setCellValue('D' . $row, $user->role);
            $sheet->setCellValue('E' . $row, $user->status);
            $sheet->setCellValue('F' . $row, $user->created_at);
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'users_' . date('YmdHis') . '.xlsx';
        $filepath = runtime_path() . $filename;
        $writer->save($filepath);

        return response()->file($filepath, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportSingle($id)
    {
        $user = User::find($id);
        if (!$user) {
            return json(['code' => 404, 'msg' => '用户不存在', 'data' => null]);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', '字段');
        $sheet->setCellValue('B1', '值');
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', $user->id);
        $sheet->setCellValue('A3', '用户名');
        $sheet->setCellValue('B3', $user->username);
        $sheet->setCellValue('A4', '邮箱');
        $sheet->setCellValue('B4', $user->email ?? '');
        $sheet->setCellValue('A5', '角色');
        $sheet->setCellValue('B5', $user->role);
        $sheet->setCellValue('A6', '状态');
        $sheet->setCellValue('B6', $user->status);
        $sheet->setCellValue('A7', '创建时间');
        $sheet->setCellValue('B7', $user->created_at);
        $sheet->setCellValue('A8', '更新时间');
        $sheet->setCellValue('B8', $user->updated_at);

        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'user_' . $user->username . '_' . date('YmdHis') . '.xlsx';
        $filepath = runtime_path() . $filename;
        $writer->save($filepath);

        return response()->file($filepath, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
