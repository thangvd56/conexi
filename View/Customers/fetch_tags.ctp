
<?php
    $tags = array();
    if (!empty($reservation_tag)) {
        
        foreach ($reservation_tag['ReservationTag'] as $key1 => $value1) {
            if (!isset($value1) && empty($value1)) {
                continue;
            }
            array_push($tags, $value1['tag_id']);
        }
    }
?>
<div class="col-lg-8 add_top style">
    <?php foreach ($tag as $key => $value) :
            $selected = '';
            if (in_array($value['Tag']['id'], $tags)) {
                $selected = 'selected';
            }
    ?>
        <div class="col-lg-3">
            <div class="form-group change_to_button tag_operation <?php echo $selected; ?>" id="<?php echo $value['Tag']['id']; ?>">
                <?php echo $value['Tag']['tag']; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<style>
    .selected {
        background: gray;
    }
</style>
