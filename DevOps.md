# Azure DevOps

## Service connection to Azure resources with Service Principal

From DevOps portal select `Settings`, `Service connections` and click `New service connection`. Select `Azure Resource Manager` option and for Service Principal there are two options:

- Service principal (automatic)
    - This creates new Azure Active Directory App registration.
    - App is created to same tenant where DevOps is.
    - App is given access to the subscription and Azure resource.
- Service principal (manual)
    - This uses existing Azure Active Directory App registration. Or one that is manually created for this.
    - App can be in different tenant than DevOps.
    - App access needs to be set manually: 
        - Reader role to subscription
        - Contributor role to resource group

App registration secret is valid for two years. After that it needs to be verified again: In DevOps portal edit the service connection and select Verify.

Created App registration can be seen in Azure portal `Azure Active Directory`'s `App registration` tab. Note that this is hidden to users by default. Visibility can be changed from AAD's `Enterprice applications`.

### Roles required

Note: These might not be minimum roles but these are enough roles.

- DevOps: Project administrator
- Subscription: Owner
- AAD directory role: Global administrator or maybe Application developer is enough
