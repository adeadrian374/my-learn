# How to config & enable HA Postgresql on Cluster

# Environment

- Centos 7 

- Postgresql-12

- Etcd

- HAproxy

- Patroni

- psycopg

# Step 

- fyi untuk requirement role yang diperlukan dari masing-masing node yang ada dalam cluster seperti berikut;

  ```
  node1 primary/master role -> etcd,HAproxy
  node2 replica/worker role -> postgresql,patroni
  node3 replica/worker role -> postgresql,patroni
  ```
1. Install package epel-release & yum-utils untuk persiapan setup repository

   ```
   yum install -y epel-release yum-utils
   ```
2. Install repository postgresql,dan install postgresql-12 di node2 & node3

   ```
   yum install -y https://download.postgresql.org/pub/repos/yum/reporpms/EL-7-x86_64/pgdg-redhat-repo-latest.noarch.rpm

   yum -y install postgresql12-server postgresql12 postgresql12-devel --skip-broken
   ```
3. Install psycopg dan patroni di node2 & node3
   
   ```
   yum -y install https://download-ib01.fedoraproject.org/pub/epel/7/x86_64/Packages/p/python3-psycopg2-2.7.7-2.el7.x86_64.rpm https://github.com/cybertec-postgresql/patroni-packaging/releases/download/1.6.0-1/patroni-1.6.0-1.rhel7.x86_64.rpm
   ```
4. Copy file configurasi patroni & ubah permission dari file konfigurasi patroni dari node2 dan node3

   ```
   cp /opt/app/patroni/etc/postgresql.yml.sample /opt/app/patroni/etc/postgresql.yml

   chown -R postgres:postgres /opt/app/patroni/etc/postgresql.yml
   ```
5. Konfigurasikan file patroni yang ada di node2 dan node3 seperti berikut;

node2

```
scope: postgres
namespace: /pg_cluster/
name: node1

restapi:
    listen: <ip-address-node2>:8008
    connect_address: <ip-address-node2>:8008

etcd:
    host: <ip-address-node1>:2379

bootstrap:
  dcs:
    ttl: 30
    loop_wait: 10
    retry_timeout: 10
    maximum_lag_on_failover: 1048576
    postgresql:
      use_pg_rewind: true
      use_slots: true
      parameters:

  initdb:
  - encoding: UTF8
  - data-checksums

  pg_hba:
 # - host replication replicator 127.0.0.1/32 md5
  - host replication replicator <ip-address-node2/prefiks> md5
  - host replication replicator <ip-address-node3/prefiks> md5
 # - host replication replicator 192.168.10.28/27 md5
  - host all all 0.0.0.0/0 md5

  users:
    admin:
      password: admin
      options:
        - createrole
        - createdb

postgresql:
  listen: <ip-address-node2>:5432
  connect_address: <ip-address-node2>:5432
  data_dir: /var/lib/pgsql/12/data
  bin_dir: /usr/pgsql-12/bin
  pgpass: /tmp/pgpass
  authentication:
    replication:
      username: replicator
      password: reppassword
    superuser:
      username: postgres
      password: postgrespassword

categories:
    nofailover: false
    noloadbalance: false
    clonefrom: false
    nosync: false
```
node3

```
scope: postgres
namespace: /pg_cluster/
name: node2

restapi:
    listen: <ip-address-node3>:8008
    connect_address: <ip-address-node3>:8008

etcd:
    host: <ip-address-node1>:2379

bootstrap:
  dcs:
    ttl: 30
    loop_wait: 10
    retry_timeout: 10
    maximum_lag_on_failover: 1048576
    postgresql:
      use_pg_rewind: true
      use_slots: true
      parameters:

  initdb:
  - encoding: UTF8
  - data-checksums

  pg_hba:
 # - host replication replicator 127.0.0.1/32 md5
  - host replication replicator <ip-address-node2/prefiks> md5
  - host replication replicator <ip-address-node3/prefiks> md5
 # - host replication replicator 192.168.10.28/27 md5
  - host all all 0.0.0.0/0 md5

  users:
    admin:
      password: admin
      options:
        - createrole
        - createdb

postgresql:
  listen: <ip-address-node3>:5432
  connect_address: <ip-address-node3>:5432
  data_dir: /var/lib/pgsql/12/data
  bin_dir: /usr/pgsql-12/bin
  pgpass: /tmp/pgpass
  authentication:
    replication:
      username: replicator
      password: reppassword
    superuser:
      username: postgres
      password: postgrespassword

categories:
    nofailover: false
    noloadbalance: false
    clonefrom: false
    nosync: false
```
6. Install etcd di node1 dan konfigurasikan rule etcd di path /etc/etcd/etcd.conf

   note :
   
   etcd -> digunakan sebagai monitoring & menentukan node mana yang akan menjadi primary ataupun standby untuk streaming replication postgresql 

   ```
   yum install -y etcd
   ```
   konfigurasi rule etcd 

   ```
   ETCD_DATA_DIR="/var/lib/etcd/default.etcd"
   ETCD_LISTEN_PEER_URLS="http://192.168.56.102:2380"
   ETCD_LISTEN_CLIENT_URLS="http://192.168.56.102:2379"
   ETCD_NAME="default"
   ETCD_INITIAL_ADVERTISE_PEER_URLS="http://192.168.56.102:2380"
   ETCD_ADVERTISE_CLIENT_URLS="http://192.168.56.102:2379"
   ETCD_INITIAL_CLUSTER="default=http://192.168.56.102:2380"
   ETCD_INITIAL_CLUSTER_TOKEN="etcd-cluster"
   ETCD_INITIAL_CLUSTER_STATE="new"
   ```
7. enable & start service etcd di node1
   
   ```
   systemctl enable etcd && systemctl start etcd && systemctl status etcd
   ```
8. enable & start service patroni di node2 & node3

   ```
   systemctl enable patroni && systemctl start patroni && systemctl status patroni
   ```
9. Install HAproxy untuk kebutuhan failover / load balancing dari postgresql, install di node1

   ```
   yum install -y haproxy
   ```
konfigurasi dari haproxy di /etc/haproxy/haproxy.cfg

```
global

        maxconn 100
        log 127.0.0.1 local2

defaults
        log global
        mode tcp
        retries 2
        timeout client 30m
        timeout connect 4s
        timeout server 30m
        timeout check 5s

listen stats
    mode http
    bind *:2233
    stats hide-version
    stats refresh 30s
    stats show-node
    stats auth username:password
    stats enable
    stats uri /

listen postgres
    bind *:5000
    option httpchk
    http-check expect status 200
    default-server inter 3s fall 3 rise 2 on-marked-down shutdown-sessions
    server node1 <ip-address-node2>:5432 maxconn 100 check port 8008
    server node2 <ip-address-node3>:5432 maxconn 100 check port 8008
#    server node3 192.168.10.28:5432 maxconn 100 check port 8008
```
10. Enable & start service HAproxy di node1
    
    ```
    systemctl enable haproxy && systemctl start haproxy && systemctl status haproxy
    ```
11. test connection dari node replica(node2 & node3) ke node primary(node1) dengan user/pass postgres/postgrespassword
    
    ```
    psql -h 192.168.56.102 -p 5000 -U postgres
    ```
12. Kemudian cek status dari HAproxy melalui url dari HAproxy UI `http://<ip-address-node1>:2233` login dengan user/pass username/password

13. Untuk mengecek status dari node primary dan standby bisa dilakukan restart service patroni yang ada di node2 maka status dari node3 akan berubah semula dari down menjadi standby begitu sebaliknya



