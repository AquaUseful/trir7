<?php

namespace page\encode {
    function minimize_html(string $str): string
    {
        return preg_replace('/>[\n\s\t]+</u', '><', $str);
    }
}
