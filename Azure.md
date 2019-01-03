# Azure

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

## Deploy React Web App from local Git repository

1. Create new _Azure Web app_ using Azure Portal
* From the `Deployment options` select `Local Git Repository`
    * `Deployment credentials` are needed to log into the Azure Repository

2. Create React app to your local computer: `create-react-app appname`
3. Build the app to create an optimized production build in build-folder: `npm run build`
  * Locally this can be tested using f.ex. Atom's development server or using Node package `serve`
3. Change directory into build folder and create a Git repository for Azure and add and commit all files:
```sh
git init
git add .
git commit -m "Initial Azure commit"
```
4. Add Azure remote to your local repository
  * From the Azure Portal __Settings > Properties__ find __GIT URL__ (`https://<username>@<webappname>.scm.azurewebsites.net:443/webappname.git`)
  * And add that as a remote:
`git remote add azure https://<username>@<webappname>.scm.azurewebsites.net:443/webappname.git`

5. Push your changes to Azure:
`git push azure master`
  * You will be prompted for the username and password created earlier.

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
