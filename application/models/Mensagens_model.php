<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mensagens_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Mensagens';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'mensagens';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'mensagem', 
            'label'  => 'Mensagem'       
        ], [
            'column' => 'lugar_id', 
            'label'  => 'Lugar'       
        ], [
            'column' => 'evento_id', 
            'label'  => 'Evento'       
        ], [
            'column' => 'banda_id', 
            'label'  => 'Banda'       
        ], [
            'column' => 'usuario_id', 
            'label'  => 'Usuario'       
        ]
    ];

    /**
	* _relations
	*
	* @protected 
	*/
	protected $_relations = [
            [
                'field' => 'lugar_id',
                'table' => 'locais',
                'use'   => 'nome'
            ],[
                'field' => 'evento_id',
                'table' => 'eventos',
                'use'   => 'nome'
            ],[
                'field' => 'banda_id',
                'table' => 'bandas',
                'use'   => 'banda'
            ],[
                'field' => 'usuario_id',
                'table' => 'usuarios',
                'use'   => 'email'
            ]
    ];
    
   /**
	* __construct
	* 
	* constructor method
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function __construct() {
        parent::__construct();
    }
}
