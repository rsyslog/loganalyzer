# Requires: Windows PowerShell 5.1+ (uses -LiteralPath).
# Creates repo-root .env from docker/env.example with interactive prompts.
param(
    [Parameter(Mandatory = $true)]
    [string] $RepoRoot
)

$RepoRoot = (Resolve-Path -LiteralPath $RepoRoot).Path
$Example = Join-Path $RepoRoot 'docker\env.example'
$Out = Join-Path $RepoRoot '.env'

if (Test-Path -LiteralPath $Out) {
    Write-Host ".env already exists: $Out"
    exit 0
}
if (-not (Test-Path -LiteralPath $Example)) {
    Write-Error "Missing or unreadable: $Example"
    exit 1
}

Write-Host ''
Write-Host '[.env missing] Press Enter at each prompt to keep the default from docker\env.example.'
Write-Host ''

$list = New-Object System.Collections.Generic.List[string]

Get-Content -LiteralPath $Example | ForEach-Object {
    $line = $_ -replace "`r$", ''
    if ($line -match '^\s*#' -or [string]::IsNullOrWhiteSpace($line)) {
        $list.Add($line) | Out-Null
        return
    }
    if ($line -match '^([A-Za-z_][A-Za-z0-9_]*)=(.*)$') {
        $key = $matches[1]
        $def = $matches[2]
        if ($key -match 'PASSWORD') {
            $prompt = "${key} (Enter keeps default '$def'): "
            # -MaskInput requires PowerShell 7.1+ (not 7.0).
            if ($PSVersionTable.PSVersion.Major -gt 7 -or ($PSVersionTable.PSVersion.Major -eq 7 -and $PSVersionTable.PSVersion.Minor -ge 1)) {
                $val = Read-Host $prompt -MaskInput
            }
            else {
                $val = Read-Host $prompt
            }
        } else {
            $val = Read-Host "${key} [$def]: "
        }
        if ([string]::IsNullOrWhiteSpace($val)) { $val = $def }
        $list.Add("${key}=${val}") | Out-Null
    } else {
        $list.Add($line) | Out-Null
    }
}

$content = ($list -join [Environment]::NewLine) + [Environment]::NewLine

# UTF-8 without BOM (same as typical .env tools expect)
$utf8NoBom = New-Object System.Text.UTF8Encoding $false
[System.IO.File]::WriteAllText($Out, $content, $utf8NoBom)

Write-Host ''
Write-Host "[.env written] $Out"
