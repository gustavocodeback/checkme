<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permissoes_lib {

   /**
	* _ci
	*
	* @private 
	*/
    private $_ci;

   /**
	* __construct
	* 
	* metodo construtor
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function __construct() {
        
        // instancia do codeigniter
        $this->_ci =& get_instance();

        // carrega a model
        $this->_ci->load->model( [ 'Classificacoes_model', 'Rotinas_model' ] );
    }

   /**
	* __isActive
	* 
	* verifica se esta ativo
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    private function __isActive( $rotinas, $controller ) {

        // percorre todas as rotinas
        foreach( $rotinas as $chave => $rotina ){
            $parts = explode( '/', $rotina['desc_link'] );
            if ( $parts[0] == $controller ) {
                $rotinas[$chave]['active'] = true;
                return $rotinas;
            }
        }

        // volta false por padrao
        return false;
    }

   /**
	* getMenu
	* 
	* monta o array para montar o menu
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function getMenu( $controller ) {

        // verifica se existem classificacoes
        if ( $classificacoes = $this->_ci->Classificacoes_model->getSorted() ) {

            // percorre as classificacoes
            foreach ( $classificacoes as $chave => $classificacao ) {

                $rotinas = $this->_ci->Rotinas_model->getByClassificacaoId( $classificacao['classificacao_id'] );

                // verifica se existem rotinas
                if ( $rotinas ) {

                    // percorre todas as rotinas
                    foreach ( $rotinas as $chave_rotina => $rotina ) {

                        // verifica as permissoes do usuario para a rotina
                        if ( !$this->hasPermission( false, $rotina['desc_rotina'] ) ) {
                            unset( $rotinas[$chave_rotina] );
                        }
                    }

                    // verifica se nenhum rotina existe
                    if ( !is_array( $rotinas ) || count( $rotinas ) == 0 ){
                        unset( $classificacoes[$chave] );
                    } else {

                        // faz o teste
                        $check = $this->__isActive( $rotinas, $controller );

                        // verifica se a rotina ative esta aqui
                        if (  $check ) {
                            $classificacoes[$chave]['active']  = true;
                            $classificacoes[$chave]['rotinas'] = $check;
                        } else {
                            $classificacoes[$chave]['rotinas'] = $rotinas;
                        }
                    }                
                } else {
                    unset( $classificacoes[$chave] );
                }
            } 
            return $classificacoes;
        } else return false;
    }

   /**
	* hasPermission
	* 
	* verifica se o usuario tem permissao
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function hasPermission( $action, $routine ) {

        // carrega a lib de autenticacao
        $this->_ci->load->library( 'Auth' );

        // pega a rotina
        $this->_ci->load->model( [ 'Rotinas_model' ] );

        
        // verifica a permissao
        if ( $rotina = $this->_ci->Rotinas_model->getRoutine( $routine, $this->_ci->auth->user('cargo') ) ) {
            
            // verifica a ação
            if ( $action ) return ( $rotina['flg_'.$action] === 'S');

            // verifica para todas as ações de uma vez
            if ( $rotina['flg_criar']   === 'N' &&
                 $rotina['flg_ver']     === 'N' &&
                 $rotina['flg_excluir'] === 'N' &&
                 $rotina['flg_editar']  === 'N' ) return false;
            
            // volta verdadeiro por padrao
            return true;
        } else return false;
    }   
}