## How To Create VPC Component at AWS using terraform file

# Environment

- AWS

- IAM user

- Internet Gateway

- Route Table

- Subnet

- Terraform

# About VPC Commponent

- Virtual Private Cloud(VPC) is an environment for running EC2 Instance, and there are a region, some Availibilty Zone, Internet Gateway, Route Table, etc.

The VPC will be help EC2 Instance for communication with another VPC by internet

in one vpc only exist 1 region, but for availabilty zone from the region is infinite

- Internet Gateway is a commponent for allow communication from your vpc with internet.

internet gateway can help for route table to routing proccess for get internet

internet gateway can initiation a connection for subnet to communication with internet, and access to another subnet / Availibilty Zone

- Route Table will be associated subnet for get internet, and can access to internet gateway & access to outside vpc


Internet Gateway is an 

# Step

1.  First of all, you will be prepare provider.tf & vpc.tf for needed create VPC Component

before you start you have to prepare IAM User for gets access_key & secret_key from service IAM User on AWS

if you don't have user on IAM User services, you must to create user & create access key for gets access_key & secret_key

2. Create provider.tf for connect from WSL to AWS using terraform file

following this config for content provider.tf;

```
#define provider aws on terraform file

terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"


    }
  }
}

# define region state, access key and secret key based on IAM user

provider "aws" {
  region     = "<your-region-state>"
  access_key = "<your-access_key>"
  secret_key = "<your-secret_key>"
}
```
note : make sure the region in accordance with the IAM user

3. Create vpc.tf for create VPC Component like VPC, Internet Gateway, Route Table, & subnet)

add this config to your vpc.tf

```
# create vpc custom

resource "aws_vpc" "<your-name-VPC>" {
  cidr_block       = "<range-your-IP-VPC>"
  instance_tenancy = "default" 

  tags = {
    Name = "<add-name-tag-vpc>"
  }
}

# create internet gateways for environment vpc

resource "aws_internet_gateway" "<name-igw>" {
  vpc_id = aws_vpc.<your-name-VPC>.id

  tags = {
    Name = "<add-name-tag-igw>"
  }
}

# note for instance_tenancy, those default value is default

# instance_tenancy for mapping state on physical hardware server for your instance

# create public.subnet

resource "aws_subnet" "<name-subnet>" {
  vpc_id     = aws_vpc.<your-name-VPC>.id
  cidr_block = "<range-your-IP-Subnet>"

  tags = {
    Name = "<add-name-tags-your-subnet>"
  }
}

# create private.subnet

resource "aws_subnet" "<name-subnet>" {
  vpc_id     = aws_vpc.<your-name-VPC>.id
  cidr_block = "<range-your-IP-Subnet>"

  tags = {
    Name = "<add-name-tags-your-subnet>"
  }
}

# create route table for connected to internet gateway

resource "aws_route_table" "<your-name-route-table>" {
  vpc_id = aws_vpc.<your-name-VPC>.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.<name-igw>.id
  }

  tags = {
    Name = "<add-your-name-route-table>"
  }
}

# create route table association for your public subnet vpc

resource "aws_route_table_association" "<your-name-association-route-table>" {
  subnet_id      = aws_subnet.<name-subnet>.id
  route_table_id = aws_route_table.<your-name-route-table>.id
}
```
4. init your terraform for download package & dependention provisioning

`terraform init`

5. cek validate from config terraform file

`terraform validate`

6. cek requirement spesification about add new config, changes and drop from instance

`terraform plan`

7. create instance based on terraform file, for apply your code to provisioning server

`terraform apply`