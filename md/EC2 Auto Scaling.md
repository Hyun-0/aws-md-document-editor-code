# EC2 Auto Scaling
- 정확한 EC2 인스턴스 수 보장
- Auto Scaling Group
  - EC2 인스턴스 모음
  - 최대, 최소, 목표 instance 개수 지정 가능
- 간편한 가용성 관리

### Auto Scaling 이점
- 내결함성 향상
  - 비정상적 Instance를 탐지하여 정상적 Instance로 대체 가능
- 가용성 향상
  - 트래픽을 처리할 수 있는 적절한 용량
- 비용 관리 개선
  - 동적으로 인스턴스 시작 및 종료

### Auto Scaling 구성 요소
- Amazon Auto Scaling Group
  - 자동 확장 정책을 적용하는 EC2 인스턴스의 컬렉션

- 구성 템플릿
  - 인스턴스 유형, 이미지, 네트워크 구성, 보안 그룹 및 기타 설정 정의