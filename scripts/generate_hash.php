<?php

$password = 'secret123';
$hash = password_hash($password, PASSWORD_ARGON2ID);
echo "Password hash for 'secret123':\n";
echo $hash . "\n"; 
echo password_verify($password, $hash);