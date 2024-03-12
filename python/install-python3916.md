# Install python3.9.9 & virtualenv

# Environment 
- Python version 3.9.16

- Centos 8

# Step

1. Setup Requirement to build Python 3.9 on CentOS 8
   
   ```
   sudo yum groupinstall "Development Tools" -y
   
   sudo yum install openssl-devel libffi-devel bzip2-devel -y
   ```
2. Install package Python versi 3.9.16
   
   ```
   wget https://www.python.org/ftp/python/3.9.16/Python-3.9.16.tgz
   ```
3. Extract the archive file using tar
   
   ```
   tar -xzvf Python-3.9.16.tgz
   ```
4. Switch to the directory created from the file extraction
   
   ```
   cd Python-3.9.16
   ```
5. Kemudian running configure Python installation
   
   ```
   ./configure --enable-optimizations --with-ensurepip=install
   ```
6. Build Python 3.9 on CentOS 8
   
   ```
   sudo make altinstall
   ```
7. Run below command to confirm successful installation of Python 3.9 on CentOS 8
   
   ```
   python3.9 --version
   ```
   Cek version dari pip installed

   ```
   pip3.9 --version
   ```
   upgrade pip

   ```
   /usr/local/bin/python3.9 -m pip install --upgrade pip
   ```
8. Install virtualenv
   
   ```
   pip3.9 install virtualenv
   ```
   

