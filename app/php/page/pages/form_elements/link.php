<?php
assert(isset($id) && isset($href) && isset($displayName));
?>

    <a class="form-link" href="<?php echo($href); ?>"
        id="<?php echo($id); ?>"><?php echo($displayName); ?></a>
