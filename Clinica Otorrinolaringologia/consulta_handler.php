<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'];

    if ($acao == 'buscar') {
        $cpf = $_POST['cpf'];

        try {
            $sql = "SELECT * FROM consultas WHERE cpf = :cpf";
            $stmt = $pdo_consultas->prepare($sql);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($consulta);
            } else {
                echo json_encode(['erro' => 'Nenhuma consulta encontrada para este CPF.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao buscar consulta: ' . $e->getMessage()]);
        }
    } elseif ($acao == 'atualizar') {
        $id = $_POST['id'];
        $novaData = $_POST['novaData'];
        $novoEspecialista = $_POST['novoEspecialista'];

        try {
            $sql = "UPDATE consultas SET data_consulta = :novaData, especialidade = :novoEspecialista WHERE id = :id";
            $stmt = $pdo_consultas->prepare($sql);
            $stmt->bindParam(':novaData', $novaData);
            $stmt->bindParam(':novoEspecialista', $novoEspecialista);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo json_encode(['sucesso' => 'Consulta atualizada com sucesso!']);
            } else {
                echo json_encode(['erro' => 'Erro ao atualizar a consulta.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Erro ao atualizar consulta: ' . $e->getMessage()]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificar Consultas</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <header>
        <img src="./images/logo.png" alt="Logo da Página" class="logo">
        <nav>
            <ul>
                <li><a href="./home.php">Início</a></li>
                <li><a href="./pages/treatments.html">Tratamentos</a></li>
                <li><a href="./pages/doctors.html">Médicos</a></li>
                <li><a href="./pages/contact.html">Contato</a></li>
                <li><a href="./agendar_consulta.php">Consultas</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="./login.php" class="btn">Login</a>
            <a href="./registrar.php" class="btn">Registro</a>
        </div>
    </header>
    <h2>Verificar Consulta</h2>

    <input type="text" id="cpfInput" placeholder="Digite seu CPF">
    <button onclick="verificarConsulta()">Verificar</button>

    <div id="resultado" style="margin-top: 20px;"></div>

    <!-- Formulário para atualizar consulta -->
    <div id="formulario" style="display: none; margin-top: 20px;">
        <h3>Atualizar Consulta</h3>
        <input type="hidden" id="consultaId">
        <label>Data:</label>
        <input type="datetime-local" id="novaData"><br><br>
        <label>Especialista:</label>
        <input type="text" id="novoEspecialista" placeholder="Especialista"><br><br>
        <button onclick="atualizarConsulta()">Salvar Alterações</button>
        <button onclick="cancelarEdicao()">Cancelar</button>
    </div>

    <script src="./script.js"></script>
</body>
</html>
