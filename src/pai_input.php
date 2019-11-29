<?php
ob_start();
$pai_input = substr(system("SET /P pai_input= & SET pai_input"), 10);
ob_end_clean();
echo $pai_input;
