<p class="text-center">
    <span class="p_style"><?php echo $totle_count; ?></span>件中、 検索結果<span class="p_style"><?php  echo $count_result; ?></span>件
</p>
<div class="table-responsive">
    <div class="well content2 customers-container">
        <div class="panel">
            <?php if ($customer): ?>
            <table class="table tbl-customers">
                <tbody>
                    <?php
                        foreach ($customer as $key => $value):
                            $image = 'app1.PNG';
                            if ($value['User']['is_install_app'] == 1) {
                                $image = 'app2.PNG';
                            }
                            $sex = trim($value['User']['gender']);
                            $gender = unserialize(GENDER);
                            if ($sex == '男性') {
                                $gender = $gender['男性'];
                            } else if ($sex == '女性') {
                                $gender = $gender['女性'];
                            } else {
                                $gender = '--';
                            }
                            $from = new DateTime($value['User']['birthday']);
                            $to = new DateTime('today');
                            $age = $from->diff($to)->y;
                    ?>
                        <tr class="panel-body change_style_div get_customer_info" id="<?= $value['User']['id'] ?>">
                            <td class="customers-check">
                                <div>
                                    <?php echo $this->Html->image($image); ?>
                                </div>
                            </td>
                            <td class="customers-name">
                                <div class="vertical-border">
                                    <?php echo $value['User']['lastname'] . ' ' . $value['User']['firstname']; ?>
                                </div>
                            </td>
                            <td class="customers-contact">
                                <div class="vertical-border">
                                    <?php echo $value['User']['contact']; ?>
                                </div>
                            </td>
                            <td class="customers-gender">
                                <div>
                                    <?php echo $gender; ?>
                                </div>
                            </td>
                            <td class="customers-icon">
                                <div>
                                    <?php
                                    echo $this->Html->image('arrow.PNG', array(
                                        'class' => 'get_customer_info',
                                        'id' => $value['User']['id'],
                                        'data-name' => $value['User']['lastname'] . ' ' . $value['User']['firstname']
                                    ));
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>