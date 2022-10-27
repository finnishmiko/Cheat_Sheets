# Azure

- [Tenant](#Tenant)
- [azcopy](#azcopy)
- [Key Vault](#Key-Vault)
  - [CSR (certificate signing request)](<#CSR-(certificate-signing-request)>)
  - [Renew a nonintegrated CA certificate](#Renew-a-nonintegrated-CA-certificate)
- [Web app with Kudu deployment](#Web-app-with-Kudu-deployment)
- [Azure web app deployment slots](#Azure-web-app-deployment-slots)
- [In-app MySQL](#In-app-MySQL)
- [Create VM with Azure CLI](#Create-VM-with-Azure-CLI)
- [Settings file example](#Settings-file-example)
  - [configure-iis.ps1](#configure-iis.ps1)
- [Open port](#Open-port)
- [Resize VM](#Resize-VM)
- [App Service Managed Certificate](#App-Service-Managed-Certificate)
- [Static web app to Storage account and SSL certificate with Azure CDN](#Static-web-app-to-Storage-account-and-SSL-certificate-with-Azure-CDN)
- [Static web app](#Static-web-app)
- [Deploy React Web App from local Git repository](#Deploy-React-Web-App-from-local-Git-repository)
- [Chrome console says it can't load `manifest.json` file](#Chrome-console-says-it-can't-load-`manifest.json`-file)
- [Deploy Node Express Web App from local Git repository](#Deploy-Node-Express-Web-App-from-local-Git-repository)

## Tenant

- A tenant represents an organisation
- It's an instance of Azure AD.
- In the AAD tenant apps are registered and managed and their access to data is configured.
- First user account in tenant has role Global admin.

## azcopy

Add SAS-token to the commands or to variable.

```PowerShell
# Set access token to variable:
export BLOB_KEY="?sv=..."

# Sync all files and subfolders to blob storage static web app folder:
azcopy.exe sync '.\build' 'https://yourstorageaccountname.blob.core.windows.net/$web?sv=...'

# Sync all files from local to Blob storage container's folder:
azcopy.exe sync 'path\to\folder' 'https://<blobstorage>.blob.core.windows.net/<blob container>/<blob folder>${BLOB_KEY}'

# Sync all files from Blob storage to local folder
azcopy.exe sync "https://<blobstorage>.blob.core.windows.net/<blob container>/<blob folder>${BLOB_KEY}" /uploads --recursive=true --delete-destination=true
```

Copy all files and subfolders to blob storage

```PowerShell
azcopy.exe copy '.\build\*' 'https://yourstorageaccountname.blob.core.windows.net/$web?sv=...' --recursive
```

## Curl log stream

This is run with Bash terminal not Powershell. Or atleast check which curl is used in Powershell. Probably it is alias to Invoke-WebRequest cmdlet and it doesn't work with it:

```PowerShell
Get-Command curl
```

- Username and password can be found from Web App's Overview tab from Get publish profile button.
- Url can be found from Kudu's front page: Log streaming (use curl, not browser!)

```bash
curl -u '$username:password' https://websitename.scm.azurewebsites.net/api/logstream
```

## Key Vault

### CSR (certificate signing request)

1. Go to Key Vault certificates tab and create .csr file from `Generate/import`.
2. Send this file to CA for the request to get signed.
3. Then bring .cer file back and `merge the Signed request` which enables the certificate.

Now you can use this certificate f.ex. in Azure Web App. Go to `TLS/SSL settings` and from `Private key Certificates` use `Import Key Vault Certificate` button.

### Renew a nonintegrated CA certificate

1. From the Key vault Certificates tab open the certificate.
2. Click New Version button and then Create.
3. From the Certificate Operation tab CSR-file can be downloaded. Send this to CA for signing.
4. When you get .cer file back use Merge Signed Request option.

If the old certificate is not expired yet, it will be used until expiry. This even when the Azure shows that the current version is the newer certificate.

## Web app with Kudu deployment

There are two different setups to do it:

1. Git repository is in /site/repository and then files are copied to /site/wwwroot with KuduSync.
2. Git repository is in /site/wwwroot folder and no separate file copying is needed.

- This "deploying inplace" is done with env variable: SCM_REPOSITORY_PATH="wwwroot"
- This setup is needed f.ex. with Wordpress where php-files are modified during updates. Remember to add and commit these changes to the Git from Azure side.

## Azure web app

- WebSocket connections: Standard size ASP has unlimited but Basic limits them to 350.
- Always On requires Basic size.
- Windows version
  - Application logs are not stored to Blob storage

### Azure web app deployment slots

Note Windows server only at this moment.

1. Add slot 'Staging' and clone settings from main app. This creates empty web app.

- Also add slot specific env variables to Azure Configuration. F.ex. WP_ENV="staging"

2. Add Local Git repository from Azure Deployment Center
3. Use this git as a remote called 'staging' in local development folder and push files to with `git push staging master`

- in case of Wordpress update permalinks twice at this point.

Then do development with local and staging environments.

When production site update is needed it can be done by swapping deployment slots. In case there are problems slots can be swapped back immediately.

- Note that Wordpress may require permalink update thing...

**Note about web jobs:** Web jobs are copied from production slot as is. So if there is some production site url dependent task, the web job in staging will still use production urls.

## In-app MySQL

Connection string can be found from Kudu: `D:\home\data\mysql\MYSQLCONNSTR_localdb.ini`

PhpMyAdmin url is Kudu url/phpmyadmin f.ex.: `https://testwebsite.scm.azurewebsites.net/phpmyadmin`

## Create VM with Azure CLI

```
USERNAME=azureuser
PASSWORD=$(openssl rand -base64 32)

az vm create \
  --name myVM \
  --resource-group My-resource-group-name \
  --image Win2019Datacenter \
  --size Standard_DS2_v2 \
  --location eastus \
  --admin-username $USERNAME \
  --admin-password $PASSWORD

az vm get-instance-view \
  --name myVM \
  --resource-group My-resource-group-name \
  --output table
```

## Settings file example

```
az vm extension set \
  --resource-group My-resource-group-name \
  --vm-name myVM \
  --name CustomScriptExtension \
  --publisher Microsoft.Compute \
  --settings "{'fileUris':['https://raw.githubusercontent.com/MicrosoftDocs/mslearn-welcome-to-azure/master/configure-iis.ps1']}" \
  --protected-settings "{'commandToExecute': 'powershell -ExecutionPolicy Unrestricted -File configure-iis.ps1'}"
```

### configure-iis.ps1

```
# Install IIS.
dism /online /enable-feature /featurename:IIS-WebServerRole

# Set the home page.
Set-Content `
  -Path "C:\\inetpub\\wwwroot\\Default.htm" `
  -Value "<html><body><h2>Welcome to Azure! My name is $($env:computername).</h2></body></html>"
```

## Open port

```
az vm open-port \
  --name myVM \
  --resource-group My-resource-group-name \
  --port 80

az vm show \
  --name myVM \
  --resource-group My-resource-group-name \
  --show-details \
  --query [publicIps] \
  --output tsv

# Then visit the IP address with browser
```

## Resize VM

```
az vm resize \
  --resource-group My-resource-group-name \
  --name myVM \
  --size Standard_DS3_v2

az vm show \
  --resource-group My-resource-group-name \
  --name myVM \
  --query "hardwareProfile" \
  --output tsv
```

## App Service Managed Certificate

Note that subdomain's CNAME needs to point to the Azure web app so that the certificate can be renewed.

## Static web app to Storage account and SSL certificate with Azure CDN

1. Create storage account (general-purpose v2)
2. Enable static web page and set index document name to `index.html`
   - Also 404-page document can be specified
3. Then copy static web site files to `$web` folder
   - The web page is then hosted at https://mywebpage.z16.web.core.windows.net/
   - Only https is used
4. Custom domain

   - Currently https is not supported with custom domain to storage account
   - HTTPS can be enabled with Azure CDN. However CDN does not support root domain (mywebpage.com) - hostname needs to be used (www.mywebpage.com)

   1. Create CDN profile (Standard Verizon)
   2. Add endpoint to storage account
   3. Endpoint origin is `Custom origin` that poins to `mywebpage.z16.web.core.windows.net`
   4. Then the web page is available from `https://mywebpage.azureedge.net`
   5. Map custom domain to this endpoint by adding CNAME record to the DNS provider's web site. And after that add it to the CDN endpoint.
   6. Enable HTTPS - this takes many hours

   - At this point HTTPS works, but HTTP does not. HTTP can be enabled from Storage account configuration and disabling Secure transfer requirement.
   - Redirecting HTTP to HTTPS requires Azure CDN premium from Verizon.

Note that default caching rules is set to 7 days expiration. At least `service-worker.js` should be bypassed so that new SW version is recognized. Also note that the UI indicator needs to be implemented to React code.

Cache at Azure CDN can be cleared from Azure Portal using Purge feature.

## Static web app

Custom domain sertificate auto renewal uses http-token method. And for that to work add `.well-known/pki-validation/fileauth.txt` file, so that digicert can validate the domain.

## Deploy React Web App from local Git repository

1. Create new _Azure Web app_ using Azure Portal

- From the `Deployment options` select `Local Git Repository`
  - `Deployment credentials` are needed to log into the Azure Repository

2. Create React app to your local computer: `create-react-app appname`
3. Build the app to create an optimized production build in build-folder: `npm run build`

- Locally this can be tested using f.ex. Atom's development server or using Node package `serve`

3. Change directory into build folder and create a Git repository for Azure and add and commit all files:

```sh
git init
git add .
git commit -m "Initial Azure commit"
```

4. Add Azure remote to your local repository

- From the Azure Portal **Settings > Properties** find **GIT URL** (`https://<username>@<webappname>.scm.azurewebsites.net:443/webappname.git`)
- And add that as a remote:
  `git remote add azure https://<username>@<webappname>.scm.azurewebsites.net:443/webappname.git`

5. Push your changes to Azure:
   `git push azure master`

- You will be prompted for the username and password created earlier.
- If you want to push other branch than master:
  `git push azure dev:master`

6. Visit your app at `webappname.azurewebsites.net`

## Chrome console says it can't load `manifest.json` file

To fix that `web.config` file needs to be added to to `wwwroot` folder ([sourse](https://stackoverflow.com/questions/48137750/azure-web-app-does-not-load-json-file)):

```xml
<?xml version="1.0" encoding="utf-8"?>
<configuration>
  <system.webServer>
    <staticContent>
      <remove fileExtension=".json"/>
      <mimeMap fileExtension=".json" mimeType="application/json"/>
    </staticContent>
  </system.webServer>
</configuration>
```

## Deploy Node Express Web App from local Git repository

Build font end code and serve it as static files with Express.

Add to package.json backend proxy:

```js
"proxy": "http://localhost:4000",
```

Few additions to the React guide:

- Node default version is 0.10 so it needs to be updated.
- Add to the `package.json`:

```js
"engines": {
"node": "8.5.0",
"npm": "4.2.0"
},
```

- Create `iisnode.yml` with content:

```sh
nodeProcessCommandLine: "D:\Program Files (x86)\nodejs\8.5.0\node.exe"
```

- To the Azure Portal _Application Settings_ add `WEBSITE_NODE_DEFAULT_VERSION` as a key and value is the version `8.5.0`

- If you have used Bower for client side packages they need to be installed manually. Go with web brower to `webappname.scm.azurewebsites.net`. Select `CMD` from _Debug console_ and go to the folder `site/wwwroot` and run `bower install`
