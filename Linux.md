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

## Ubuntu install

Install desktop version with minimal settings.

```sh
sudo apt install netstat
# sudo apt install ufw
# sudo apt remove ufw
sudo apt openssh-server
sudo apt install make
```

```sh
sudo netstat -ntlp
# sudo systemctl status ufw
sudo iptables -L -nv

```

```sh
ssh-copy-id -i ~/.ssh/id_rsa.pub user@host
```

## Curl

### Debug why image is not visible in Twitter sharing

Check with curl that the meta tags are set correctly:

```bash
curl -v -A Twitterbot https://address/to/check >> save-to-file.txt
```

## .htaccess

^ is the start of the url.
$ is the end of the url.
. is any character.

- means 0 or more of any character.

* means 1 or more of any character.

```
#!/bin/bash
# Target directory
TARGET=/target/directory/here

git diff --name-only HEAD HEAD^1 | while read file
do
    echo $file
    # First create the target directory, if it doesn't exist.
    mkdir -p "$TARGET/$(dirname $file)"
    # Then copy over the file.
    cp -rf "$file" "$TARGET/$file"
done
```
