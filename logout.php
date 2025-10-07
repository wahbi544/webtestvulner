<?php
include 'index.php';

// Destroy session
session_destroy();
echo "You are logged out! <a href='sign.php'>Login again</a>";
