param()

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$scaffoldRoot = Join-Path $env:TEMP ("nestly-laravel-" + [guid]::NewGuid().ToString('N'))
$scaffoldPath = Join-Path $scaffoldRoot 'laravel'

if ((Test-Path (Join-Path $projectRoot 'artisan')) -and (Test-Path (Join-Path $projectRoot 'composer.json'))) {
    throw 'A Laravel project already exists here. Refusing to overwrite its scaffold.'
}

if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    throw 'PHP 8.2 or newer is required. Install PHP and add it to PATH, then rerun this script.'
}

if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    throw 'Composer is required. Install Composer and add it to PATH, then rerun this script.'
}

$phpVersion = & php -r "echo PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;"
if ($LASTEXITCODE -ne 0 -or [version]$phpVersion -lt [version]'8.2') {
    throw "PHP 8.2 or newer is required. Found PHP $phpVersion."
}

try {
    New-Item -ItemType Directory -Path $scaffoldRoot | Out-Null

    & composer create-project laravel/laravel $scaffoldPath '11.*' --no-install --no-scripts --no-interaction
    if ($LASTEXITCODE -ne 0) {
        throw 'Laravel scaffold creation failed.'
    }

    $applicationDirectories = @(
        'app/Http', 'app/Models', 'app/Services', 'resources/views', 'routes'
    )
    foreach ($directory in $applicationDirectories) {
        $source = Join-Path $projectRoot $directory
        $destination = Join-Path $scaffoldPath $directory
        New-Item -ItemType Directory -Path $destination -Force | Out-Null
        Copy-Item (Join-Path $source '*') $destination -Recurse -Force
    }

    Copy-Item (Join-Path $projectRoot 'database/migrations/*') `
        (Join-Path $scaffoldPath 'database/migrations') -Recurse -Force
    Copy-Item (Join-Path $projectRoot 'database/seeders/*') `
        (Join-Path $scaffoldPath 'database/seeders') -Recurse -Force
    Get-ChildItem (Join-Path $scaffoldPath 'database/migrations') -Filter '*_create_users_table.php' |
        Remove-Item -Force

    $scaffoldCss = Join-Path $scaffoldPath 'public/css'
    New-Item -ItemType Directory -Path $scaffoldCss -Force | Out-Null
    Copy-Item (Join-Path $projectRoot 'public/css/*') $scaffoldCss -Recurse -Force

    $rootFiles = @(
        '.env.example', '.gitignore', 'artisan', 'composer.json', 'composer.lock',
        'package.json', 'phpunit.xml', 'vite.config.js'
    )
    foreach ($file in $rootFiles) {
        $source = Join-Path $scaffoldPath $file
        if (Test-Path $source) {
            Copy-Item $source $projectRoot -Force
        }
    }

    $directories = @(
        'app/Http', 'app/Models', 'app/Providers', 'app/Services', 'bootstrap',
        'config', 'database', 'public', 'resources/css', 'resources/js',
        'resources/views', 'routes', 'storage', 'tests'
    )
    foreach ($directory in $directories) {
        $source = Join-Path $scaffoldPath $directory
        $destination = Join-Path $projectRoot $directory
        New-Item -ItemType Directory -Path $destination -Force | Out-Null
        Copy-Item (Join-Path $source '*') $destination -Recurse -Force
    }

    Push-Location $projectRoot
    try {
        if (-not (Test-Path '.env')) {
            Copy-Item '.env.example' '.env'
        }

        if (-not (Test-Path 'database/database.sqlite')) {
            New-Item -ItemType File -Path 'database/database.sqlite' | Out-Null
        }

        & composer install --no-interaction
        if ($LASTEXITCODE -ne 0) {
            throw 'Composer dependency installation failed.'
        }

        & php artisan key:generate --force
        if ($LASTEXITCODE -ne 0) {
            throw 'Application key generation failed.'
        }

        & php artisan migrate --seed --force
        if ($LASTEXITCODE -ne 0) {
            throw 'Database migrations or seeders failed.'
        }

        & php artisan storage:link
        if ($LASTEXITCODE -ne 0) {
            Write-Warning 'Storage link creation failed. Complaint image uploads may need a manual storage link.'
        }
    }
    finally {
        Pop-Location
    }

    Write-Host 'Laravel 11 setup completed. Start the app with: php artisan serve'
}
finally {
    if (Test-Path $scaffoldRoot) {
        Remove-Item $scaffoldRoot -Recurse -Force
    }
}