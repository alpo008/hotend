<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 04.02.17
 * Time: 13:43
 */

namespace app\models\custom;

use yii\grid\ActionColumn;


class CustomActionColumn extends  ActionColumn
{
    public $filter = [];

    protected function renderFilterCellContent()
    {
        return $this->filter;
    }
}