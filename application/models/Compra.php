<?php

class Compra extends Zend_Db_Table_Row_Abstract {

    public function listarComprasAtivas($status) {
        
        $tCompra = new DbTable_Compra();
        $query = $tCompra->select()
                ->where('status in (?)', $status);

        return $tCompra->fetchAll($query);
    }

    public function inserir($post) {

            $tCompra = new DbTable_Compra();
            $novaCompra = $tCompra->createRow();
            $novaCompra->setFromArray($post);
            $novaCompra->save();
    }

    public function mostrarStatusAtual($solicitacaoid) {
        //refatorar para chamar somente a coluna status
        $tSolicitacao = new DbTable_Solicitacao();
        $query = $tSolicitacao->select()
                ->where('id = (?)', $solicitacaoid);

        return $tSolicitacao->fetchAll($query);
    }

    public function atualizarStatus($idSolicitacao, $statusatual, $gerente_responsavel) {
        
        switch ($statusatual) {
            case 'nova':
                $status = 'em analise';
                break;
            case 'em analise':
                $status = 'aprovada';
                break;
            case 'reprovada':
                $status = 'reprovada';
                break;
            case 'entregue':
                $status = 'entregue';
                break;
            case 'recebida';
                $status = 'recebida';
                break;
            case 'cancelada':
                $status = 'cancelada';
                break;
            case 'aprovada':
                $status = 'aprovada';
                break;
            case 'pendente':
                $status = 'pendente';
                break;
            case 'entregue';
                $status = 'entregue';
            default:
                $status = 'recebida';
                break;
            
            
        }

        $tSolicitacao = new DbTable_Solicitacao();
        $solicitacao = $tSolicitacao->find($idSolicitacao);

        $dados = array('status' => $status);

        $solicitacao->current()->setFromArray($dados);
        $solicitacao->current()->save();
        
        if ($gerente_responsavel){
                
                $this->atualizarGerenteResponsavel($gerente_responsavel, $solicitacao);
                
            }
        
    }

    public function listarHistorico($usuarioId) {

        $lista = ['recebida', 'reprovada', 'cancelada'];
        
        $tSolicitacao = new DbTable_Solicitacao();
        $query = $tSolicitacao->select()
                ->where('usuarioid = (?)', $usuarioId)
                ->where('status in (?)', $lista); 

        return $tSolicitacao->fetchAll($query);
    }
    
    
    public function listarAgendadas(){
        
        $lista = ['agendada', 'pendente'];
        $tSolicitacao = new DbTable_Solicitacao();
        $query = $tSolicitacao->select()
                ->where('status in (?)', $lista); 

        return $tSolicitacao->fetchAll($query);

    }

    public function listarReprovadas(){

        $tSolicitacao = new DbTable_Solicitacao();
        $query = $tSolicitacao->select()
            ->where('status = (?)', 'reprovada');

        return $tSolicitacao->fetchAll($query);

    }
    
     public function listarCanceladas(){

        $tSolicitacao = new DbTable_Solicitacao();
        $query = $tSolicitacao->select()
            ->where('status = (?)', 'cancelada');

        return $tSolicitacao->fetchAll($query);

    }

    
    public function atualizaDataDeRecebimento($idSolicitacao,$data_recebimento){
        
        
        $tSolicitacao = new DbTable_Solicitacao();
        $solicitacao = $tSolicitacao->find($idSolicitacao);
        
        $post = ['data_recebimento' => $data_recebimento];
        $solicitacao->current()->setFromArray($post);
        $solicitacao->current()->save();
        
    }
    
    
    public function atualizaDataDeaprovação($idSolicitacao,$data_aprovacao){
        
        $tSolicitacao = new DbTable_Solicitacao();
        $solicitacao = $tSolicitacao->find($idSolicitacao);
        
        $post = ['data_aprovacao' => $data_aprovacao];
        $solicitacao->current()->setFromArray($post);
        $solicitacao->current()->save();
        
        
    }
    
    public function selecionarUltimaSolicitacaoCadastrada(){
        
         $tSolicitacao = new DbTable_Solicitacao();
         $query = $tSolicitacao->select()
                ->from($tSolicitacao, array(new Zend_Db_Expr("MAX(id) AS maxID")));
               
        return $tSolicitacao->fetchAll($query);
        
    }
    
    public function listarSolicitacoesGerente(){
        
         $solicitacoesAtivas = ['em analise', 'agendada', 'pendente', 'aprovada', 'entregue'];
        
        $tSolicitacao = new DbTable_Solicitacao();
        $query = $tSolicitacao->select()
                ->where('status in (?)', $solicitacoesAtivas); 

        return $tSolicitacao->fetchAll($query);
        
    }
    
    public function atualizarGerenteResponsavel($gerente_responsavel, $solicitacao){
        
        
        $dados = array('gerente_responsavel' => $gerente_responsavel);

        $solicitacao->current()->setFromArray($dados);
        $solicitacao->current()->save();
        
        
        
    }


}