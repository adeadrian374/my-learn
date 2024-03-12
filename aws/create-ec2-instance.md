## How to create EC2 using Terraform on AWS

## Environment

- AWS
- IAM Services
- Terraform
- WSL

## Step

First of all, you must create dir for saved file config EC2, then you will be prepare provider.tf, main.tf, variable.tf, ec2.tf

1. After create dir for saved file config EC2, you must create module for EC2

when create module EC2,  your directory position must exit path from dir which used for saved file config EC2, then create ec2.tf

then add config at ec2.tf

```
module "ec2" {
  source = "./ec2"
}
```

2. login to path dir for saved file config EC2 & create provider.tf for prepare provider aws on terraform file

```
# define provider cloud for create EC2

terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"


    }
  }
}

# define region state, access key and secret key

provider "aws" {
  region     = var.<name-variabel-region>
  access_key = var.<name-access_key>
  secret_key = var.<name-secret_key>
}

# generate PEM file with type RSA for needed key pair

resource "tls_private_key" "rsa_4096" {
  algorithm = "RSA"
  rsa_bits  = 4096
}

# Create keypair for EC2 Instance aws (create public key using PEM file)

resource "aws_key_pair" "key_pair" {
  key_name   = var.keypair_key
  public_key = tls_private_key.rsa_4096.public_key_openssh
}

# Saved private key from aws to local system

resource "local_file" "private_key" {
  content  = tls_private_key.rsa_4096.private_key_pem
  filename = "${aws_key_pair.key_pair.key_name}.pem"
}

```

note : key pair will be created public key for ec2 instance & private key for your local system


3. after that, you must create main.tf for define your resource detail

```
# define your instance detail, like AMI, instance type,count, etc.

resource "aws_instance" "<name-your-instance>" {
  ami           = var.<name-ami_id>
  instance_type = var.<name-instance_type>
  count = var.<name-instance_count>
  key_name = aws_key_pair.key_pair.key_name

  tags = {
    Name = "<your-name-tags>"
  }
}
```

4. then you must create variable.tf for define your requirement spesification instance

```
# define & describe your spesification requirement instance on variable

variable "ami_id" {
    type = string
    default = "<your-ami-id>"
}

variable "region_state" {
    type = string
    default = "<your-state-region>"
}

variable "access_key" {
    type = string
    default = "<your-access_key>"
}

variable "secret_key" {
    type = string
    default = "<your-secret_key>"
}

variable "instance_type" {
    type = string
    default = "<your-type-instance>"
}

variable "instance_count" {
    type = number
    default = <insert-your-total-instance-create>
}

variable "keypair_key" {}

```

note : you must get access_key & secret_key from security-credentials at IAM user, if you don't have an access_key & secret_key, you must create fresh access key at IAM user with policy administrator access

following step for create access key on IAM User via aws

login to console aws

- on console dashboard, search services "IAM", then choose IAM user

- choose Users in section drop down Access management

- chose for create user

- add username for user

- add permission options with choose "Attach Policies directly" and then at permission policies you must choose "AdministratorAccess"

- next, then choose create user

- klik the User which was created from IAM user

- choose tab to Security Credentials

- choose menu Access keys then create Access key

- when option Access key best practices & alternatives you will be choose "Command Line Interface (CLI)" for enable access key from awscli

- then checklist at option "I understand the above recommendation and want to proceed to create an access key."

- add name for describe tag value, this step for describe name at access key 

- then add config provider access key & secret key with region from EC2, and saved config provider access key & secret key to variable.tf

```
5. init your terraform for download package & dependention provisioning

`terraform init`

6. cek validate from config terraform file

`terraform validate`

7. cek requirement spesification about add new config, changes and drop from instance

`terraform plan`

8. create instance based on terraform file, for apply your code to provisioning server

`terraform apply`




