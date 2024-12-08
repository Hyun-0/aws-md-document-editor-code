# AWS CloudFormation
- AWS에서 인프라를 코드로 관리할 수 있는 서비스 (IaC - Infrastructure as Code)
- 자동화된 배포
- 일관성 있는 배포

### AWS CloudFormation 주요 개념
- Template
  - Json 혹은 YAML 형식으로 구성된 파일
  - 배포하고자 하는 AWS 리소스와 그 구성을 정의
- Stack
  - 템플릿을 기반으로 생성된 AWS 리소스의 집합
  - 한 번에 여러 리소스를 생성하고 관리 가능
  - 모든 리소스는 상호 연관된 상태로 배포
- StackSets
  - 다중 계정과 리전에 CloudFormation 스택 배포 및 관리
- Resources
  - 템플릿 내 정의된 AWS 서비스 자원

### AWS CloudFormation 주요 기능
- 자동화된 인프라 배포
- 인프라의 관리 용이성
- 변경 세트
- 롤백
- 크로스 리전 및 크로스 계정 배포

### AWS CloudFormation 장점
- 인프라의 자동화
- 동일한 템플릿으로 일관된 환경 구축
- 리소스 종석성 자동 처리 및 배포 시간 단축
- 코드로 인프라 정리 및 템플릿 추적 및 관리함으로 버전 관리 용이 