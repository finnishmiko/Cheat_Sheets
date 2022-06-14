# Cheat Sheets

- [ATtiny13A](ATtiny13A.md)
- [Application Insights](ApplicationInsights.md)
- [Azure](Azure.md)
- [Chrome](Chrome.md)
- [Craft](Craft.md)
- [CSS](CSS.md)
- [Docker](docker.md)
- [ESP8266](ESP8266.md)
- [Git](git.md)
- [Java](Java.md)
- [JavaScript](JavaScript.md)
- [Linux](Linux.md)
- [MongoDB](MongoDB.md)
- [Nodejs](Nodejs.md)
- [PHP](PHP.md)
- [PhoneGap](PhoneGap.md)
- [Python](Python.md)
- [Raspberry](Raspberry.md)
- [React](React.md)
- [SQL](SQL.md)
- [WCAG](WCAG.md)
- [Wordpress](Wordpress.md)

# Env variables

```sh
# Set env variable
$env:APP_BACKEND_URL="http://localhost:3001/api/0.01/"

# Set env variable in Linux
export APP_BACKEND_URL="http://localhost:3001/api/0.01/"

# Set env variables to Apache server:
# Add following to .htaccess file and restart server
SetEnv APP_BACKEND_URL http://localhost:3001/api/0.01/

# Delete env variable
Remove-Item Env:\APP_BACKEND_URL

# See current env variables in windows:
Get-ChildItem env:

# See current env variables in Linux:
printenv
```

# SSH keys

In home `folder/.ssh` run:

```sh
ssh-keygen -t rsa
# Filename f.ex. id_rsa

# Check created public key:
more id_rsa.pub
```

Add public key to Virtual Machine's `.ssh` folder `authorized_keys` file.

```
cat id_rsa.pub > ~\.ssh\authorized_keys
```

In local machine `.ssh` folder add file `config` with content:

```sh
Host loginnametoserver
	User loginnametoserver
	Hostname test.server.com
```

```sh
# Now you can login to VM with
ssh loginnametoserver

# instead of
ssh loginnametoserver@test.server.com
```

# SSH2 key conversion

```sh
ssh-keygen -i -f Identity.pub > sshpub

# Word count should be 1
wc sshpub
```

# DNS

Note that it can take up to 48 hours for the DNS entry changes to propagate. That happened with Elisa and after 48h they saved the record again and it worked in 15 minutes.

### Custom domain (`example.com`) to Azure web app

```sh
# Add example.com to Azure web app custom domain list.

# DNS settings for example.com:

# For root domain: example.com
A @ IP address from Azure web app
TXT @ exampleapp.azurewebsites.net

# Subdomain www.example.com
# CNAME record
www
exampleapp.azurewebsites.net
```

Note that after this the site responds both to root domain and subdomain and also to azurewebsites.net domain. In Wordpress wp-config.php's WP_HOME and SITE_URL settings can be configured to direct all trafic to one site i.e. hard code one url.

### Other hosting companies

Note that f.ex. One.com doesn't support custom domains that are not in One.com.

# IIS `web.config` settings

Redirect http to https:

```conf
<rule name="HTTPSforce" enabled="true" stopProcessing="true">
    <match url="(.*)" />
    <conditions>
        <add input="{HTTPS}" pattern="OFF$" />
    </conditions>
    <action type="Redirect" url="https://{HTTP_HOST}/{R:1}" redirectType="Permanent" />
</rule>

<rule name="RedirectNonWwwToWww" stopProcessing="true">
    <match url="(.*)" />
    <conditions>
        <add input="{HTTP_HOST}" pattern="^domain.com$" />
    </conditions>
    <action type="Redirect" url="https://www.domain.com/{R:0}" redirectType="Permanent" />
</rule>


<rule name="Redirect to new domain" enabled="true">
    <match url="(.*)$" />
    <conditions trackAllCaptures="true">
        <add input="{HTTP_HOST}" negate="false" pattern="^(.*)\.foo\.com" />
    </conditions>
    <action type="Redirect" url="https://{C:1}.bar.com/{R:1}" appendQueryString="true" redirectType="Permanent" />
</rule>

<rule name="Redirect rquests to default azure websites domain" stopProcessing="true">
    <match url="(.*)" />
    <conditions logicalGrouping="MatchAny">
        <add input="{HTTP_HOST}" pattern="^yoursite\.azurewebsites\.net$" />
    </conditions>
    <action type="Redirect" url="http://www.yoursite.com/{R:0}" />
</rule>

<rule name="Redirect to non-www" stopProcessing="true">
    <match url="(.*)" negate="false"></match>
    <action type="Redirect" url="http://domain.com/{R:1}"></action>
    <conditions>
        <add input="{HTTP_HOST}" pattern="^domain\.com$" negate="true"></add>
    </conditions>
</rule>


<rule name="All HTTP to HTTPS+WWW" stopProcessing="true">
    <match url=".*" />
    <conditions trackAllCaptures="true">
        <add input="{SERVER_PORT_SECURE}" pattern="0" />
        <add input="{HTTP_HOST}" pattern="(?:localhost|stage\.|dev\.)" negate="true" />
        <!-- here with this 3rd condition we capture the host name without "www." prefix into {C:1} variable to use in redirect action -->
        <add input="{HTTP_HOST}" pattern="^(?:www\.)?(.+)" />
    </conditions>
    <action type="Redirect" url="https://www.{C:1}/{R:0}" appendQueryString="true" redirectType="Permanent" />
</rule>

<rule name="All HTTPS With No WWW to HTTPS+WWW" stopProcessing="true">
    <match url=".*" />
    <conditions trackAllCaptures="false">
        <add input="{SERVER_PORT_SECURE}" pattern="1" />
        <add input="{HTTP_HOST}" pattern="(?:localhost|stage\.|dev\.)" negate="true" />
        <add input="{HTTP_HOST}" pattern="^www\." negate="true" />
    </conditions>
    <action type="Redirect" url="https://www.{HTTP_HOST}/{R:0}" appendQueryString="true" redirectType="Permanent" />
</rule>

```

Define allowed media types.

```conf
<staticContent>
    <mimeMap fileExtension="woff" mimeType="application/font-woff" />
    <mimeMap fileExtension="woff2" mimeType="application/font-woff2" />
    <mimeMap fileExtension=".mp4" mimeType="video/mp4" />
    <mimeMap fileExtension=".m4v" mimeType="video/m4v" />
    <mimeMap fileExtension=".jpg" mimeType="image/jpeg" />
    <mimeMap fileExtension=".jpeg" mimeType="image/jpeg" />
    <mimeMap fileExtension=".webp" mimeType="image/webp" />
    <mimeMap fileExtension=".png" mimeType="image/png" />
    <mimeMap fileExtension=".gif" mimeType="image/gif" />
    <mimeMap fileExtension=".svg" mimeType="image/svg+xml" />
    <mimeMap fileExtension=".css" mimeType="text/css" />
    <mimeMap fileExtension=".js" mimeType="text/javascript" />
    <clientCache cacheControlCustom="private" cacheControlMode="UseMaxAge" cacheControlMaxAge="7.00:00:00" />
</staticContent>
```

Use webp images if awailable.

```conf
<rewrite>
    <rules>
        <rule name="Redirect existing converted images to WEBP if available" enabled="true" stopProcessing="true">
            <match url="(.*)\.(jpe?g|png)$" />
            <conditions logicalGrouping="MatchAll" trackAllCaptures="true">
                <add input="{HTTP_ACCEPT}" pattern="image/webp" />
                <add input="{REQUEST_FILENAME}.webp" matchType="IsFile" />
            </conditions>
            <action type="Rewrite" url="{R:1}.{R:2}.webp" />
        </rule>
    </rules>
</rewrite>
```

Security options.

```conf
<security>
    <requestFiltering removeServerHeader="true" />
</security>

<httpProtocol>
    <customHeaders>
        <remove name="X-Powered-By" />
    </customHeaders>
</httpProtocol>
```

# WAMP server

Default setting is such that `C:\wamp64\www` folder is `http://localhost` and `C:\wamp64\www\project1` folder is `http://localhost/project1`.

To change project address to `http://www.project1.test` following settings are needed.

1. Edit hosts file in `C:\Windows\System32\drivers\etc\hosts` and add following line:

```sh
127.0.0.1 www.project.test

# Existing setting in hosts file is:
127.0.0.1 localhost
```

2. Then edit Apache's `httpd-vhosts.conf` file (can be accessed from Apache icon) and add following settings:

```sh
<VirtualHost *:80>
  ServerName project1
  ServerAlias www.project1.test
  DocumentRoot "${INSTALL_DIR}/www/project1"
  <Directory "${INSTALL_DIR}/www/project1/">
    Options +Indexes +Includes +FollowSymLinks +MultiViews
    AllowOverride All
    Require local
  </Directory>
</VirtualHost>
```

3. If some folder is outside the project root, alias can be created for it (to conf file above)

```sh
  Alias /static "${INSTALL_DIR}/www/build"
  <Directory "${INSTALL_DIR}/www/build">
    AllowOverride all
    Require all granted
  </Directory>
```

# PowerShell commands

Check DNS-records:

```powershell
nslookup -type=TXT test.com
nslookup -type=CNAME test.com
nslookup -type=A test.com

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

```PowerShell
# Bash: cat FILENAME -tail 200
# -Wait means "follow"
Get-Content FILENAME -tail 200 -Wait

Rename-Item .\oldname.txt .\newname.txt
```
