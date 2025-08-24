Test Lab

Test Lab is a custom wrapper for Codeception
 in your Yii2 Advanced project.
It allows you to run Codeception commands (like generate:suite, run, etc.) using a simpler custom entry file.

📌 Features

Custom entry point: ./test-lab

Pre-configured with:

REST module for API testing

Yii2 module with your backend test config

Custom helper: \Fitenson\TestLab\Codeception\ApiHelper

Shorter commands (e.g. ./test-lab generate:suite api instead of vendor/bin/codecept ...)

🚀 Installation

Go to your Yii2 Advanced project root.

Create a new file called test-lab (no extension).

Paste the following contents: 
#!/usr/bin/env php
<?php

// point to composer autoload
require __DIR__ . '/vendor/autoload.php';

// forward the arguments to Codeception
$argv = $_SERVER['argv'];

// Replace "test-lab" with "codecept" in arguments
$argv[0] = 'codecept';

// Run Codeception binary
require __DIR__ . '/vendor/codeception/codeception/app.php';


⚙️ Configuration
Create or update your codeception.yml (or per-suite config) with:
actor: ApiTester
modules:
  enabled:
    - REST:
        url: http://backend.test/          # base URL to your backend app
        depends: PhpBrowser
    - Yii2:
        configFile: 'backend/config/test.php'  # adjust path if needed
    - \Fitenson\TestLab\Codeception\ApiHelper  # custom helper
