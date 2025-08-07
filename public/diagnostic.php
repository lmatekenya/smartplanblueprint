<?php
// Diagnostic script for InfinityFree hosting

// Basic information
echo '<h1>Diagnostic Information</h1>';

// PHP Version
echo '<h2>PHP Version</h2>';
echo '<p>' . phpversion() . '</p>';

// Extensions
echo '<h2>Loaded Extensions</h2>';
echo '<pre>';
print_r(get_loaded_extensions());
echo '</pre>';

// Directory permissions
echo '<h2>Directory Permissions</h2>';
$directories = [
    '../var',
    '../var/cache',
    '../var/log',
];

foreach ($directories as $dir) {
    echo '<p>';
    echo $dir . ': ';
    if (file_exists($dir)) {
        echo 'Exists, ';
        echo is_writable($dir) ? 'Writable' : 'NOT Writable';
    } else {
        echo 'Does NOT exist';
    }
    echo '</p>';
}

// Environment
echo '<h2>Environment Variables</h2>';
echo '<pre>';
if (file_exists('../.env')) {
    echo 'Content of .env file: <br>';
    echo htmlspecialchars(file_get_contents('../.env'));
} else {
    echo '.env file does not exist';
}
echo '</pre>';

// Symfony requirement check
echo '<h2>Symfony Requirements</h2>';

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

try {
    new \App\Kernel('prod', false);
    echo '<p style="color:green">Kernel can be instantiated successfully</p>';
} catch (\Exception $e) {
    echo '<p style="color:red">Error instantiating Kernel: ' . $e->getMessage() . '</p>';
}

// Database connection test
echo '<h2>Database Connection Test</h2>';
try {
    // Parse DATABASE_URL from .env
    $env = file_get_contents('../.env');
    preg_match('/DATABASE_URL="([^"]*)"/', $env, $matches);
    if (isset($matches[1])) {
        $dbUrl = $matches[1];
        echo '<p>Attempting connection to: ' . preg_replace('/:[^:]*@/', ':****@', $dbUrl) . '</p>';

        // Parse DB URL
        $parts = parse_url($dbUrl);
        $dbName = ltrim($parts['path'], '/');

        // Connect to database
        $conn = new \PDO(
            "mysql:host={$parts['host']};dbname={$dbName}",
            $parts['user'],
            $parts['pass']
        );
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        echo '<p style="color:green">Database connection successful!</p>';
    } else {
        echo '<p style="color:red">Could not find DATABASE_URL in .env file</p>';
    }
} catch (\Exception $e) {
    echo '<p style="color:red">Database connection failed: ' . $e->getMessage() . '</p>';
}
