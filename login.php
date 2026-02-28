<?php
// Arquivo: sos4/api/login.php
require_once 'conexao.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recebe os dados JSON
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->email) || empty(htmlentities($data->password))) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "E-mail e senha são obrigatórios."]);
        exit();
    }

    $email = $conn->real_escape_string($data->email);
    $password = $data->password;

    // 2. Busca o usuário no banco de dados pelo e-mail
    $sql = "SELECT id, name, email, password FROM sosamazonia WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // 3. Verifica se a senha corresponde ao hash armazenado (MUITO IMPORTANTE!)
        if (password_verify($password, $user['password'])) {
            // LOGIN BEM-SUCEDIDO!
            http_response_code(200);
            echo json_encode([
                "success" => true, 
                "message" => "Login bem-sucedido!",
                "user" => [
                    "name" => $user['name'],
                    "email" => $user['email']
                ]
            ]);
        } else {
            // Senha incorreta
            http_response_code(401); // Unauthorized
            echo json_encode(["success" => false, "message" => "E-mail ou senha incorretos."]);
        }
    } else {
        // Usuário não encontrado
        http_response_code(401); // Unauthorized
        echo json_encode(["success" => false, "message" => "E-mail ou senha incorretos."]);
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>