<?php if ($shop) : ?>
<table>
    <tr>
        <td class = "text-right"><strong>紹介文</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['introduction']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>店舗名</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['shop_name']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>店舗名(カナ)</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['shop_kana']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>住所</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['address']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>営業時間</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['hours_start'].' - '.$shop['Shop']['hours_end']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>営業時間直接入力</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['openning_hours']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>定休日</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['holidays']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>TEL</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['phone']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>FAX</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['fax']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>ホームページ</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['url']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>E-mail</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['email']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>Facebook</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['facebook']) ?></td>
    </tr>
    <tr>
        <td class = "text-right"><strong>Twitter</strong></td>
        <td class = "text-left"><?php echo h($shop['Shop']['twitter']) ?></td>
    </tr>
</table>

<?php endif;
