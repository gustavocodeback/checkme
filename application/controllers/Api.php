<?php defined('BASEPATH') OR exit('No direct script access allowed');

// controller com os métodos da api
class Api extends CI_Controller {

    /**
	* _user
	* 
	* @private
	*/
    private $_user = false;

    // método constructor
	public function __construct() {
		parent::__construct();

        // arruma o json
        $data = json_decode(file_get_contents('php://input'), true);
        if ( $data ) $_POST = $data;

        // carrega a library
        $this->load->library( 'Rest' );
	}

   /*--------------------------------------------------------
    *
    * USUARIOS
    *
    * METODOS REFERENTES AO CONTROLE DE USUARIOS
    *
    *-------------------------------------------------------*/

    // checa o uid
    public function checar_uid( $uid ) {

        // carrega a model
        $this->load->model( 'Usuarios_model' );

        // pega o usuario
        $user = $this->Usuarios_model->obterUsuarioPorUid( $uid );

        // busca o usuario
        if ( !$user ) {
            
            // prepara os dados
            $dados = [
                'email' => $this->input->post( 'email' ),
                'uid'   => $uid
            ];

            // cria o usuario
            $this->Usuarios_model->create( $dados );
            $this->rest->resolve( $dados );
            
        } else $this->rest->resolve( $user );
    }

    // cadastra um novo usuario
    public function cadastrar_usuario() {

        // regras de validação
		$this->form_validation->set_rules( 'email', 'E-mail','required|valid_email|trim' );
		$this->form_validation->set_rules( 'nome', 'Nome', 'required|max_length[50]|min_length[1]|trim' );
		$this->form_validation->set_rules( 'cidade', 'Cidade', 'required|trim' );
		$this->form_validation->set_rules( 'uid', 'UID', 'required' );

        // verifica se o formulário é válido
        if ( $this->form_validation->run() !== false ) {

            // verifica se o email ja foi cadastrado
            $this->load->model( 'Usuarios_model' );


            // pega os dados
            $dados = [
                'nome'   => $this->input->post( 'nome' ),
                'email'  => $this->input->post( 'email' ),
                'uid'    => $this->input->post( 'uid' ),
                'cidade' => $this->input->post( 'cidade' ),
                'status' => 'A',
            ];

            // carrega a model
            $this->load->model( 'Usuarios_model' );

            // tenta criar o usuário
            if ( $this->Usuarios_model->create( $dados ) ) {

                // volta o usuário
                $this->rest->resolve( $dados );
            } else $this->rest->reject(  'Não foi possivel criar o usuário' );
        } else $this->rest->reject( validation_errors() );
    }

    // obtem os dados de um usuario
    public function usuario( $uid ) {

        // carrega a model
        $this->load->model( 'Usuarios_model' );

        // busca o usuario
        if ( $user = $this->Usuarios_model->obterUsuarioPorUid( $uid ) ) {
            $this->rest->resolve( $user );
        } else $this->rest->reject( 'Nenhum usuário encontrado' );
    }

    // altera os dados de um usuario
    public function alterar_usuario() {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // regras de validação
		$this->form_validation->set_rules( 'email', 'E-mail','required|valid_email|trim' );
		$this->form_validation->set_rules( 'nome', 'Nome', 'required|max_length[50]|min_length[1]|trim' );
		$this->form_validation->set_rules( 'cidade', 'Cidade', 'required|trim' );
		$this->form_validation->set_rules( 'uid', 'UID', 'required' );

        // pega o usuario
        $user = $this->rest->user();

        // verifica se o formulário é válido
        if ( $this->form_validation->run() !== false ) {

            // verifica se o email ja foi cadastrado
            $this->load->model( 'Usuarios_model' );

            // pega os dados
            $dados = [
                'nome'   => $this->input->post( 'nome' ),
                'email'  => $this->input->post( 'email' ),
                'cidade' => $this->input->post( 'cidade' ),
                'foto'   => $this->input->post( 'foto' ),
                'status' => 'A',
                'id'     => $user['id']
            ];

            // carrega a model
            $this->load->model( 'Usuarios_model' );

            // tenta criar o usuário
            if ( $this->Usuarios_model->update( $dados ) ) {

                // volta o usuário
                $this->rest->resolve( $dados );
            } else $this->rest->reject(  'Não foi possivel alterar o usuário' );
        } else $this->rest->reject( validation_errors() );
    }

    /*--------------------------------------------------------
    *
    * CIDADES
    *
    * METODOS REFERENTES A CIDADES
    *
    *-------------------------------------------------------*/

    // obtem as cidades cadastradas
    public function obter_cidades() {

        // carrega a model
        $this->load->model( 'Cidades_model' );

        // carrega todas as cidades
        $cidades = $this->Cidades_model->getAll();

        // volta as cidades obtidas
        $this->rest->resolve( $cidades );
    }

    /*--------------------------------------------------------
    *
    * BANDAS
    *
    * METODOS REFERENTES A BANDAS
    *
    *-------------------------------------------------------*/
    
    // obtem as bandas cadastradas
    public function obter_bandas( $pagina = 1, $busca = '' ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario logado
        $user = $this->rest->user();
        $busca = utf8_decode( urldecode( $busca ));

        // carrega a model de bandas
        $this->load->model( [ 'Bandas_model', 'Interacoes_model' ] );

        // obtem as bandas
        $bandas = $this->Bandas_model->obterBandas( $pagina, $busca );

        // percorre todas as bandas
        foreach( $bandas as $chave => $banda ) {

            // verifica se o usuário logado já favoritou a banda
            $banda['favorito'] = $this->Interacoes_model->favoritou( $user['id'], 'banda_id', $banda['id'] );
            if ( $banda['foto'] ) {
                $banda['foto'] = base_url( 'uploads/'.$banda['foto'] );
            } else $banda['foto'] = null;

            // arruma a descricao
            $banda['descricao'] = trim( strip_tags( $banda['descricao'] ) );

            // adiciona a banda
            $bandas[$chave] = $banda;
        }

        // volta as bandas
        $this->rest->resolve( $bandas );
    }

    /*--------------------------------------------------------
    *
    * INTERACAO
    *
    * METODOS REFERENTES AS INTERACOES
    *
    *-------------------------------------------------------*/
    
    // favorita um item
    public function favoritar( $tipo = false, $id = false ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario logado
        $user = $this->rest->user();

        // verifica se é um tipo valido
        $tipos = [ 'lugar', 'evento', 'banda' ];
        if ( !in_array( $tipo, $tipos ) ) return $this->rest->reject( 'Não é um tipo válido' );

        // verifica se existe um id
        if ( !$id ) return $this->rest->reject( 'Voce deve informar um id' );

        // carrega a model
        $this->load->model( 'Interacoes_model' );

        // prepara os dados
        $dados = [
            'usuario_id' => $user['id'],
            $tipo.'_id'  => $id,
            'interacao'  => 'F'
        ];

        // tenta criar a interacao
        if ( $this->Interacoes_model->create( $dados ) ) {
            
            // seta o id
            $dados['id'] = $this->db->insert_id();

            // volta os dados
            return $this->rest->resolve( $dados );
        } else return $this->rest->reject( 'Erro ao favoritar' );
    }

    // desvaforita um item
    public function desfavoritar( $tipo = false, $id = false ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario logado
        $user = $this->rest->user();

        // verifica se é um tipo valido
        $tipos = [ 'lugar', 'evento', 'banda' ];
        if ( !in_array( $tipo, $tipos ) ) return $this->rest->reject( 'Não é um tipo válido' );

        // verifica se existe um id
        if ( !$id ) return $this->rest->reject( 'Voce deve informar um id' );

        // carrega a model
        $this->load->model( 'Interacoes_model' );

        // tenta desvaforitar
        if ( $this->Interacoes_model->desfavoritar( $user['id'], $tipo.'_id', $id ) ) {
            $this->rest->resolve( 'Item desfavoritado com sucesso!' );
        } else $this->rest->reject( 'Erro ao desfavoritar' );
    }

    // obtem os favoritos de um usuario
    public function favoritos( $tipo = false, $pagina = 1 ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario logado
        $user = $this->rest->user();
        $user_id = $user['id'];

        // verifica se é um tipo valido
        $tipos = [ 'lugar', 'evento', 'banda' ];
        if ( !in_array( $tipo, $tipos ) ) return $this->rest->reject( 'Não é um tipo válido' );

        // monta a query
        $this->db->from( 'interacoes i' )
        ->select( 'e.*, c.categoria' );

        // faz um switch case
        switch( $tipo ) {
            case 'lugar':
                $this->db->join( 'locais e', 'e.id = i.lugar_id'  );
                $this->db->join( 'categorias_lugares c', 'c.id = e.categoria_id'  );
                $this->db->where( " i.usuario_id = $user_id AND 
                                    i.interacao = 'F' AND
                                    e.status <> 'N' " );
            break;
            case 'banda':
                $this->db->join( 'bandas e', 'e.id = i.banda_id'  );            
                $this->db->join( 'categorias_bandas c', 'e.categoria_id = c.id'  );            
                $this->db->where( " i.usuario_id = $user_id AND 
                                    i.interacao = 'F' AND
                                    e.status <> 'N' " );
            break;
            case 'evento':
                $this->db->join( 'eventos e', 'e.id = i.evento_id'  );
                $this->db->join( 'categorias_eventos c', 'c.id = e.categoria_id'  );
                $this->db->where( " i.usuario_id = $user_id AND 
                                    i.interacao = 'F' AND
                                    i.evento_id IS NOT NULL " );                    
            break;
        }

        // seta a pagina
        $pagina = ( $pagina - 1 ) * 5;
        $this->db->limit( 5, $pagina );

        // faz a busca
        $busca     = $this->db->get();
        $resultado = $busca->result_array();

        // percorre os dados
        foreach( $resultado as $chave => $item ) {
            
            // filtra a descricao
            $item['descricao'] = trim( strip_tags( $item['descricao'] ) );
            
            // verifica se o usuario favoritou o lugar
            if ( $item['foto'] ) {
                $item['foto'] = base_url( 'uploads/'.$item['foto'] );
            } else $item['foto'] = null;
            $item['favorito'] = true;

            // adiciona o item
            $resultado[$chave] = $item;
        }

        // volta os dados
        $this->rest->resolve(  $resultado );
    }

    /*--------------------------------------------------------
    *
    * LUGARES E ESTABELECIMENTOS
    *
    * METODOS REFERENTES A LUGARES E ESTABELECIMENTOS
    *
    *-------------------------------------------------------*/

    // pega os estabelecimentos
    public function locais( $tipo = 'estabelecimentos', $pagina = 1, $busca = '' ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();
        $busca = utf8_decode( urldecode( $busca ));

        // pega a cidade
        $cidade_id = $this->rest->header( 'filter_cidade' );
        if ( !$cidade_id ) return $this->rest->reject( 'Nenhuma cidade informada' );

        // verifica se é um tipo válido
        $tipos = [ 'estabelecimentos', 'turismo' ];
        $tipos_r = [ 'estabelecimentos' => 'E', 'turismo' => 'L' ];
        if ( !in_array( $tipo, $tipos ) ) return $this->rest->reject( 'Nao é um tipo válido' );
        $tipo = $tipos_r[$tipo];

        // carrega a model
        $this->load->model( [ 'Locais_model', 'Interacoes_model' ] );

        // page os lugares
        $lugares = $this->Locais_model->obterLugares( $pagina, $busca, $tipo, $cidade_id );

        // percorre os lugares encontrados
        foreach( $lugares as $l => $lugar ) {

            // verifica se o usuario favoritou o lugar
            $lugar['favorito'] = $this->Interacoes_model->favoritou( $user['id'], 'lugar_id', $lugar['id'] );
            if ( $lugar['foto'] ) {
                $lugar['foto'] = base_url( 'uploads/'.$lugar['foto'] );
            } else $lugar['foto'] = null;
            $lugar['descricao'] = trim( strip_tags( $lugar['descricao'] ) );
            $lugares[$l] = $lugar;
        }

        // retorna os resultados
        return $this->rest->resolve( $lugares );
    }

    // pega os estabelecimentos de um usuarios
    public function meus_locais() {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();

        // faz a query
        $this->db->from( 'locais' )
        ->select( '*' )
        ->where( " usuario_id = ".$user['id']." AND status = 'a' " );

        // faz a busca
        $busca = $this->db->get();

        // volta os resultados
        $this->rest->resolve( $busca->result_array() );
    }

    // pega os detalhes de um estabelecimento
    public function local( $id ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();

        // carrega a model
        $this->load->model( [ 'Locais_model', 'Interacoes_model' ] );

        // pega o local
        $local = $this->Locais_model->getById( $id );

        // verifica se achou um local
        if ( $local ) {

            // prepara a foto
            $local['foto'] = base_url( 'uploads/'.$local['foto'] );
            $local['descricao'] = trim( strip_tags( $local['descricao'] ) );
            $local['favorito'] = $this->Interacoes_model->favoritou( $user['id'], 'lugar_id', $local['id'] );
            
            // envia o local
            return $this->rest->resolve( $local );
        } else return $this->rest->reject( $id );
    }
    
    /*--------------------------------------------------------
    *
    * EVENTOS
    *
    * METODOS REFERENTES A EVENTOS
    *
    *-------------------------------------------------------*/

    // pega as categorias
    public function categorias() {

        // carrega a model
        $this->load->model( 'Categorias_eventos_model' );

        // carrega todas as cidades
        $categorias = $this->Categorias_eventos_model->getAll();

        // volta as cidades obtidas
        $this->rest->resolve( $categorias );
    }

    // pega os eventos
    public function obter_eventos( $pagina = 1, $busca = '' ){

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();
        $busca = utf8_decode( urldecode( $busca ) );

        // pega a cidade
        $cidade_id = $this->rest->header( 'filter_cidade' );
        if ( !$cidade_id ) return $this->rest->reject( 'Nenhuma cidade informada' );

        // carrega as models
        $this->load->model( [ 'Eventos_model', 'Interacoes_model' ] );

        // carrega os eventos
        $eventos = $this->Eventos_model->obterEventos( $pagina, $busca, $cidade_id );

        // percorre todos os eventos
        foreach( $eventos as $e => $evento ) {

            // verifica se o usuario favoritou o lugar
            $evento['favorito'] = $this->Interacoes_model->favoritou( $user['id'], 'evento_id', $evento['id'] );
            if ( $evento['foto'] ) {
                $evento['foto'] = base_url( 'uploads/'.$evento['foto'] );
            } else $evento['foto'] = null;
            $evento['descricao'] = trim( strip_tags( $evento['descricao'] ) );
            $evento['data'] = date( 'd/m/Y', strtotime( $evento['data'] ) );
            $eventos[$e] = $evento;
        }

        // envia os eventos
        return $this->rest->resolve( $eventos );
    }

    // pega os eventos do usuario logado
    public function meus_eventos( $pagina = 1 ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();

        // carrega as models
        $this->load->model( [ 'Eventos_model' ] );

        // carrega os eventos
        $eventos = $this->Eventos_model->obterMeusEventos( $pagina, $user['id'] );

        // percorre todos os eventos
        foreach( $eventos as $e => $evento ) {

            // verifica se o usuario favoritou o lugar
            if ( $evento['foto'] ) {
                $evento['foto'] = base_url( 'uploads/'.$evento['foto'] );
            } else $evento['foto'] = null;
            $evento['descricao'] = trim( strip_tags( $evento['descricao'] ) );
            $evento['data'] = date( 'd/m/Y', strtotime( $evento['data'] ) );
            $eventos[$e] = $evento;
        }

        // envia os eventos
        return $this->rest->resolve( $eventos );
    }

    // pega os eventos de um lugar
    public function eventos_lugar( $lugar_id = false, $pagina = 1, $busca = '' ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();

        // verifica se existe um lugar
        if ( !$lugar_id ) return $this->rest->reject( 'Nenhum lugar informado' );

        // carrega as models
        $this->load->model( [ 'Eventos_model', 'Interacoes_model' ] );

        // carrega os eventos
        $eventos = $this->Eventos_model->obterEventosLugar( $pagina, $lugar_id );

        // percorre todos os eventos
        foreach( $eventos as $e => $evento ) {

            // verifica se o usuario favoritou o lugar
            $evento['favorito'] = $this->Interacoes_model->favoritou( $user['id'], 'evento_id', $evento['id'] );
            if ( $evento['foto'] ) {
                $evento['foto'] = base_url( 'uploads/'.$evento['foto'] );
            } else $evento['foto'] = null;
            $evento['descricao'] = trim( strip_tags( $evento['descricao'] ) );
            $eventos[$e] = $evento;
        }

        // envia os eventos
        return $this->rest->resolve( $eventos );
    }

    // exclui um evento
    public function excluir_evento( $id = false ) {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();

        // verifica se existe um id
        if ( !$id ) return $this->rest->reject( 'Nenhum id informado' );

        // carrega a model
        $this->load->model( 'Eventos_model' );

        // carrega o evento
        $evento = $this->Eventos_model->getById( $id );
        if ( !$evento ) return $this->rest->reject( 'O evento nao existe' );

        // verifica se o usuario é o dono do evento
        if ( $evento['usuario_id'] <> $user['id'] ) return $this->rest->reject( 'Nao é o dono do evento' );
    
        // exclui o evento
        if ( $this->Eventos_model->delete( $evento['id'] ) ) {
            return $this->rest->resolve( 'Evento excluido com sucesso' );
        } else return $this->rest->reject( 'Erro ao excluir o evento' );
    }

    // cadastra um evento
    public function criar_evento() {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();

        // carrega as models
        $this->load->model( [ 'Eventos_model', 'Locais_model' ] );

        // regras de validação
        if ( $this->input->post( 'local' ) && $this->input->post( 'local' ) !== 'outro' ) {
            $this->form_validation->set_rules('local', 'Local','required|trim');
        } else {
            $this->form_validation->set_rules('cidade', 'Cidade','required|trim');
            $this->form_validation->set_rules('endereco', 'Endereço','required|trim');
            $this->form_validation->set_rules('contato', 'Contato','required|trim');
        }
		$this->form_validation->set_rules('categoria', 'Categoria','required|trim');
		$this->form_validation->set_rules('banda', 'Banda','trim');
		$this->form_validation->set_rules('nome', 'Nome','required|trim');
		$this->form_validation->set_rules('desc', 'Descrição','required|trim');
		$this->form_validation->set_rules('data', 'Data','required|trim');
		$this->form_validation->set_rules('hora', 'Hora','required|trim');

        // verifica se o formulário é válido
        if ( $this->form_validation->run() !== false ) {

            // verifica se existe uma foto
            if ( $foto = $this->input->post('foto') ) {
                $data = base64_decode( preg_replace('#^data:image/\w+;base64,#i', '', $foto ) );
                $foto_name = md5( uniqid( time() * rand() ) ).'.jpg';
                file_put_contents('uploads/'.$foto_name, $data);
            } else $foto_name = null;

            // pega os dados
            $dados = [
                'local_id'     => $this->input->post( 'local' ),
                'banda_id'     => null,
                'categoria_id' => $this->input->post( 'categoria' ),
                'descricao'    => $this->input->post( 'desc' ),
                'data'         => $this->input->post( 'data' ),
                'horario'      => $this->input->post( 'hora' ),
                'nome'         => $this->input->post( 'nome' ),
                'usuario_id'   => $user['id'],
                'status'       => 'A',
                'foto'         => $foto_name
            ];
            
            // regras para a data do evento
            // o evento não pode ser marcado numa data que já passou
            // o evento só pode ser marcado até daqui 30 dias
            if( ( strtotime( $dados['data'] ) - time() ) < 0 ) {
                return $this->rest->reject( 'Essa data já passou' );
            } elseif ( ( strtotime( $dados['data'] ) - time()) > 2592000 ) {
                return $this->rest->reject( 'A data limite para marcar o evento é até 30 dias' );
            }

            // regras de validação
            if ( $this->input->post( 'local' ) && $this->input->post( 'local' ) !== 'outro' ) {
                
                // pega o local
                $local              = $this->Locais_model->getById( $this->input->post( 'local' ) );
                $dados['local_id']  = $this->input->post( 'local' );
                $dados['cidade_id'] = $local['cidade_id'];
                $dados['endereco']  = null;
                $dados['contato']   = null;

            } else {
                
                // pega o local
                $dados['local_id']  = null;
                $dados['cidade_id'] = $this->input->post( 'cidade' );
                $dados['endereco']  = $this->input->post( 'endereco' );
                $dados['contato']   = $this->input->post( 'contato' );
            }

            // carrega a model
            if ( $this->Eventos_model->create( $dados ) ) {
                return $this->rest->resolve( 'Evento enviada com sucesso' );
            } else return $this->rest->reject( 'Não foi possivel criar o evento' );
        } else return $this->rest->reject( validation_errors() );
    }

    /*--------------------------------------------------------
    *
    * PAGINAS
    *
    * METODOS REFERENTES A PAGINAS
    *
    *-------------------------------------------------------*/

    // pega as paginas
    public function paginas() {

        // carrega a model
        $this->load->model( 'Paginas_model' );

        // pega as paginas
        $paginas = $this->Paginas_model->getAll();

        // mostra as paginas
        $this->rest->resolve( $paginas );
    }

    /*--------------------------------------------------------
    *
    * COMENTARIOS
    *
    * METODOS REFERENTES A COMENTARIOS
    *
    *-------------------------------------------------------*/

    // pega os comentarios de uma sessao
    public function comentarios( $chave = false, $id = false ) {
        
        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // verifica se a chave esta correta
        $tipos = [ 'lugar_id', 'evento_id', 'banda_id' ];
        if ( !in_array( $chave, $tipos ) ) return $this->rest->reject( 'Chave incorreta' );

        // verifica se existe uma chave e um valor
        if ( !$id ) return $this->rest->reject( 'Nenhuma chave ou valor foi enviada' );

        // prepara a query
        $this->db->from( 'mensagens m')
        ->select( 'm.*, u.nome, u.foto' )
        ->join( 'usuarios u', ' m.usuario_id = u.id' )
        ->where( [ $chave => $id ] );

        // faz a busca
        $query = $this->db->get();

        // verifica se existem comentarios
        $this->rest->resolve( $query->result_array() );
    }

    // adiciona um novo comentario
    public function comentar() {

        // verifica se o mesmo esta logado
        if ( !$this->rest->authorize() ) return false;

        // pega o usuario
        $user = $this->rest->user();

        // pega os dados enviados
        $dados = [
            'mensagem'   => $this->input->post('mensagem'),
            'lugar_id'   => $this->input->post( 'lugar_id' )  ? $this->input->post( 'lugar_id' )  : null,
            'banda_id'   => $this->input->post( 'banda_id' )  ? $this->input->post( 'banda_id' )  : null,
            'evento_id'  => $this->input->post( 'evento_id' ) ? $this->input->post( 'evento_id' ) : null,
            'usuario_id' => $user['id']
        ];

        // verifica se existe uma mensagem
        if ( $dados['mensagem'] && !empty( trim( $dados['mensagem'] ) ) ) {
            
            // retira as tags html
            $dados['mensagem'] = strip_tags( $dados['mensagem'] );

            // cria um novo comentario
            $this->load->model( 'Mensagens_model' );
            if ( $this->Mensagens_model->create( $dados ) ) {
                $this->rest->resolve( 'Comentario enviado com sucesso' );
            } else $this->rest->reject( 'Houve um erro ao tentar enviar o comentário' );
        } else $this->rest->reject( 'Um comentário precisa ser enviado' );
    }
}