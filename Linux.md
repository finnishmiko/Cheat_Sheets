# Linux

Check an SSL connection and certificate:

```bash
echo | openssl s_client -servername www.domaintocheck.fi -connect www.domaintocheck.fi:443 -showcerts| openssl x509 -noout -dates
```

Local IP address:
```bash
hostname -I
```

Check the kernel version:
```bash
uname -r
```


## .htaccess

^ is the start of the url.
$ is the end of the url.
. is any character.
* means 0 or more of any character.
+ means 1 or more of any character.
