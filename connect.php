<?php
 function conectarBanco() {
 // Configurações do banco de dados
 $host = 'localhost';
 $username = 'root';  // Usuário do MySQL
 $password = '';      // Senha do MySQL
 $dbname = 'sidralpizza';

 // Criando a conexão
 $conn = new mysqli($host, $username, $password, $dbname);

 // Verificando a conexão
 if ($conn->connect_error) {
     die("Conexão falhou: " . $conn->connect_error);
 }

 return $conn;  // Retorna a conexão
 }
?>