# EC2 Elastic Beanstalk
- AWS에서 제공하는 PaaS (Platform as a Service) 서비스
- 웹 애플리케이션의 자동 배포 및 관리

### Elastic Beanstalk 특징
- 자동화된 인프라 관리
- 다양한 언어 지원
-  Auto Scaling
- 모니터링 및 로깅

### Elastic Beanstalk 장점
- 간편한 배포
- 자동화된 리소스 관리
- 비용 효율성
- 관리 단순화

### Elastic Beanstalk 구성 요소
- 애플리케이션
  - 논리적 단위로 구성된 애플리케이션 버전과 환경
- 환경
  - 애플리케이션이 실행되는 물리적 리소스의 집합
- 로드 밸런서
  - 트래픽을 EC2 인스턴스에 분산
- EC2 인스턴스
- Auto Scaling
  - 트래픽 증가 시 EC2 인스턴스 자동으로 추가
- S3 버킷 및 CloudWatch 모니터링

### Elastic Beanstalk 배포 과정
1. 코드 준비 및 패키징
2. 환경 생성
3. 배포 및 설정
4. 모니터링 및 관리
5. 업데이트 및 버전 관