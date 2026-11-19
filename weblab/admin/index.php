<?php
// Physical /admin/ folder exists for admin CSS files.
// This file forwards /admin/ to the main router so Apache does not show Forbidden.
require __DIR__ . '/../index.php';
