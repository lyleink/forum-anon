<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\Feedback as FeedbackModel;
use app\model\FeedbackStatusHistory;

class Feedback extends BaseController
{
    public function index()
    {
        $page = (int) $this->request->get("page", 1);
        $pageSize = (int) $this->request->get("pageSize", 10);
        $keyword = $this->request->get("keyword", "");
        $category = $this->request->get("category", "");
        $status = $this->request->get("status", "");

        $query = FeedbackModel::with("statusHistory")->order("id", "desc");

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where("content", "like", "%{$keyword}%")->whereOr(
                    "category",
                    "like",
                    "%{$keyword}%",
                );
            });
        }

        if ($category) {
            $query->where("category", $category);
        }

        if ($status) {
            $query->where("status", $status);
        }

        $total = $query->count();
        $list = $query->page($page, $pageSize)->select();

        return json([
            "code" => 200,
            "msg" => "操作成功",
            "data" => [
                "list" => $list,
                "total" => $total,
                "page" => $page,
                "pageSize" => $pageSize,
            ],
        ]);
    }

    public function read($id)
    {
        $feedback = FeedbackModel::with("statusHistory")->find($id);

        if (!$feedback) {
            return json(["code" => 404, "msg" => "反馈不存在", "data" => null]);
        }

        return json([
            "code" => 200,
            "msg" => "操作成功",
            "data" => $feedback,
        ]);
    }

    public function save()
    {
        $category = $this->request->post("category", "");
        $content = $this->request->post("content", "");

        if (empty($category) || empty($content)) {
            return json([
                "code" => 400,
                "msg" => "分类和内容不能为空",
                "data" => null,
            ]);
        }

        $feedback = new FeedbackModel();
        $feedback->category = $category;
        $feedback->content = $content;
        $feedback->status = "pending";
        $feedback->author = null;

        $userId = $this->request->userId ?? null;
        if ($userId) {
            $feedback->user_id = $userId;
        }

        $feedback->save();

        FeedbackStatusHistory::create([
            "feedback_id" => $feedback->id,
            "status" => "pending",
            "description" => "反馈已提交",
        ]);

        return json([
            "code" => 200,
            "msg" => "反馈提交成功",
            "data" => [
                "id" => $feedback->id,
                "category" => $feedback->category,
                "content" => $feedback->content,
                "status" => $feedback->status,
                "created_at" => $feedback->created_at,
            ],
        ]);
    }

    public function updateStatus($id)
    {
        $feedback = FeedbackModel::find($id);
        if (!$feedback) {
            return json(["code" => 404, "msg" => "反馈不存在", "data" => null]);
        }

        $status = $this->request->post("status", "");
        $validStatuses = ["pending", "in-progress", "resolved"];

        if (!in_array($status, $validStatuses)) {
            return json([
                "code" => 400,
                "msg" => "无效的状态值",
                "data" => null,
            ]);
        }

        $feedback->status = $status;
        $feedback->save();

        $descriptions = [
            "pending" => "重新设为待处理状态",
            "in-progress" => "开始处理反馈",
            "resolved" => "标记反馈为已解决",
        ];

        FeedbackStatusHistory::create([
            "feedback_id" => $feedback->id,
            "status" => $status,
            "description" => $descriptions[$status] ?? "状态变更为{$status}",
        ]);

        return json([
            "code" => 200,
            "msg" => "状态已更新",
            "data" => [
                "id" => $feedback->id,
                "status" => $feedback->status,
                "updated_at" => $feedback->updated_at,
            ],
        ]);
    }

    public function updateNotes($id)
    {
        $feedback = FeedbackModel::find($id);
        if (!$feedback) {
            return json(["code" => 404, "msg" => "反馈不存在", "data" => null]);
        }

        $notes = $this->request->post("notes", "");

        $feedback->notes = $notes;
        $feedback->notes_time = date("Y-m-d H:i:s");
        $feedback->save();

        return json([
            "code" => 200,
            "msg" => "备注已保存",
            "data" => [
                "id" => $feedback->id,
                "notes" => $feedback->notes,
                "notes_time" => $feedback->notes_time,
            ],
        ]);
    }

    public function delete($id)
    {
        $feedback = FeedbackModel::find($id);
        if (!$feedback) {
            return json(["code" => 404, "msg" => "反馈不存在", "data" => null]);
        }

        FeedbackStatusHistory::where("feedback_id", $id)->delete();
        $feedback->delete();

        return json(["code" => 200, "msg" => "反馈已删除", "data" => null]);
    }

    public function export()
    {
        $feedbacks = FeedbackModel::order("id", "desc")->select();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', '分类');
        $sheet->setCellValue('C1', '内容');
        $sheet->setCellValue('D1', '状态');
        $sheet->setCellValue('E1', '提交者');
        $sheet->setCellValue('F1', '创建时间');

        $row = 2;
        foreach ($feedbacks as $fb) {
            $sheet->setCellValue('A' . $row, $fb->id);
            $sheet->setCellValue('B' . $row, $fb->category);
            $sheet->setCellValue('C' . $row, str_replace(["\r\n", "\r", "\n"], " ", $fb->content));
            $sheet->setCellValue('D' . $row, $fb->status);
            $sheet->setCellValue('E' . $row, $fb->author ?? '匿名');
            $sheet->setCellValue('F' . $row, $fb->created_at);
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'feedbacks_' . date('YmdHis') . '.xlsx';
        $filepath = runtime_path() . $filename;
        $writer->save($filepath);

        return response()->file($filepath, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportSingle($id)
    {
        $feedback = FeedbackModel::with('statusHistory')->find($id);
        if (!$feedback) {
            return json(['code' => 404, 'msg' => '反馈不存在', 'data' => null]);
        }

        $statusMap = [
            'pending' => '待处理',
            'in-progress' => '处理中',
            'resolved' => '已解决',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', '字段');
        $sheet->setCellValue('B1', '值');

        $row = 2;
        $fields = [
            '反馈ID' => $feedback->id,
            '分类' => $feedback->category,
            '内容' => $feedback->content,
            '状态' => $statusMap[$feedback->status] ?? $feedback->status,
            '提交者' => $feedback->author ?? '匿名',
            '创建时间' => $feedback->created_at,
            '更新时间' => $feedback->updated_at,
        ];

        if ($feedback->notes) {
            $fields['处理备注'] = $feedback->notes;
            $fields['备注时间'] = $feedback->notes_time ?? '';
        }

        foreach ($fields as $label => $value) {
            $sheet->setCellValue('A' . $row, $label);
            $sheet->setCellValue('B' . $row, $value);
            $row++;
        }

        // Status history
        $historyRows = $feedback->statusHistory ?: [];
        if (!empty($historyRows)) {
            $row++;
            $sheet->setCellValue('A' . $row, '状态历史');
            $sheet->mergeCells('A' . $row . ':B' . $row);
            $row++;
            $sheet->setCellValue('A' . $row, '序号');
            $sheet->setCellValue('B' . $row, '状态 / 描述 / 时间');
            $row++;

            $idx = 1;
            foreach ($historyRows as $h) {
                $statusText = $statusMap[$h['status']] ?? $h['status'];
                $desc = $h['description'] ?? '';
                $time = $h['created_at'] ?? '';
                $sheet->setCellValue('A' . $row, $idx);
                $sheet->setCellValue('B' . $row, "【{$statusText}】 {$desc} ({$time})");
                $row++;
                $idx++;
            }
        }

        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'feedback_' . $feedback->id . '_' . date('YmdHis') . '.xlsx';
        $filepath = runtime_path() . $filename;
        $writer->save($filepath);

        return response()->file($filepath, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
