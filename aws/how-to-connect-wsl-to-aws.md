## How to connect aws to WSL

## Environment

1. AWS

2. WSL (Ubuntu 20.04.6)

## Step

1. Download package awscli from zip file

```
curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
```
2. Unzip the file awscli

```
unzip awscliv2.zip
```
3. install awscli via result unzip from awscliv2.zip

```
sudo ./aws/install
```
4. Then you have to connect wsl to aws using awscli, using the following command

```
sudo aws configure
```

output

```
AWS Access Key ID [None]: <insert-your-Access key>
AWS Secret Access Key [None]: <insert-your-Secret key>
Default region name [None]: <insert-your-region-state>
Default output format [None]: <name-file>
```

note :

1. before you running aws configure, make sure you were created an user at IAM user services for get the access key & secret key from user

2. the output format type is json file


