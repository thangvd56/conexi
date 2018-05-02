<?php if(isset($data)) : ?>
    <?php
        foreach ($data as $key => $value) {
            echo $this->Form->input($key.'.shop_id.', array(
                'type' => 'checkbox',
                'class' => 'is-checked-shop',
                'hiddenField' => false,
                'value' => $value['Shop']['id'],
                'label' => $value['Shop']['shop_name'],
                'data-shopname' => $value['Shop']['shop_name']
            ));
        }
    ?>
<?php endif;
