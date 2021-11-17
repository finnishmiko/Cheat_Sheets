# Linux

Check an SSL connection and certificate:

```bash
echo | openssl s_client -servername www.domaintocheck.fi -connect www.domaintocheck.fi:443 -showcerts| openssl x509 -noout -dates
```
