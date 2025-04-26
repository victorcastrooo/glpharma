<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../db_connect.php';

header('Content-Type: application/json');

// Função para obter as marcas mais vendidas
function getMarcasMaisVendidas($conn) {
    $sql = "SELECT m.nomemarca, SUM(ip.quantidade) AS total_vendido 
            FROM itens_pedido ip 
            JOIN produto p ON ip.produto_id = p.id 
            JOIN marca m ON p.marca_id = m.id 
            GROUP BY m.nomemarca 
            ORDER BY total_vendido DESC";
    $result = $conn->query($sql);
    
    $marcas = [];
    while ($row = $result->fetch_assoc()) {
        $marcas[] = $row;
    }
    return $marcas;
}

// Obter os dados
$data = [
    'marcas' => getMarcasMaisVendidas($conn)
];

// Retornar os dados como JSON
echo json_encode($data);
?>