<?php

/*
Handle logging out user, destroying session and returning to index.php
*/
session_start();

session_unset();
session_destroy();

header("location: ../login.php");
exit();
