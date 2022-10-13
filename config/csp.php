<?php

$bytes = random_bytes(5);

return [
    "nonce" => bin2hex($bytes),
];
