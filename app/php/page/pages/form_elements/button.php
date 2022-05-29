<?php
assert(isset($id) &&
isset($displayName))
?>

<div>
    <input class="form-btn" type="button" value="<?php echo($displayName); ?>"
        id="<?php echo($id) ?>">
</div>

<?php
unset($id, $displayName);
