<?php if (isset($data)) : ?>
    <table class="table table-bordered">
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">コピー日時</th>
            <th class="text-center">コピー元</th>
            <th class="text-center">コピー先</th>
            <th class="text-center">機能</th>
        </tr>
        <?php foreach ($data as $key => $value) : ?>
        <tr>
            <td><?php echo $key+1; ?></td>
            <td><?php echo date('d-m-Y', strtotime($value['CouponCopy']['created'])); ?></td>
            <td><?php echo '<strong>'.h($value['CouponCopy']['group_name']).'</strong><br>'.h($value['CouponCopy']['from_shop_name']); ?></td>
            <td>
                <?php
                    if ($value['CouponCopy']['copy_all']) {
                        echo '<strong>'.h($value['CouponCopy']['group_name']).'</strong><br>全店舗';
                    } else {
                        $shop_name = '';
                        foreach ($value['CouponCopy']['to_shop_name'] as $key2 => $value2) {
                            $shop_name = $shop_name.'<br>'.$value2;
                        }
                        echo '<strong>'.h($value['CouponCopy']['group_name']).'</strong>'.$shop_name;
                    }
                ?>
            </td>
            <td>
                <input type="button" class="btn btn-restore btn-color" value="元に戻す"
                       data-toggle="popover"
                       data-placement="left"
                       data-html="true"
                       data-trigger="click"
                       data-content="元に戻しますか？<br>
                       <input type='button' value='いいえ' class='btn btn-no'>
                       <input type='button' value='はい' class='btn btn-ok'
                       data-copy-id='<?php echo $value['CouponCopy']['id']; ?>'
                       data-shop-id='<?php echo h($value['CouponCopy']['to_shop_id']); ?>'>"
                >
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif;