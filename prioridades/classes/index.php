<?php
class conexao{
	function __construct(){
		$nome_banco      = 'prioridades';
		$nome_collection = 'tarefas';
		
		$this->conexao = new Mongo();
		$this->db = $this->conexao->$nome_banco;
		$this->collection = $this->db->$nome_collection;
		
		header ('Content-type: text/html; charset=utf-8');
	}
}

class prioridade extends conexao{
	function mostrar($_id){
		$mongo_id = new MongoID($_id);
		return $this->collection->findOne(array('_id' => $mongo_id));
	}
	
	function listar($filter){
		return $this->collection->find($filter);
	}
	
	function inserir(){
		$this->query = array(
			'UsuarioID'  => $this->UsuarioID,
			'Usuario'    => $this->Usuario,
			'Tarefa'     => $this->Tarefa,
			'Tipo'       => $this->Tipo,//Obrigatório, idéia, outro
			'Prioridade' => $this->Prioridade
		);
		$this->collection->insert($this->query);
	}
	
	function mudar_tarefa(){
		$this->mongo_id = new MongoID($this->_id);
		$this->collection->update(array('_id' => $this->mongo_id), array('$set' => array('Tarefa meuu' => $this->Tarefa)), true);
	}
	
	function mudar_prioridade(){
		$this->mongo_id = new MongoID($this->_id);
		
		if($this->modo=='up'){
			$this->collection->update(array('_id' => $this->mongo_id), array('$inc' => array('Prioridade' => 1)), false);
		}elseif($this->modo=='down'){
			$this->collection->update(array('_id' => $this->mongo_id), array('$inc' => array('Prioridade' => -1)), false);
		}
	}
	
	function excluir(){
		$this->mongo_id = new MongoID($this->_id);
		$this->collection->remove(array('_id' => $this->mongo_id));
	}
}
?>