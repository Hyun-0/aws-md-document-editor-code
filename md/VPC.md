# Virtual Private Cloud (VPC)

- AWS에서 논리적으로 격리된 네트워크 공간을 할당하여 가상 네트워크에서 AWS 리소스를 이용할 수 있는 서비스 제공
- 요약: AWS 계정을 위한 전용 가상 네트워크
  - 리전, 가용 영역에 적용

### 구성 요소

- 자체 IP 주소 범위
  - Private IP
    - VPC 내부에서만 사용할 수 있는 IP
    - VPC subnet 범위에서 자동 할당
    - 동일 네트워크에서 instance 간의 통신에 사용
  - Public IP
    - 인터넷을 통해 연결 가능
    - 인스턴스와 인터넷 간의 통신을 위해 사용
    - EC2 instance 생성 시 옵션으로 public IP 주소 할당 가능
      - Elastic IP (EIP): 동적 컴퓨팅을 위해 고안된 고정 Public IP
  - Classless Inter-Domain Routing (CIDR)
    - 192.0.2.0/24
    - 제일 뒤의 24: 24bit 고정, 숫자 하나당 8bit = 192.0.2 rhwjd
    - Subnet에서 IP 할당 시 .0에서만 할당

- Subnet
  - VPC 내부에서 분리된 IP block
  - 각 Availability Zone (AZ, 가용 영역)에 하나 이상의 subnet 추가 가능
  - 단일 AZ에서만 생성 가능
  - Public Subnet
    - 외부와 통신 가능한 Subnet
    - Subnet 네트워크 트래픽이 Internet Gateway (IG)로 라우팅되는 Subnet
  - Private Subnet
    - IG로 라우팅되지 않는 Subnet
    - 보안성이 필요한 DB 서버 등에 사용

- Routing Table
  - 외부로 나가는 Outbound Traffic에 대해 허용된 경로 지정
    
- Network Gateway
  - 한 네트워크(segment)에서 다른 네트워크로 이동하기 위해 거쳐야 하는 지점
  - VPC와 인터넷 간의 통신을 가능하게 해줌
  - Network Address Translation (NAT Gateway)
    - 내부 IP를 외부 IP로 변환하는 서비스

- VPC Security
  - 인스턴스 수준에서 적용
  - 기본 보안 그룹
    - 모든 인바운드 트래픽 거부
    - 모든 아웃바운드 트래픽 허용
