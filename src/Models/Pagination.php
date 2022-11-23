<?php

namespace Rainte\Yii\Models;

class Pagination
{
    /**
     * 分页: 默认页数.
     */
    const PAGE = 1;
    /**
     * 分页: 默认每页数量.
     */
    const SIZE = 10;

    /**
     * 分页处理.
     *
     * @param object $query Query.
     * @param int $page 页数.
     * @param int $size 大小.
     * @return array
     */
    public static function run(object $query, int $page, int $size): array
    {
        $totalCount = (clone $query)->count();

        $pagination = new \yii\data\Pagination(['totalCount' => $totalCount]);
        $pagination->setPage(bcsub($page, 1));
        $pagination->setPageSize($size, true);

        $res = [
            'page' => (int) bcadd($pagination->page, 1),
            'size' => (int) $pagination->pageSize,
            'total' => (int) $pagination->totalCount,
        ];

        $res['list'] = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $res;
    }
}
