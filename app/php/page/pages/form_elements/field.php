<?php
assert(isset($id) && isset($displayName) && isset($type));
assert(isset($field));

$input_suffix = '_field';
$err_suffix='_err';
?>

<div class="form-divcont">
    <span class="form-label"><?php echo($displayName); ?></span> <br>
    <input class="form-field" type="<?php echo($type); ?>"
        id="<?php echo($id.$input_suffix); ?>"> <br>
        <span class="form-error" id="<?php echo($id.$err_suffix)?>"></span> 
</div>

<?php
unset($id, $displayName, $type);
