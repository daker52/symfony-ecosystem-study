$git = "C:\Program Files\Git\cmd\git.exe"
$repo = "C:\Users\Hak\Projects\symfony-ecosystem-study"
Set-Location $repo
& $git add -A
$status = & $git status --porcelain
if (-not $status) { Write-Output "Nic k commitu"; exit 0 }
$env:GIT_AUTHOR_NAME = "daker52"
$env:GIT_COMMITTER_NAME = "daker52"
$env:GIT_AUTHOR_EMAIL = "daker52@users.noreply.github.com"
$env:GIT_COMMITTER_EMAIL = "daker52@users.noreply.github.com"
$tree = & $git write-tree
$parent = (& $git rev-parse HEAD).Trim()
$new = (& $git commit-tree $tree -p $parent -m "docs: uprava aha poznatky").Trim()
& $git reset --hard $new
& $git log -1 --oneline
