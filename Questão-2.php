<?php
// conectar banco (SQLite)
$db = new PDO("sqlite:tarefas.db");

// tabela de tarefas
$db->exec("
    CREATE TABLE IF NOT EXISTS tarefas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        descricao TEXT,
        data TEXT,
        concluida INTEGER DEFAULT 0
    )
");
?>

<?php
    include "database.php";
?>

<h2>Sistema de Tarefas</h2>

<!-- adicionar -->
<form method="POST" action="add_tarefa.php">
    <p>Descrição: <input name="descricao" required></p>
    <p>Data: <input type="date" name="data" required></p>
    <button>Adicionar</button>
</form>

<hr>

<h3>Tarefas Pendentes</h3>
<?php
$pend = $db->query("SELECT * FROM tarefas WHERE concluida=0");

foreach($pend as $t){
    echo $t['descricao']." - ".$t['data'];
    echo " <a href='update_tarefa.php?id=".$t['id']."'>Concluir</a>";
    echo " <a href='delete_tarefa.php?id=".$t['id']."'>Excluir</a><br>";
}
?>

<hr>

<h3>Tarefas Concluídas</h3>
<?php
$ok = $db->query("SELECT * FROM tarefas WHERE concluida=1");

foreach($ok as $t){
    echo "<s>".$t['descricao']." - ".$t['data']."</s>";
    echo " <a href='delete_tarefa.php?id=".$t['id']."'>Excluir</a><br>";
}
?>

<?php
include "database.php";

// pegar dados do form
$desc = $_POST['descricao'];
$data = $_POST['data'];

$add = $db->prepare("INSERT INTO tarefas (descricao, data) VALUES (?, ?)");
$add->execute([$desc, $data]);

header("Location: index.php");
?>

<?php
include "database.php";

$id = $_GET['id'] ?? 0;

$db->exec("UPDATE tarefas SET concluida=1 WHERE id=$id");

header("Location: index.php");
?>

<?php
include "database.php";

$id = $_GET['id'] ?? 0;

// apagar
$db->exec("DELETE FROM tarefas WHERE id=$id");

header("Location: index.php");
?>