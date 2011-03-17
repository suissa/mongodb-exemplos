<?php
include_once("classes/index.php");
?>

<style type='text/css'>
	body{
		font-size:25px;
	}
	
	a:link{text-decoration:none;color:#090}
	a:visited{text-decoration:none;color:#090}
	a:hover{text-decoration:none;color:#F03}
	
	#updown{
		font-size:10px;
	}
</style>
<?php
$prioridade = new prioridade;

$action = $_REQUEST["action"];
if($action == ""){
	//só lista
}elseif($action == "inserir"){
	//só insere
	$prioridade->UsuarioID  = 0;
	$prioridade->Usuario    = 'todos';
	$prioridade->Tarefa     = $_REQUEST["Tarefa"];
	$prioridade->Tipo       = 'obrigatório';
	$prioridade->Prioridade = 0;
	$prioridade->inserir();
}elseif($action == "alterar"){
	//só altera
	$prioridade->_id = $_REQUEST['_id'];
	$prioridade->Tarefa = $_REQUEST['Tarefa'];
	$prioridade->mudar_tarefa();
	
	$cursorShow = $prioridade->mostrar($_REQUEST['_id']);
}elseif($action == "alterarPrioridade"){
	//só altera
	$prioridade->_id = $_REQUEST['_id'];
	$prioridade->modo = $_REQUEST['p'];
	$prioridade->mudar_prioridade();
	
	$cursorShow = $prioridade->mostrar($_REQUEST['_id']);
}elseif($action == "excluir"){
	//só exclui
	$prioridade->_id = $_REQUEST['_id'];
	$prioridade->excluir();
	
	$cursorShow = $prioridade->mostrar($_REQUEST['_id']);
}else{
	//erro
	echo "<h1>ERRO</h1>
	<img src='http://images.uncyc.org/pt/1/1d/Error404.png' style='float:right;padding-left:10px;' />
	Erro é um vício no processo de formação da vontade, em forma de noção falsa ou imperfeita sobre alguma coisa ou alguma pessoa.
	Erro é um dos vícios do consentimento dos negócios jurídicos. A manifestação de vontade é defeituosa devido a uma má interpretação dos fatos.
	Subdivide-se em erro substancial ou essencial e erro acidental. O erro substancial invalida o ato jurídico. O erro acidental é aquele que pode ser resolvido facilmente, não invalidando o ato jurídico.
	<br/><br/>
	<em>http://pt.wikipedia.org/wiki/Erro</em>";
	exit;
}

if(isset($_REQUEST['_id']))$cursorShow = $prioridade->mostrar($_REQUEST['_id']);

$filter = array();
$cursor = $prioridade->listar($filter);
$cursor->sort(array('Prioridade' => -1))->limit(41)->skip(0);


foreach($cursor as $i => $item){
	echo "
	<a href='?action=excluir&_id=$i'>x</a> - 
	<a href='?_id=$i'>" . $item['Tarefa'] . "</a> 
	<span id='updown'>[<a href='?action=alterarPrioridade&_id=$i&p=up'>▲</a>
					   <a href='?action=alterarPrioridade&_id=$i&p=down'>▼</a>] 
	$item[Prioridade]</span><br/>";
}

?>
	<br/><br/>
<form action='index.php' method='get'>
	<label>Nova tarefa</label>
    <input type='text' id='Tarefa' name='Tarefa' />
    <input type='hidden' name='_id' value='<?php echo $_REQUEST['_id'];?>' />
    <input type='hidden' name='action' value='inserir' />
    <input type='submit' value='go' />
</form>
<form action='index.php' method='get'>
	<label>Alterar tarefa</label>
    <input type='text' id='Tarefa' name='Tarefa' value='<?php echo $cursorShow['Tarefa'];?>' />
    <input type='hidden' name='_id' value='<?php echo $_REQUEST['_id'];?>' />
    <input type='hidden' name='action' value='alterar' />
    <input type='submit' value='go' />
</form>
