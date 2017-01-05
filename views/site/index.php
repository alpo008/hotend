<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2><?= Yii::t('app', 'Urgent orders')?></h2>
                <table class = "table table-bordered">
                    <tr>
                        <td>
                            <?=Yii::t('app', 'Material ref')?>
                        </td>
                        <td>
                            <?=Yii::t('app', 'Material')?>
                        </td>
                        <td>
                            <?=Yii::t('app', 'Planned to order')?>
                        </td>
                        <td>
                            <?=Yii::t('app', 'Stock qty')?>
                        </td>
                        <td>
                            <?=Yii::t('app', 'Order Date')?>
                        </td>
                        <td>
                            <?=Yii::t('app', 'Status')?>
                        </td>
                        <td>
                            <?=Yii::t('app', 'Order ref')?>
                        </td>
                        <td>

                        </td>
                    </tr>
                    <?php
                        foreach ($lists['orders_to_do'] as $order_to_do): ?>
                            <tr>
                                <td>
                                    <?=$order_to_do['materials']['ref']?>
                                </td>
                                <td>
                                    <?=$order_to_do['materials']['name']?>
                                </td>
                                <td>
                                    <?=$order_to_do['qty']?>
                                </td>
                                <td>
                                    <?=$order_to_do['materials']['qty']?>
                                </td>
                                <td>
                                    <?=$order_to_do['order_date'] ?>
                                </td>
                                <td>
                                    <?=$lists['statuses'][$order_to_do['status']] ?>
                                </td>
                                <td>
                                    <?=$order_to_do['docref'] ?>
                                </td>
                                <td>
                                    <a href="/orders/update/<?=$order_to_do['id']?>">
                                        <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                    <?php
                        endforeach;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
