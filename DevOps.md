# Azure DevOps

## Add Pull-Request check

First create a pipeline to your project. F.ex. `/pipeline/pull-req-test.yaml`. Then add new pipeline that uses this file.

Then from project settings select `Repositories` and select repository. Then select `Policies` tab and `Branch Policies`. Select the branch and add new policy. Select `Build validation` and select the pipeline that was created.

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

Note: These might not be minimum roles but these are enough roles to create Service Connection manually:

- DevOps: Project administrator
- Subscription: Owner
- AAD directory role: Global administrator or maybe Application developer is enough

Service Principal roles to be used with Service Connection:

- Reader role to subscription
- Contributor role to resource group

Note that DevOps task `AzureFileCopy@4` that is used to copy files to Blob storage requires explicitly `Storage Blob Data Contributor` or `owner role`.

### Service Principal Client secret expired - valid for 2 years

"Could not fetch access token for Azure. Verify if the Service Principal used is valid and not expired. For more information refer https://aka.ms/azureappservicedeploytsg"

First create new client secret:

1. In the Azure portal, in App registrations, select your application.
2. Select Certificates & secrets > Client secrets > New client secret.
3. Add a description for your client secret.
4. Select Add.

Then update the service connection in DevOps portal:

1. Go to Project settings > Service connections, and then select the service connection you want to modify.
2. Select Edit in the upper-right corner.
3. Select Save to save the service connection. Note: Don't try to verify the service connection at this step.
4. Exit the service connection edit window, and then refresh the service connections page.
5. Select Edit in the upper-right corner, and now select Verify.
6. Select Save to save your service connection.


## External developers to selected Git repository

- Add identities of those users to Azure AD tenant.

  - External guest access policy is needed.

- Create new Permissions group
  - Allow these two:
    - View project-level information: Allowed (inherited)
    - View analytics : Allowed (inherited)
  - And deny rest of the options

## Debug DevOps pipeline

Three levels: Stages, jobs and tasks.

Check folder contents with this script:

```yml
- script: |
    id
    ls -la
    pwd
  workingDirectory: "$(Build.StagingDirectory)"
```

## DevOps pipeline debugging with azure-pipelines.yml

```yml

trigger:
  - main

variables:
- name: 'artifactName'
  value: 'WebApp-$(Build.SourceBranchName)'
- name: isProd
  value: $[eq(variables['Build.SourceBranch'], 'refs/heads/main')]

# ...

stages:

# ...

    - task: PublishPipelineArtifact@1
      inputs:
        path: $(System.DefaultWorkingDirectory)/
        artifact: $(artifactName)

# ...

- stage: 'DeployProd'
  displayName: 'Deploy Prod'
  dependsOn: build
  condition: and(succeeded(), eq(variables.isProd, 'true'))
  jobs:

  - job: Deploy
    pool:
      vmImage: '$(deployVmImage)'
    steps:

    - download: current
      artifact: $(artifactName)
    # Downloads artifact to: /home/vsts/work/1/WebApp-main
    # which is $(Pipeline.Workspace)/$(artifactName)
    # Another location can be defined like this:
    - task: DownloadPipelineArtifact@2
      inputs:
        buildType: 'current'
        artifactName: '$(artifactName)'
        targetPath: '$(System.DefaultWorkingDirectory)'
    # Downloads artifact to: /home/vsts/work/1/s
    # which is $(System.DefaultWorkingDirectory)

    # Folders in pipeline:
    # $(Agent.WorkFolder):          /home/vsts/work
    # This folder contains Git-repository items:
    # $(System.DefaultWorkingDirectory): /home/vsts/work/1/s
    # $(Pipeline.Workspace):        /home/vsts/work/1
    # $(Build.StagingDirectory):    /home/vsts/work/1/a
    # $(Build.BinariesDirectory):   /home/vsts/work/1/b

    # Note that this task uses specifically $(System.DefaultWorkingDirectory) folder.
    # So artifact must be copied here.
    # Setting like this didn't work: app_location: '$(Pipeline.Workspace)/$(artifactName)/build'
    - task: AzureStaticWebApp@0
      inputs:
        app_location: '/build'
        azure_static_web_apps_api_token: $(deployment_token)
```

## Agent pools

Requires Personal Access Token with `Agent Pools (Read & manage)` rights.



## Git Submodule

Disable this Project Settings -> Pipelines -> Settings:
- `Protect access to repositories in YAML pipelines`

## Task snippets

Deploy to Azure Linux Web app. Note additional arguments that can be used to ignore folders (so they won't be removed if they are not included in artifact).

```yml
- task: AzureRmWebAppDeployment@4
  displayName: 'Azure App Service Deploy'
  inputs:
    azureSubscription: '<subscription name>'
    appType: 'webAppLinux'
    WebAppName: <web app name>
    ResourceGroupName: '<resource group name>'
    package: '$(Build.StagingDirectory)/*.zip'
    useWebDeploy: true
    deploymentType: 'zipDeploy'
    enableCustomDeployment: true
    RemoveAdditionalFilesFlag: true
    AdditionalArguments: '-retryAttempts:6 -retryInterval:10000 -skip:Directory=\\wp-content\\uploads -skip:Directory=\\wp-content\\themes\\my_theme\\assets\\images -skip:Directory=\\wp-content\\webp-express'

```

Deploy to Azure Windows Web app slot.

```yml
- task: AzureRmWebAppDeployment@4
  displayName: 'Azure App Service Deploy: s2hbackend'
  inputs:
    azureSubscription: '<subscription name OR service connection name>'
    appType: 'webApp'
    WebAppName: <web app name>
    DeployToSlotOrASEFlag: true
    ResourceGroupName: '<resource group name>'
    SlotName: 'staging'
    package: '$(Build.StagingDirectory)/*.zip'
    WebConfigParameters: '-Handler iisnode -NodeStartFile service.js -appType node'
    useWebDeploy: true
    deploymentType: 'webDeploy'
    enableCustomDeployment: true
    RemoveAdditionalFilesFlag: true

```

## Use secure files in pipeline

Add secure file in DevOps portal `Pipelines` -> `Library` -> `Secure files` -> `+ Secure file`. For example SSH key usage to backup files with SFTP in pipeline:

```yml
- task: DownloadSecureFile@1
  name: SSH_KEY
  displayName: 'Download secure file'
  inputs:
    secureFile: 'id_mykey'

- script: |
    mkdir .ssh
    cp $(SSH_KEY.secureFilePath) .ssh/id_rsa
    chmod 600 .ssh/id_rsa
  workingDirectory: "$(System.DefaultWorkingDirectory)"
  displayName: Copy SSH key

- script: |
    mkdir backup
    sftp -r -oStrictHostKeyChecking=no -oIdentityFile=.ssh/id_rsa $(SFTP_USER)@$(SFTP_HOST):. backup
  workingDirectory: "$(System.DefaultWorkingDirectory)"
  displayName: Backup files

- script: |
    rm -rf .ssh
  workingDirectory: "$(System.DefaultWorkingDirectory)"
  displayName: Remove SSH key

- script: |
    FILENAME=backup-$(date '+%Y-%m-%d').tar.gz
    tar cvfz $FILENAME backup
  workingDirectory: "$(System.DefaultWorkingDirectory)"
  displayName: Create archive
```
