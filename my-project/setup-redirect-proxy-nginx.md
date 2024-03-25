## How to setup ip public & subdomain on Centos 7 (Include with proxy server using NGINX) Millio247 Mobile

## Environment

- Centos 7
- Nginx
- Docker
- Millio247 branch 2.1.0

# Step

1. Add new config network file eth0:1 on path `/etc/sysconfig/network-scripts/`

template config ip public

```
DEVICE=eth0:1
Type=Ethernet
ONBOOT=yes
NM_CONTROLLED=no
BOOTPROTO=none
IPADDR=
PREFIX=
GATEWAY=
DNS1=
DNS2=
```

2. Change port from 22 to another port (example 2234, 2235, etc)

change port on config file sshd_config `/etc/ssh/sshd_config`

```
before

uncomment port 22

# port 22

change to port 2273

after

port 2273
```

3. restart network and sshd service

```
systemctl restart network

systemctl restart sshd
```

4. Enable proxy server using nginx, because it was using ip public & subdomain, so needed for enable proxy server

create config proxy on path `/etc/nginx/conf.d/<namefile.conf>`

add config on file namefile.conf for proxy server

```
server {
   client_max_body_size 2000M;
   listen 443 ssl http2;
   server_name <name-subdomain>;
   # ssl_certificate /etc/letsencrypt/live/chanthel.direduce.org/fullchain.pem; # managed by Certbot
   # ssl_certificate_key /etc/letsencrypt/live/chanthel.direduce.org/privkey.pem; # managed by Certbot
   ssl_certificate /etc/nginx/cert/<your-file>.crt;
   ssl_certificate_key /etc/nginx/cert/<your-file>.key;
   # ssl_dhparam /home/ubuntu/ssl/dhparams.pem;

   # intermediate configuration
   ssl_protocols TLSv1.3;
   ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;
   ssl_prefer_server_ciphers off;

   # HSTS (ngx_http_headers_module is required) (63072000 seconds)
   add_header Strict-Transport-Security "max-age=63072000" always;

   # OCSP stapling
   ssl_stapling on;
   ssl_stapling_verify on;

   access_log /var/log/nginx/millio247_access_log;
   error_log  /var/log/nginx/millio247_error_log;

   location / {
      rewrite /(.*) /$1 break;
      proxy_redirect   off;
      proxy_set_header Host $host;
      proxy_set_header X-Forwarded-Host $host;
      proxy_set_header X-Forwarded-Server $host;
      proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_set_header HTTPS "on";

      proxy_pass http://<ip-add-local:port>;
   }

}

```
note :

untuk `ssl_certificate` dan `ssl_certificate_key` perlu insert file crt & key & perlu req terlebih dahulu

5. needed restart nginx.service

```
systemctl restart nginx
```
6. disable MAC used in ssh on file sshd_config `/etc/ssh/sshd_config`

```
before

Ensure strong MAC is used in ssh

after

#Ensure strong MAC is used in ssh
```

7. restart sshd.service

```
systemctl restart sshd
```

8. add config `net.ipv4.ip_forward=1` on path file `/etc/sysctl.conf`

```
net.ipv4.ip_forward=1
```

9. restart network, config sysctl, because forwarding routing disable on ipv4

```
systemctl restart network

sysctl net.ipv4.ip_forward
```
10. Adduser another root, and create those user with role sudoers

11. Change port 80 from nginx.conf to another port (bebas port) -> tujuannya agar pertama klik url langsung kedirect ke halaman yang akan dituju, intinya mendisable port 80 untuk kedirect dengan port yg listen saat ini

```
server {
        listen       81;
        listen       [::]:81;
```

restart nginx server saja tidak perlu restart container

12. after reboot you must restart sshd, restart nginx and restart docker

13. akses your aplikasi using IP Public or subdomain


