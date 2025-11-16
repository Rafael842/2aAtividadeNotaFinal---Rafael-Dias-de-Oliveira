<?php
// Conexão com o banco SQLite
// Cria o arquivo automaticamente se não existir
$db = new PDO("sqlite:livraria.db");

// Criar tabela (só cria 1 vez)
$db->exec("
    CREATE TABLE IF NOT EXISTS livros (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT,
        autor TEXT,
        ano INTEGER
    )
");
?>

<?php
    // puxar o banco
    include "database.php";
?>

<h2>Livraria (trabalho simples)</h2>

<!-- Form para adicionar livro -->
<form method="POST" action="add_book.php">
    <p>Título: <input name="titulo" required></p>
    <p>Autor: <input name="autor" required></p>
    <p>Ano: <input type="number" name="ano" required></p>
    <button>Adicionar</button>
</form>

<hr>

<h3>Livros cadastrados</h3>

<?php
// listar os livros
$lista = $db->query("SELECT * FROM livros");

foreach($lista as $l){
    echo "ID: ".$l['id']." - ".$l['titulo']." (".$l['autor'].") - ".$l['ano'];
    echo " | <a href='delete_book.php?id=".$l['id']."'>Excluir</a><br>";
}
?>

<?php
// adiciona no banco
include "database.php";

$t = $_POST['titulo'];
$a = $_POST['autor'];
$ano = $_POST['ano'];

$add = $db->prepare("INSERT INTO livros (titulo, autor, ano) VALUES (?, ?, ?)");
$add->execute([$t, $a, $ano]);

header("Location: index.php");
?>

<?php
include "database.php";

$id = $_GET['id'] ?? 0;

// apagar pelo id
$db->exec("DELETE FROM livros WHERE id=$id");

header("Location: index.php");
?>