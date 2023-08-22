# Powershell

- default version 5.1
- latest version 7.2

## Az-module

Install Az-module to Powershell 7.2.

```Powershell
Connect-AzAccount -TenantId <tenant id>

# Get Service Principal ID
$spId = (Get-AzADServicePrincipal -DisplayName '<Service Principal display name>').id
echo $spId;

# Assign new role to SP
New-AzRoleAssignment -ObjectId $spId -RoleDefinitionName "Reader" -Scope "/subscriptions/<subscription id>"
New-AzRoleAssignment -ObjectId $spId -RoleDefinitionName "Contributor" -Scope "/subscriptions/<subscription id>/resourceGroups/<resource group name>"

# Assign new role to SP
New-AzRoleAssignment -ObjectId $spId -RoleDefinitionName "Storage Blob Data Contributor" -Scope "/subscriptions/<subscription id>/resourceGroups/<resource group name>/providers/Microsoft.Storage/storageAccounts/<storage account name>"

# This doesn't work:
Get-AzRoleAssignment -ObjectId $spId
```

## Azure CLI

Install Azure CLI

```Powershell
az login --tenant <tenant id>
az account set --subscription <subscription id>

## Check all roles:
az role assignment list --all

## Example outputs:

## Reader role to subscription
    # principalType: ServicePrincipal
    # roleDefinitionName: Reader
    # scope: /subscription/<subscription id>

## Contributor role to resource group
    # principalType: ServicePrincipal
    # resourceGroup: <name>
    # roleDefinitionName: Contributor
    # scope: /subscription/<subscription id>/resourceGroups/<resource group name>

## Storage Blob Data Contributor role to Blob storage
    # principalType: ServicePrincipal
    # resourceGroup: <name>
    # roleDefinitionName: Storage Blob Data Contributor
    # scope: "/subscriptions/<subscription id>/resourceGroups/<resource group name>/providers/Microsoft.Storage/storageAccounts/<storege account name>"

# Check service principal's role with scope:
az role assignment list --assignee <principalID> --scope <scope to check>

# Create new role to scope (if missing)
az role assignment create --role "Reader" --assignee "<principalId>" --description "<add text description>" --scope "/subscriptions/<subscription id>"

az role assignment create --role "Contributor" --assignee "<principalId>" --description "<add text description>" --scope "/subscriptions/<subscription id>/resourceGroups/<resource group name>"

# Create new role to scope (if missing)
az role assignment create --role "Storage Blob Data Contributor" --assignee "<principalId>" --description "<add text description>" --scope "/subscriptions/<subscription id>/resourceGroups/<resource group name>/providers/Microsoft.Storage/storageAccounts/<storage account name>"

############################
## Check Web app PHP version

az webapp config show --resource-group <resource-group-name> --name <app-name> --query linuxFxVersion

```


# PowerShell commands


```PowerShell
# Bash: cat FILENAME -tail 200
# -Wait means "follow"
Get-Content FILENAME -tail 200 -Wait

Rename-Item .\oldname.txt .\newname.txt

# pwd
Get-Location

# cd - change directory
Push-Location

```


Calculate size of uploads folder and subfolders

```PowerShell
"{0} MB" -f ((Get-ChildItem uploads\ -Recurse | Measure-Object -Property Length -Sum -ErrorAction Stop).Sum / 1MB)
```

Check DNS-records:

```powershell
nslookup -type=TXT test.com
nslookup -type=CNAME test.com
nslookup -type=A test.com
nslookup -type=MX test.com

Resolve-DnsName test.com
```

```powershell
Get-NetIPAddress

TRACERT.EXE 192.168.1.1

```

```PowerShell
(Get-ChildItem -Path c:\pstbak\*.* -Filter *.pst | ? {
  $_.LastWriteTime -gt (Get-Date).AddDays(-3)
}).Count
```

```PowerShell
(Get-ChildItem -Path *.* -Filter *.pst | ? { $_.LastWriteTime -gt (Get-Date).AddDays(-1) }).Count

Get-ChildItem -Path . -Recurse| ? {$_.LastWriteTime -gt (Get-Date).AddDays(-4)}
```

List all files in subfolders

```PowerShell
Get-ChildItem -File -Path . -Recurse | Sort-Object -Property Length | Select-Object -Property Length, FullName | Format-Table -AutoSize
```

Display only larget than 3 MB filesFilter with size and save output to file:

```PowerShell
Get-ChildItem -File -Path . -Recurse | where Length -gt 3mb | Sort-Object -Property Length | Select-Object -Property Length, FullName | Format-Table -AutoSize | Out-File -FilePath C:\temp\output.txt
```

## Move files recursively

```PowerShell



$sourcePath = "C:\source"
$destPath = "C:\destination"
Write-Host "Moving all files in '$($sourcePath)' to '$($destPath)'"
$fileList = @(Get-ChildItem -Path "$($sourcePath)" -File -Recurse)
$directoryList = @(Get-ChildItem -Path "$($sourcePath)" -Directory -Recurse)
ForEach($directory in $directoryList){
$directories = New-Item ($directory.FullName).Replace("$($sourcePath)",$destPath) -ItemType Directory -ea SilentlyContinue | Out-Null
}
Write-Host "Creating Directories"
ForEach($file in $fileList){
try {
Move-Item -Path $file.FullName -Destination ((Split-Path $file.FullName).Replace("$($sourcePath)",$destPath)) -Force -ErrorAction Stop
}
catch{
Write-Warning "Unable to move '$($file.FullName)' to '$(((Split-Path $file.FullName).Replace("$($sourcePath)",$destPath)))': $($_)"
return
}
}
Write-Host "Deleting folder '$($sourcePath)'"
Remove-Item -Path "$($sourcePath)" -Recurse -Force -ErrorAction Stop
```
