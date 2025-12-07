<?php
function normalizeDatetimeLocalToDb(?string $input)
{
    if (!$input) return null;
    $s = trim($input);
    if (strpos($s, 'T') !== false) {
        $s = str_replace('T', ' ', $s);
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $s)) $s .= ':00';
        return $s;
    }
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $s)) {
        if (strlen($s) === 16) $s .= ':00';
        return $s;
    }
    return null;
}
