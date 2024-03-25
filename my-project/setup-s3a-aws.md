# config S3 Protocol Ozone or Minio -> untuk mendapatkan access ID key & secreet key

# Untuk mendapatkan access ID key & secreet key bisa melalui AWS-CLI

## environment

- Centos 7
- YAVA 3.0
- Ozone 1.0.0 (non secure alias belum set ozone.security.enabled=true)
- Kerberos

Sebelum mengkonfigurasikan AWS-CLI, perlu diperhatikan untuk add config di custom core-site HDFS, berikut property yg dibutuhkan agar S3a Ozone dapat diakses via hadoop

```
fs.s3a.path.style.access=true
fs.s3a.block.size=512M
fs.s3a.buffer.dir=${hadoop.tmp.dir}/s3a
fs.s3a.committer.magic.enabled=false
fs.s3a.committer.name=directory
fs.s3a.committer.staging.abort.pending.uploads=true
fs.s3a.committer.staging.conflict-mode=append
fs.s3a.committer.staging.tmp.path=/tmp/staging
fs.s3a.committer.staging.unique-filenames=true
fs.s3a.connection.establish.timeout=5000
fs.s3a.connection.ssl.enabled=false
fs.s3a.connection.timeout=200000
fs.s3a.endpoint=<url-ozone>:9878
fs.s3a.impl=org.apache.hadoop.fs.s3a.S3AFileSystem
fs.s3a.committer.threads=2048
fs.s3a.connection.maximum=8192
fs.s3a.fast.upload.active.blocks=2048
fs.s3a.max.total.tasks=2048
fs.s3a.multipart.threshold=512M
fs.s3a.multipart.size=2G
fs.s3a.socket.recv.buffer=65536
fs.s3a.socket.send.buffer=65536
fs.s3a.threads.max=2048
fs.s3a.secret.key=<diisi sesuai secret key dari set awscli>
fs.s3a.access.key=<diisi sesuai access key dari set awscli>
```

jika sudah save dan restart service oozone

# Konfigurasi S3 Protocol

note : untuk setup s3 aws ini, perlu menyesuaikan endpoint bucket yang digunakan, biasanya wadah untuk bucket nya menggunakan service dari ozone,minio, dan ceph

## Akses S3a ozone Menggunakan AWS-CLI

1. Install awscli di node ozone

yum install -y awscli

2. Konfigurasikan awscli untuk mengatur credential yang diperlukan dari access ID key & secreet key, bila kondisi ozone masih non-secure maka untuk credential bisa diatur secara bebas

su - ozone
aws configure set default.s3.signature_version s3v4
aws configure set aws_access_key_id ${accessId}
aws configure set aws_secret_access_key ${secret}
aws configure set region us-west-1

contoh

su - ozone
aws configure set default.s3.signature_version s3v4
aws configure set aws_access_key_id test123
aws configure set aws_secret_access_key test123!
aws configure set region us-west-1
note: pastikan sebelum menjalankan command awscli sudah kinit ke ozone dan posisi running awscli ada di user ozone

3. Untuk membuat bucket s3 ozone gunakan command

aws s3api --endpoint http://prdsrvryavai01.labs247.com:9878 create-bucket --bucket nama-bucket

contoh

aws s3api --endpoint http://yava-ozone03.labs247.com:9878 create-bucket --bucket buckettest

4. Untuk mengakses bucket yang telah dibuat gunakan command
   
aws s3 ls --endpoint http://prdsrvryavai01.labs247.com:9878 s3://nama-bucket

contoh 

aws s3 ls --endpoint http://yava-ozone03.labs247.com:9878 s3://buckettest

5. Untuk mengupload data dari local ke bucket

aws s3 cp /file/yang/mau/diupload --endpoint http://prdsrvryavai01.labs247.com:9878 s3://nama-bucket

contoh

aws s3 cp /etc/hosts --endpoint http://yava-ozone03.labs247.com:9878 s3://buckettest/hosts

## Akses S3a ozone via hadoop

1. Untuk akses s3 via hadoop dapat menggunakan command

hadoop fs -ls s3a://nama-bucket/

contoh

hadoop fs -ls s3a://buckettest/

2. Upload file dari local ke bucket via hadoop

hadoop fs -put /path/file/yang/mau/diupload s3a://nama-bucket/

3. Melihat isi data bucket

hadoop fs -cat s3a://nama-bucket/nama-file

contoh

hadoop fs -cat s3a://buckettest/hosts