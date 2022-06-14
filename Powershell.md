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
New-AzRoleAssignment -ObjectId $spId -RoleDefinitionName "Storage Blob Data Contributor" -Scope "/subscriptions/<subscription id>/resourceGroups/<resource group name>/providers/Microsoft.Storage/storageAccounts/<storage account name>"

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
az role assignment create --role "Storage Blob Data Contributor" --assignee "<principalId>" --description "<add text description>" --scope "/subscriptions/<subscription id>/resourceGroups/<resource group name>/providers/Microsoft.Storage/storageAccounts/<storage account name>"
```
