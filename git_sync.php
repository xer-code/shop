<?php
$output = [];
exec('git status 2>&1', $output);
exec('git add . 2>&1', $output);
exec('git commit -m "Fix live server chat routing and add chat deletion" 2>&1', $output);
exec('git push 2>&1', $output);
echo implode("\n", $output);
