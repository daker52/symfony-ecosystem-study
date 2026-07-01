# Rychlá kontrola PHP pro demo (Windows)

$phpDir = "$env:LOCALAPPDATA\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
$php = Join-Path $phpDir "php.exe"

if (-not (Test-Path $php)) {
    Write-Host "PHP 8.3 (winget) nenalezeno. Nainstaluj: winget install PHP.PHP.8.3" -ForegroundColor Red
    exit 1
}

Write-Host "PHP: $php"
& $php -m | Select-String "pdo_sqlite|sqlite3"

$mods = & $php -m
if ($mods -notcontains "pdo_sqlite") {
    Write-Host ""
    Write-Host "CHYBI pdo_sqlite — v php.ini odkomentuj:" -ForegroundColor Red
    Write-Host "  extension=pdo_sqlite"
    Write-Host "  extension=sqlite3"
    Write-Host "Soubor: $phpDir\php.ini"
    exit 1
}

Write-Host ""
Write-Host "OK — SQLite driver je k dispozici." -ForegroundColor Green
Write-Host "Spust server: symfony server:stop; symfony server:start"
