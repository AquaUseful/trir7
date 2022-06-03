<?php
assert(isset($id) &&
isset($displayName))
?>

<div class="form-divcont">
    <input class="btn green" type="button" value="<?php echo($displayName); ?>"
        id="<?php echo($id) ?>">
</div>

<?php
unset($id, $displayName);
