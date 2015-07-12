<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'models/base_model.php';

class BroadCast extends Base_Model
{
	function __construct()
	{
		parent::__construct();
		// 		$this->load->database();
	}
		/**
	 * @desc This function is used to return the Campaigns And Their Contacts Count back to the caller.
	 * @param $tablename String contains user contacts table name
	 * @param $selectClause Comma separated column name string
	 * @param $whereClause An array of where clause containing the column name as key and column value as key's value
	 * @param $limit A string containing the query limit
	 * @param $offset A string containing the query offset
	 * @return query result (containing array of CampaignsAndContactsCount records).
	 * */
	public function getBroadcasts(&$selectClause = '*', &$whereClause = array(), &$whereInClause = array('column_name' => 'id', 'values' => array()), &$whereNotInClause = array('column_name' => 'id', 'values' => array()), &$orderByClause = array(), $limit = NULL, $offset = NULL, $isPaginationRequiredFlag = FALSE)
	{
		if($isPaginationRequiredFlag === TRUE){
			/**
			 * That means we need to do pagination.
			 * So, we will execute two queries. One to get the result set per page and the other
			 * to get the total result count
			 * */
			$this->readingDBInstance->select("SQL_CALC_FOUND_ROWS $selectClause", FALSE);
		}else{
			$this->readingDBInstance->select($selectClause);
		}
		if($limit > 0 and $offset >= 0){
			$this->readingDBInstance->limit($limit, $offset);
		}

		if(is_array($orderByClause) and count($orderByClause) > 0){
			$orderByClause = implode(', ', $orderByClause);
			$this->readingDBInstance->order_by($orderByClause);
		}
		if(is_array($whereClause) and count($whereClause) > 0){
			$this->readingDBInstance->where($whereClause);
		}
		if(is_array($whereInClause) and array_key_exists('column_name', $whereInClause) and strlen($whereInClause['column_name']) > 0 and is_array($whereInClause['values']) and count($whereInClause['values']) > 0){
			$this->readingDBInstance->where_in($whereInClause['column_name'], $whereInClause['values']);
		}
		if(is_array($whereNotInClause) and array_key_exists('column_name', $whereNotInClause) and strlen($whereNotInClause['column_name']) > 0
				and is_array($whereNotInClause['values']) and count($whereNotInClause['values']) > 0){
			$this->readingDBInstance->where_not_in($whereNotInClause['column_name'], $whereNotInClause['values']);
		}
		$query = $this->readingDBInstance->get('user_campaign_broadcasts');
// 				echoToStdout($this->readingDBInstance->last_query(), TRUE);
// 				die();
		if($isPaginationRequiredFlag === FALSE){
			return $query->result();
		}else{
			$dataToReturn = new stdClass();
			$dataToReturn->broadcasts = $query->result();
			$query = $this->readingDBInstance->query('SELECT FOUND_ROWS() AS `Count`');
			$dataToReturn->totalRecordsCount = $query->row()->Count;
			return $dataToReturn;
		}
	}
	/**
	 * @desc This function is used to update Broadcast into user_broadcasts table
	 * @param $insertClause (Associative Array) containing information about Broadcast
	 * @return Numeric Returns integer as affecting rows
	 */
	public function updateBroadcast(&$dataToUpdate, &$whereClause = array())
	{
		$this->writingDBInstance->where($whereClause);
		$this->writingDBInstance->update("user_campaign_broadcasts", $dataToUpdate);
		return $this->writingDBInstance->affected_rows();
	}
	
