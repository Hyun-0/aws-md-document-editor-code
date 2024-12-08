<?php
session_start();  // 세션 시작
header('Content-Type: application/json');

// md 폴더 경로 설정
$mdDir = __DIR__ . '/md';

// RDS 데이터베이스 설정
$servername = "final-database.c3ymyq00sgfm.ap-northeast-2.rds.amazonaws.com";
$dbUsername = "admin";
$dbPassword = "admin1234";
$dbName = "project_db";

// 데이터베이스 연결
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// 연결 확인
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => '데이터베이스 연결 실패: ' . $conn->connect_error]);
    exit();
}

// 세션에 username이 설정되어 있는지 확인
if (!isset($_SESSION['username'])) {
    http_response_code(403);  // 접근 거부
    echo json_encode(['error' => '로그인이 필요합니다.']);
    exit();
}

// 업로드 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'upload') {
    if (isset($_FILES['file'])) {
        $targetDir = __DIR__ . '/md/';  // 저장할 디렉터리 설정
        $fileName = basename($_FILES['file']['name']);
        $targetPath = $targetDir . $fileName;

        // 파일 저장 시도
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            echo json_encode(['success' => true, 'message' => '파일이 성공적으로 업로드되었습니다.']);
        } else {
            echo json_encode(['success' => false, 'message' => '파일 저장 실패.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '업로드된 파일이 없습니다.']);
    }
    exit();
}

// 액션 파라미터 확인
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        // md 폴더 내 파일 목록 가져오기
        $files = array_diff(scandir($mdDir), array('.', '..'));
        $fileList = [];
    
        foreach ($files as $file) {
            // 각 파일의 소유자(username) 가져오기
            $stmt = $conn->prepare("SELECT username FROM documents WHERE document = ?");
            $stmt->bind_param("s", $file);
            $stmt->execute();
            $stmt->bind_result($username);
            $stmt->fetch();
            $stmt->close();
    
            // 파일 이름과 소유자(username) 반환
            $fileList[] = [
                'name' => $file,
                'owner' => $username
            ];
        }
    
        echo json_encode($fileList); // JSON으로 파일 목록과 소유자 반환
        break;    

    case 'load':
        // 특정 파일 내용 불러오기
        $fileName = $_GET['name'] ?? '';
        $filePath = $mdDir . '/' . basename($fileName);

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            echo json_encode(['content' => $content, 'username' => getFileOwner($fileName)]); // 내용과 파일 소유자 반환
        } else {
            http_response_code(404); // 파일이 없을 경우 404 반환
            echo json_encode(['error' => '파일을 찾을 수 없습니다.']);
        }
        break;

    case 'delete':
        // 파일 삭제 처리
        $fileName = $_GET['name'] ?? '';
        $filePath = $mdDir . '/' . basename($fileName);

        // 파일이 존재하는지 확인
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo json_encode(['error' => '파일을 찾을 수 없습니다.']);
            exit();
        }

        // 해당 파일의 username을 확인
        $stmt = $conn->prepare("SELECT username FROM documents WHERE document = ?");
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        $stmt->bind_result($fileOwner);
        $stmt->fetch();
        $stmt->close();

        // 문서의 username과 로그인된 username이 일치하는지 확인
        if ($fileOwner !== $_SESSION['username']) {
            http_response_code(403); // 권한 없음
            echo json_encode(['error' => '문서를 삭제할 권한이 없습니다.']);
            exit();
        }

        // 파일 삭제
        if (unlink($filePath)) {
            // 데이터베이스에서 해당 문서 삭제
            $stmt = $conn->prepare("DELETE FROM documents WHERE document = ?");
            $stmt->bind_param("s", $fileName);
            if ($stmt->execute()) {
                echo json_encode(['message' => '파일이 성공적으로 삭제되었습니다.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => '데이터베이스 삭제 실패: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(['error' => '파일 삭제에 실패했습니다.']);
        }
        break;

    default:
        // POST 요청 시 파일 저장 처리
        $input = json_decode(file_get_contents('php://input'), true);
        $fileName = basename($input['fileName'] ?? '');
        $content = $input['content'] ?? '';

        // 파일명 및 내용 검증
        if (!$fileName || !$content) {
            http_response_code(400); // 잘못된 요청
            echo json_encode(['error' => '파일 이름 또는 내용이 비어 있습니다.']);
            exit;
        }

        // 로그인 상태 확인 (소유자 체크는 새 파일 생성 시 제외)
        if (!isset($_SESSION['username'])) {
            http_response_code(403);  // 접근 거부
            echo json_encode(['error' => '로그인이 필요합니다.']);
            exit();
        }

        // 파일 저장 (항상 새로운 파일로 저장)
        $filePath = $mdDir . '/' . $fileName;
        if (file_put_contents($filePath, $content) !== false) {
            $username = $_SESSION['username'];
            
            // 데이터베이스에 문서 추가 (새 문서 생성 시 소유권 확인 X)
            $sql = "INSERT INTO documents (document, username) VALUES (?, ?) 
                    ON DUPLICATE KEY UPDATE username = VALUES(username)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                http_response_code(500);
                echo json_encode(['error' => 'SQL 쿼리 준비 오류: ' . $conn->error]);
                exit();
            }

            $stmt->bind_param("ss", $fileName, $username);

            if ($stmt->execute()) {
                echo json_encode(['message' => '파일이 성공적으로 저장되었습니다.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => '데이터베이스 저장 실패: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(['error' => '파일 저장에 실패했습니다.']);
        }
        break;
}

$conn->close();

// 문서 소유자 조회 함수
function getFileOwner($fileName) {
    global $conn;
    $stmt = $conn->prepare("SELECT username FROM documents WHERE document = ?");
    $stmt->bind_param("s", $fileName);
    $stmt->execute();
    $stmt->bind_result($fileOwner);
    $stmt->fetch();
    $stmt->close();
    return $fileOwner;
}
?>

