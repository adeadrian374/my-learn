# Monitoring Server on Local used Prometheus, Node Exporter and Grafana 

# Environment

- Centos 7

- Prometheus

- Node Exporter

- Grafana

# Step

# Setup Prometheus

1. Install epel-release pada server 
   
   ```
   yum install -y epel-release
   ```
2. Cek pada bagian selinux, dan nonaktifkan selinuxnya

   ```
   sed -i 's/enforcing/disabled/g' /etc/selinux/config
   ```
3. Install package Prometheus dari repository github

   ```
   wget https://github.com/prometheus/prometheus/releases/download/v2.40.1/prometheus-2.40.1.linux-amd64.tar.gz
   ```
4. Create user prometheus dengan memberikan status /sbin/false

   ```
   useradd -s /sbin/false prometheus
   ```
5. Change ownership pada prometheus file ke user prometheus dan change permission pada path prometheus file

   ```
   chmod 755 prometheus-2.40.1.linux-amd64 -R
   ```

   ```
   chown prometheus:prometheus prometheus-2.40.1.linux-amd64 -R
   ```
6. move path prometheus file ke dir baru

   ```
   mv <path prometheus file> <path baru>
   ```
7. Buat file prometheus.service, kedalam path /etc/systemd/system/ 

   ```
   vi /etc/systemd/system/prometheus.service
   ```
   dan konfigurasikan isi file prometheus.service seperti yang ada dibawah ini dan jangan lupa save

   ```
      [Unit]
   Description=Prometheus Server
   Wants=network-online.target
   After=network-online.target

   [Service]
   User=prometheus
   Group=prometheus
   Type=simple
   ExecStart= /root/package/prometheus \
   --config.file= /root/package/prometheus.yml \
   --storage.tsdb.path=/root/package/ \
   --web.console.templates= /root/package/consoles \
   --web.console.libraries= /root/package/console_libraries

   [Install]
   WantedBy=multi-user.target
   ```
8. restart pada daemon nya dan start prometheus nya
   
   ```
   systemctl daemon-reload
   ```
   
   untuk start prometheus, masuk ke dir baru yang digunakan untuk move prometheus file nya

   ```
   nohup ./prometheus & > /dev/null
   ```
9. Panggil prometheus melalui browser dengan menggunakan port 9090, sebelum memanggil di browser, pastikan firewalld sudah stop
   
   ```
   systemctl status firewalld && systemctl stop firewalld
   ```

# Setup Grafana

1. Buat file grafana.repo di path /etc/yum.repos.d/grafana.repo

   ```
   vi /etc/yum.repos.d/grafana.repo
   ```
   untuk konfigurasi isi dari grafana.repo ada dibawah ini ;

   ```
   [grafana]
   name=grafana
   baseurl=https://packages.grafana.com/oss/rpm
   repo_gpgcheck=1
   enabled=1
   gpgcheck=1
   gpgkey=https://packages.grafana.com/gpg.key
   sslverify=1
   sslcacert=/etc/pki/tls/certs/ca-bundle.crt 
   ```
2. Install Grafana 
   
   ```
   yum install -y grafana
   ```
3. Kemudian start grafana dan cek di browser dengan memanggil ip add beserta port dari grafana 3000. untuk user/pass admin/admin jika berhasil login maka akan diminta untuk add new password untuk grafana nya

   ```
   systemctl status grafana-server && systemctl stop grafana-server
   ```
# Setup node exporter

1. Download package dari node exporter nya terlebih dahulu dan jangan lupa di extract 
   
   ```
   wget https://github.com/prometheus/node_exporter/releases/download/v1.4.0/node_exporter-1.4.0.linux-amd64.tar.gz
   ```

   ```
   tar -zvf node_exporter-1.4.0.linux-amd64.tar.gz
   ```
2. kemudian buat file node_exporter.service di path /etc/systemd/system/ 
   
   ```
   vi /etc/systemd/system/node_exporter.service
   ```
   untuk config dari file node_exporter.service seperti yang ada di bawah ini;

   ```
    [Unit]

    Description=node_exporter
    Wants=network-online.target
    After=network-online.target

    [Service]

    User=prometheus
    Group=prometheus
    Type=simple
    ExecStart=<nama path>/node_exporter

    [Install]

    WantedBy=multi-user.target
   ```
3. edit file prometheus.yml, untuk menambahkan server ke prometheus,kemudian tambahkan config job beserta targets nya seperti yang ada dibawah ini, pastikan jika ingin menambahkan job baru add dibawah static_configs milik prometheus

   ```
   static_configs:
      - targets: ["localhost:9090"]


   - job_name: "nama job"
    static_configs:
      - targets: ['localhost:9100']
   ```
4. Kemudian start node_exporter nya dan juga prometheus, lalu cek di prometheus UI untuk memastikan targets server yang sebelumnya di add di file prometheus.yml,telah muncul untuk node yang telah ditambahkan sebelumnya
   
   untuk start node_exporter, perlu masuk ke path dimana node_exporter itu disimpan, biasanya terletak di path node exporter file

   ```
   nohup ./node_exporter & > /dev/null
   ```
   jika sudah muncul targets nya, maka tinggal integrasikan dari prometheus ke grafana 



   


