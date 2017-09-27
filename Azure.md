# Azure

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
