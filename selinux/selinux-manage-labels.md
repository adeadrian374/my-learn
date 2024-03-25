Selinux Manage
 
command cek status selinux

note: default permission selinux is enforcing

```
getenforce
```
command  selinux to set permissive

```
setenforce 0
```

command check proccess your selinux is running or not running if using context labels

```
ps axZ
```

role on context labels selinux

```
user:role:type:level 
```

Perlu implementasi selinux with httpd on dir /var/www/html

command for relabeled for content on dir /var/www/html so can access from httpd

relabeled -> command untuk menyamakan permission terkait type context dari context labels selinux yang ada dari dir tertentu

```
restorecon -Rv <name-path-dir>
```

1. percobaan relabeled httpd

create html file and check labels context from file html

dari type context nya, dari sisi file dibuat dari user root, maka context type nya sebagai admin_home_t

```
[root@yavahel7w-02 ~]# echo "hallo" > hallo.html
[root@yavahel7w-02 ~]# ll
total 32
-rw-------. 1 root root  1451 Aug 28 10:20 anaconda-ks.cfg
-rwxr-xr-x. 1 root root    40 Sep 18 15:58 clear-cache.sh
-rw-r--r--. 1 root root     6 Oct 25 14:36 hallo.html
-rw-------. 1 nifi nifi  2268 Sep  7 13:46 nifi-certificate-authority-keystore.jks
-rw-r--r--. 1 root root 14656 Jul  4  2014 snappy-devel-1.1.0-3.el7.x86_64.rpm
drwxr-xr-x. 2 root root    33 Sep  6 00:24 tmp_leapp_py3
[root@yavahel7w-02 ~]# ls -lZ hallo.html
-rw-r--r--. 1 root root unconfined_u:object_r:admin_home_t:s0 6 Oct 25 14:36 hallo.html
[root@yavahel7w-02 ~]#

```

jika dibuat dari user-custom, dari type context nya kebaca sebagai user_home_t

```
hgrid@yavahel7w-02 ~]$ echo "hagologo" > pagee.html
[hgrid@yavahel7w-02 ~]$ ll
total 4
-rw-rw-r--. 1 hgrid hgrid 9 Oct 25 14:47 pagee.html
[hgrid@yavahel7w-02 ~]$ ls -lZ
total 4
-rw-rw-r--. 1 hgrid hgrid unconfined_u:object_r:user_home_t:s0 9 Oct 25 14:47 pagee.html
[hgrid@yavahel7w-02 ~]$
```

issue jika file html tersebut di move ke dir /var/www/html/

Forbidden

You don't have permission to access this resource.


maka perlu penyesuaian type context label dari file html nya

walaupun permission file html sudah root dan ACL file nya sudah 755, tetapi perlu set label type nya ke `httpd_sys_content_t`

```
[root@yavahel7w-02 ~]# ls -lZ /var/www/html/
total 4
-rw-r--r--. 1 root root unconfined_u:object_r:admin_home_t:s0 6 Oct 25 14:36 hallo.html
[root@yavahel7w-02 ~]# ls -lZ /var/www/
total 0
drwxr-xr-x. 2 root root system_u:object_r:httpd_sys_script_exec_t:s0  6 Apr  3  2023 cgi-bin
drwxr-xr-x. 2 root root system_u:object_r:httpd_sys_content_t:s0     24 Oct 25 14:37 html
```

untuk penyesuaian type context label menggunakan command `restorecon -Rv`

```
[root@yavahel7w-02 ~]# restorecon -Rv /var/www/html/
Relabeled /var/www/html/hallo.html from unconfined_u:object_r:admin_home_t:s0 to unconfined_u:object_r:httpd_sys_content_t:s0
[root@yavahel7w-02 ~]#
```

maka akses ui nya sudah tidak forbidden


2. Akses direktory from httpd

test case
- menambahkan file html di suatu dir (misal /srv/special/hallo.html)

```
[root@yavahel7w-02 ~]# mkdir /srv/special
[root@yavahel7w-02 ~]# echo "xenous" > /srv/special/xen.html
[root@yavahel7w-02 ~]#
```
- menampilkan list file dan akses kedalam file melalui web httpd
- tetapi muncul forbidden ketika akses ke file html nya, akses  
- dan jika mundur satu path list index directory untuk isi file html nya tidak muncul

step yang sudah dicoba

create config test.conf dari httpd di dir `/etc/httpd/conf.d/`

```
<Directory /srv/special/>
    AllowOverride None
    Require all granted
</Directory>

Alias /special /srv/special
```

restart httpd service & check web browser list Index Directory

dari list index directory masih belum menampilkan isi dari dir `special`

perlu ubah permission selinux ke `permissive` biar bisa akses file dari list Index Directory dan dapat menampilkan list file dan akses kedalam file melalui web httpd

```
setenforce 1
```

merubah context labels type juga bisa menggunakan command `semanage fcontext`

semanage fcontext ->  SELinux Policy Management file context tool

note : opsi menggunakan semanage ini untuk merubah type labels context dengan kondisi status selinux = enforcing

command untuk merubah type context jika file atau sub dir berikutnya akan mengikuti type context yang sudah diubah

```
semanage fcontext -a -t <name-type-context> "</name-path-dir/(/.*)?"

restorecon -Rv <name-path-dir>
```
dan dengan mengatur type context menggunakan semanage fcontext lalu relabeled maka untuk akses ke web server dari list index directory bisa ditampilkan isi list nya, dan status selinux tetap enforcing


command untuk list context labels on SELinux

```
semanage fcontext -l | grep "<path-dir>"
```

command untuk delete labels on path SELinux

```
semanage fcontext -d -t <nama-type-context> "<path-dir>"
```

3. Manage Port SELinux Labels

untuk mengetahui list port yang digunakan oleh labels SELinux

```
semanage port -l
```

add port on SELinux Labels type context

change port listen on `/etc/httpd/conf/httpd.conf` 80 -> 8040

save & restart httpd

add port sesuai dengan name type context dari port yang akan dipakai, beserta protocol dan port terbarunya, jika belum menambahkan port di SELinux Labels nya, maka akan kedenied seperti ini jika hanya merubah config dari httpd.conf dan tidak add port ke port

```
(13)Permission denied: AH00072: make_sock: could not bind to address 0.0.0.0:8040
```

```
semanage port -a -t <nama-type-context-port> -p <protocol-port> <port-new>


semanage port -a -t http_port_t -p tcp 8040
```

untuk delete port berdasarkan type context dari labels yang sebelumnya sudah diterapkan

```
semanage port -d -t <nama-type-context-port> -p <protocol-port> <port-new>

semanage port -d -t http_port_t -p tcp 8040
```

4. Manage SELinux use boolean settings

Get status boolean SELinux

```
getsebool -a
```

check all status SELinux boolean 

```
semanage boolean -l | less
```
create directory baru di /home

check status selinux boolean

```
semanage boolean -l | grep <name-selinux-boolean>
```

sebelum enable ON pada SELinux boolean, config terlebih dahulu pada httpd.conf untuk menambahkan permission user di param UserDir

tujuannya -> agar ketika user tersebut mengakses dir user dari browser dapat langsung terdeteksi,dan hanya user tersebut yang dapat diakses saja

before

```
#
# UserDir: The name of the directory that is appended onto a user's home
# directory if a ~user request is received.
#
# The path to the end user account 'public_html' directory must be
# accessible to the webserver userid.  This usually means that ~userid
# must have permissions of 711, ~userid/public_html must have permissions
# of 755, and documents contained therein must be world-readable.
# Otherwise, the client will only receive a "403 Forbidden" message.
#
<IfModule mod_userdir.c>
    #
    # UserDir is disabled by default since it can confirm the presence
    # of a username on the system (depending on home directory
    # permissions).
    #
    UserDir disabled

    #
    # To enable requests to /~user/ to serve the user's public_html
    # directory, remove the "UserDir disabled" line above, and uncomment
    # the following line instead:
    #
    #UserDir public_html
</IfModule>
```

after

```
#
# UserDir: The name of the directory that is appended onto a user's home
# directory if a ~user request is received.
#
# The path to the end user account 'public_html' directory must be
# accessible to the webserver userid.  This usually means that ~userid
# must have permissions of 711, ~userid/public_html must have permissions
# of 755, and documents contained therein must be world-readable.
# Otherwise, the client will only receive a "403 Forbidden" message.
#
<IfModule mod_userdir.c>
    #
    # UserDir is disabled by default since it can confirm the presence
    # of a username on the system (depending on home directory
    # permissions).
    #
    UserDir disabled
    UserDir enabled <nama-user>

    #
    # To enable requests to /~user/ to serve the user's public_html
    # directory, remove the "UserDir disabled" line above, and uncomment
    # the following line instead:
    #
    UserDir public_html
</IfModule>
```
change permission from 700 to 711 for dir /home/<name-user> untuk memudahkan dalam mengeksekusi file dari sub dir nya

perlu enable boolean SELinux terlebih dahulu untuk httpd.*homedirs agar dari path homedir user dapat diakses dari browser

note : jika belum di enable boolean SELinux akan muncul issue seperti ini

```
Forbidden

You don't have permission to access this resource.
```

cek status boolean SELinux dari `httpd_enable_homedirs`

```
semanage boolean -l | grep httpd.*homedirs

semanage boolean -l | grep httpd_enable_homedirs
```

jika statusnya masih off semua, maka perlu enable booleans to on

jika ingin mengaktifkan satu kolom saja untuk boolean SELinux, bisa dicoba seperti ini

```
setsebool <name-boolean> 1
```

```
[root@yavahel7w-02 ~]# semanage boolean -l | grep httpd_enable_homedirs
httpd_enable_homedirs          (off  ,  off)  Allow httpd to enable homedirs
[root@yavahel7w-02 ~]# setsebool httpd_enable_homedirs 1
[root@yavahel7w-02 ~]# semanage boolean -l | grep httpd_enable_homedirs
httpd_enable_homedirs          (on   ,  off)  Allow httpd to enable homedirs
[root@yavahel7w-02 ~]#
```

jika ingin mengaktifkan keseluruhan kolom dari status boolean SELinux, agar keduanya sama-sama on, bisa dicoba seperti ini

```
setsebool -P <nama-boolean> 1


setsebool -m --on <name-boolean> 
```
note : secara default jika mengatur status boolean SELinux harus keduanya berubah


jika ingin disable kolom dari status boolean SELinux, bisa dicoba seperti ini

```
setsebool -m --off <name-boolean>
```

lalu cek browser kembali, untuk akses homedir dari httpd

```
http://<ip-add>/~<name-homedir>
```

5. Diagnostic & Trace issue on SELinux 

check logs on `/var/logs/messages` lalu cek di messages run nya dibagian sealert nya,disitu akan dibantu untuk troubleshootnya terkait issue dari SELinux nya



























