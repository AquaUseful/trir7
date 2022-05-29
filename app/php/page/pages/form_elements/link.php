<?php
assert(isset($id) && isset($href) && isset($displayName));
?>

<div>
    <a class="form-link" href="<?php echo($href); ?>"
        id="<?php echo($id); ?>"><?php echo($displayName); ?></a>
</div>
