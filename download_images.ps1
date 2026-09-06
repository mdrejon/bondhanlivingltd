$baseUrl = "https://html.themehour.net/konta/demo/"
$files = Get-ChildItem -Filter *.html | Select-Object -ExpandProperty Name
$imagePaths = @()

foreach ($file in $files) {
    $content = Get-Content $file -Raw
    $pattern1 = '(?i)(?:src|data-bg-src|href)="([^"]+\.(?:jpg|png|svg|jpeg|webp|gif|ico))"'
    $matches1 = [regex]::Matches($content, $pattern1)
    
    foreach ($match in $matches1) {
        $imgPath = $match.Groups[1].Value
        $imgPath = $imgPath -replace "^/", ""
        $cleanPath = $imgPath -replace "//", "/"
        if ($cleanPath -notmatch "^http") {
             if ($imagePaths -notcontains $cleanPath) {
                 $imagePaths += $cleanPath
             }
        }
    }
}

$imagePaths = $imagePaths | Select-Object -Unique

$total = $imagePaths.Length
$count = 0
foreach ($imgPath in $imagePaths) {
    $count++
    $url = $baseUrl + $imgPath
    
    # Fix paths that might contain extra slashes or invalid chars
    $normalizedPath = $imgPath.Replace('/', '\')
    $localPath = Join-Path (Get-Location).Path $normalizedPath
    
    $dir = Split-Path $localPath
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Force -Path $dir | Out-Null
    }
    
    if (-not (Test-Path $localPath) -or (Get-Item $localPath).Length -eq 0) {
        Write-Host "[$count/$total] Downloading $url to $localPath"
        try {
            Invoke-WebRequest -Uri $url -OutFile $localPath -UseBasicParsing -TimeoutSec 15
        } catch {
            Write-Host "Failed to download $url : $($_.Exception.Message)"
        }
    } else {
        Write-Host "[$count/$total] Exists: $localPath"
    }
}
Write-Host "Finished!"
