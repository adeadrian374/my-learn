# Setup Kubernetes Cluster with kubeadm on Local (VM)

# Environment

- Centos 7

- Kubernetes

- Docker

- Containerd

# Step

1. Setup requirements dari server yang akan digunakan untuk kubernetes cluster, untuk spesifikasinya yaitu 

   Master -> 4GB Ram, 2vcpus
   
   Worker -> 4GB Ram, 2vcpus

2. Update OS pada setiap nodenya

   ```
   yum update -y
   ```
3. add repository ke dalam nodes untuk menginstall kubelet,kubeadm,dan kubectl
   
   ```
   sudo tee /etc/yum.repos.d/kubernetes.repo<<EOF
   [kubernetes]
   name=Kubernetes
   baseurl=https://packages.cloud.google.com/yum/repos/kubernetes-el7-x86_64
   enabled=1
   gpgcheck=1
   repo_gpgcheck=1
   gpgkey=https://packages.cloud.google.com/yum/doc/yum-key.gpg https://packages.cloud.google.com/yum/doc/rpm-package-key.gpg
   EOF
   ```
4. Install required packages beserta kubelet,kubeadm,dan kubectl
   
   ```
   sudo yum clean all && sudo yum -y makecache
   sudo yum -y install epel-release vim git curl wget kubelet kubeadm kubectl --disableexcludes=kubernetes
   ```
5. check version dari kubeadm dan kubectl

   ```
   kubeadm  version
   
   kubectl version --client
   ```
6. Disable Selinux & Swap

   ```
   sudo setenforce 0
   sudo sed -i 's/^SELINUX=.*/SELINUX=permissive/g' /etc/selinux/config
   ```
   ```
   sudo sed -i '/ swap / s/^\(.*\)$/#\1/g' /etc/fstab
   sudo swapoff -a
   ```
7. configure pada sysctl
   
   ```
   sudo modprobe overlay
   sudo modprobe br_netfilter
   ```

   ```
   sudo tee /etc/sysctl.d/kubernetes.conf<<EOF
   net.bridge.bridge-nf-call-ip6tables = 1
   net.bridge.bridge-nf-call-iptables = 1
   net.ipv4.ip_forward = 1
   EOF
   ```
   ```
   sysctl --system
   ```
8. Setup & Instalasi container runtime with containerd
   - Configure persistent loading of modules
     
     ```
     sudo tee /etc/modules-load.d/containerd.conf <<EOF
     overlay
     br_netfilter
     EOF
     ```
   - Load at runtime
     
     ```
     sudo modprobe overlay
     sudo modprobe br_netfilter
     ```
   - Ensure sysctl params are set

     ```
     sudo tee /etc/sysctl.d/kubernetes.conf<<EOF
     net.bridge.bridge-nf-call-ip6tables = 1
     net.bridge.bridge-nf-call-iptables = 1
     net.ipv4.ip_forward = 1
     EOF
     ```
   - Reload configs
     
     ```
     sudo sysctl --system
     ```
   - install required packages
     
     ```
     sudo yum install -y yum-utils device-mapper-persistent-data lvm2
     ```
   - add docker-repo on containerd
     
     ```
     sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
     ```
   - Install containerd
     
     ```
     sudo yum update -y && yum install -y containerd.io
     ```
   - Configure containerd and start service

     ```
     sudo mkdir -p /etc/containerd
     sudo containerd config default > /etc/containerd/config.toml
     ```
   - restart containerd

     ```
     sudo systemctl restart containerd
     sudo systemctl enable containerd
     ```
9. Disable Firewalld
   
   ```
   sudo systemctl disable --now firewalld
   ```
10. untuk node master,maka perlu initalize control plane pada node master, sebaiknya cek module dari br_netfilter
    
    ```
    lsmod | grep br_netfilter
    ```
  - enable service pada kubelet

    ```
    systemctl enable kubelet
    ```
  - untuk running control plane,maka perlu untuk add component melalui pull image container

    ```
    sudo kubeadm config images pull
    ```
  - set pada cluster enddpoint dengan add DNS Name ke /etc/hosts

  - kemudian create cluster
    
    ```
    sudo kubeadm init \
    --pod-network-cidr=192.168.0.0/16 \
    --upload-certs \
    --control-plane-endpoint=diisi dengan hostname dari master
    ```
    atau juga bisa dengan running kubeadm init untuk penggunaan bootstrap cluster

11. To start using your cluster, you need to run the following as a regular user:
    
    configure kubectl

    ```
    mkdir -p $HOME/.kube
    sudo cp -i /etc/kubernetes/admin.conf $HOME/.kube/config
    sudo chown $(id -u):$(id -g) $HOME/.kube/config
    ```
12. Kemudian untuk node worker nya,bisa untuk join ke control-plane yang sudah dibuatkan oleh node master

    ```
    kubeadm join 192.168.10.249:6443 --token 4garvb.4wl16w64tzldt7xj \
        --discovery-token-ca-cert-hash sha256:265ecf1edb5fd80287366caf97503c9dfaa15e5bd6223b55445b08ac15142b08
    ```



