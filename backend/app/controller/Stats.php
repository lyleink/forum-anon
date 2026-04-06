<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\Feedback as FeedbackModel;
use app\model\User;

class Stats extends BaseController
{
    public function dashboard()
    {
        $totalFeedbacks = FeedbackModel::count();
        $activeUsers = User::where('status', 'active')->count();
        $pendingFeedbacks = FeedbackModel::where('status', 'pending')->count();
        $resolvedFeedbacks = FeedbackModel::where('status', 'resolved')->count();

        $inProgressFeedbacks = FeedbackModel::where('status', 'in-progress')->count();

        $categoryStats = FeedbackModel::field('category, count(*) as count')
            ->group('category')
            ->order('count', 'desc')
            ->select();

        $recentFeedbacks = FeedbackModel::order('id', 'desc')
            ->limit(5)
            ->select();

        return json([
            'code' => 200,
            'msg'  => '操作成功',
            'data' => [
                'totalFeedbacks'   => $totalFeedbacks,
                'activeUsers'      => $activeUsers,
                'pendingFeedbacks' => $pendingFeedbacks,
                'resolvedFeedbacks'=> $resolvedFeedbacks,
                'categoryStats'    => $categoryStats,
                'statusStats'      => [
                    'pending'     => $pendingFeedbacks,
                    'in-progress' => $inProgressFeedbacks,
                    'resolved'    => $resolvedFeedbacks,
                ],
                'recentFeedbacks'  => $recentFeedbacks,
            ],
        ]);
    }
}
