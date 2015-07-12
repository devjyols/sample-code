<?php
class Base_Model extends CI_Model
{
	protected $readingDBInstance;// This will hold the instance of the reading db server for select queries only
	protected $writingDBInstance;// This will hold the instance of the writing db server for insert/update/delete,alter queries only
	protected $tempTablesDBInstance;// This instance will be able to create temporary tables in the slave only
	protected $possibleJoinTypes;
	function __construct()
	{
		parent::__construct();
		$this->readingDBInstance = $this->load->database('reportingdb', TRUE);
		$this->writingDBInstance = $this->load->database('default', TRUE);
		$this->tempTablesDBInstance = $this->load->database('temporary_tables_slave_db', TRUE);
		$this->possibleJoinTypes = array('inner', 'left', 'right', 'outer', 'left outer', 'right outer');
	}
}
