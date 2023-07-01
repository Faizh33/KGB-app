<?php

function generateUUID() {
    $randomBytes = random_bytes(16);
    $timeLow = bin2hex(substr($randomBytes, 0, 4));
    $timeMid = bin2hex(substr($randomBytes, 4, 2));
    $timeHiAndVersion = bin2hex(substr($randomBytes, 6, 2));
    $clockSeqHiAndReserved = bin2hex(substr($randomBytes, 8, 2));
    $clockSeqLow = bin2hex(substr($randomBytes, 10, 2));
    $node = bin2hex(substr($randomBytes, 12, 4));

    $uuid = sprintf('%s-%s-%s-%s-%s%s',
        $timeLow, $timeMid, $timeHiAndVersion, $clockSeqHiAndReserved, $clockSeqLow, $node);

    return $uuid;
}
