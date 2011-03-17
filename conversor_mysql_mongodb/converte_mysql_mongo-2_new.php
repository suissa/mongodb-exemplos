<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Convertendo</title>
<style type="text/css">
.erro {
	padding-left: 30px;
	line-height: 30px;
	color: #FF0000;
	font-size: 30px;
	background: url(erro_icone.jpg) no-repeat bottom left;
	font-family: "Times New Roman", Times, serif;
}
.dica {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color:#333333;
	margin-left: 50px;
	display: block;
	float: left;
	text-indent: -40px;
}
</style>
</head>

<body>

<?php
$host = isset($_REQUEST['host_mysql']) ? $_REQUEST['host_mysql'] : "localhost";
$user = isset($_REQUEST['user_mysql']) ? $_REQUEST['user_mysql'] : "root";
$pass = isset($_REQUEST['pass_mysql']) ? $_REQUEST['pass_mysql'] : "a";
$db   = isset($_REQUEST['db_mysql']) ? $_REQUEST['db_mysql'] : "teste_latin";
//BANCO MYSQL
$query_pega_tabelas = "SHOW TABLES";
$index_tabelas = "Tables_in_".$db; //nome do array com o valor do nome das tabelas via SHOW TABLES
//BANCO MYSQL
$db_mysql = $db;
$db_suissa = 'suissola';
/*	MONGODb	*/
try{
$conexao = new Mongo();
$db_mongo = $conexao->$db_mysql;
//$collection = $db_mongo->teste_tamanho; 
}catch(MongoConnectionException $e){
	die("<h1 class='erro'>Erro do banco de dados MONGODB - Inicie o servidor do MongoDD!</h1><span class='dica'>Dica: Entre no prompt do windows<br />
 cd \ <br />
 cd mongodb<br />
cd bin<br />
mongod </span>");
}
function getTime(){ static $tempo; if( $tempo == NULL ){ $tempo = microtime(true); } else{ echo 'Tempo de inserção no MongoDB (segundos): '.(microtime(true)-$tempo).''; } } 
/*	MONGODb	*/

$linkk = mysql_connect($host,$user,$pass);
if(!$linkk){
	die("Erro na conexao! ".mysql_error());
}
else{
	if(!mysql_select_db($db,$linkk))
		die("Arrumar selecao do banco de dados!");
	else {
		echo "Vamos ler o banco de dados MYSQL agora!";
         	if($result_pega_tabelas=mysql_query($query_pega_tabelas)){
				//echo "<h1>Tabelas: </h1>";
				
				$query_charset = "SHOW VARIABLES LIKE 'character_set%'";
				while($arr_pega_tabelas[] = mysql_fetch_assoc($result_pega_tabelas)){
					//echo "<hr />";
				}
				$rs_charset=mysql_query($query_charset);
				
				
				foreach($arr_pega_tabelas as $c=>$v){
					//var_dump($v);
					if(is_array($v)){
						foreach($v as $a => $b){
							echo "<h3>Tabela: ".$b."</h3>";	
							$nome_tabela = $b;
						  //$arr_nome_tabelas[$nome_tabela ] = $v[$index_tabelas];
							$query_pega_dados = "EXPLAIN ".$nome_tabela;	
							//echo "<h1>$query_pega_dados</h1>";
							 if($result_pega_dados=mysql_query($query_pega_dados)){
								$arr_pega_dados = array();
								while($arr_pega_dados = mysql_fetch_assoc($result_pega_dados)){
									/*echo "<pre>";
									var_dump($arr_pega_dados);
									echo "</pre>";*/
									$arr_totalis[$nome_tabela][] = $arr_pega_dados;
									//criacao do array com nome das tabelas e seus metadados
									//$arr_meta_dados[$nome_tabela] = $arr_pega_dados;
								}//fim while
							}//fim id query pega dados
						}//fim foreach
					}//fim if array
				}//fim foreeach
				foreach($arr_totalis as $c => $v){
						$query = 'select * from '.$c;
						$collection = $db_mongo->$c;
						
						$rs_select = mysql_query($query);
						echo "Inserindo em ".$c."<br />";
						echo "<pre>";	
						
						while($arr_dados = mysql_fetch_assoc($rs_select)){
							foreach($arr_dados as $c => $v){
								$obj[utf8_encode_suissa($c)] = utf8_encode_suissa($v);
							}
							$arr[] = $obj;
						}	
						$erro = 0;			
 						getTime(); 
						foreach($arr as $cc => $vv){
							if(!$collection->insert($vv)){
								echo "Não inseriu".print_r($c) ;
								$erro++;
								$var_erro[] = var_dump($vv);
							}
						}
						
						 getTime(); 
						if($erro > 0){
							echo "<h3>Aconteceram os seguintes erros:</h3>";
							foreach($var_erro as $f => $g){
								var_dump($g);
							}
						}
				echo "</pre>";
				}
			}//fim if	
	}//fim else
}
function utf8_encode_suissa($s) {
  return iconv('iso-8859-1', 'utf-8', $s);
}
?>
</body>
</html>