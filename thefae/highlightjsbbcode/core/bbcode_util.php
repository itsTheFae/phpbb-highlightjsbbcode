<?php
/**
 *
 * Code modified from Advanced BBCode Box 
 *
 * @copyright (c) 2016 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 * 
 */

namespace thefae\highlightjsbbcode\core;

use phpbb\db\driver\driver_interface;
use phpbb\user;

/**
 * Class bbcode_util
 *
 * @package thefae\highlightjsbbcode\core
 */
class bbcode_util
{
	/** @var \acp_bbcodes */
	protected $acp_bbcodes;
    
    /** @var \thefae\highlightjsbbcode\core\utils */
    protected $ext_utils;

	/**
	 * Constructor
	 *
	 * @param driver_interface $db
	 * @access public
	 */
	public function __construct(driver_interface $db)
	{
		$this->ext_utils        = new \thefae\highlightjsbbcode\core\utils();
		$this->acp_bbcodes      = $this->get_acp_bbcodes();
        $this->db               = $db;
	}

	/**
	 * Installs bbcodes, used by migrations to perform add/updates
	 *
	 * @param array $bbcodes Array of bbcodes to install
	 * @access public
	 */
	public function install_bbcodes(array $bbcodes)
	{
		foreach ($bbcodes as $bbcode_name => $bbcode_data)
		{
			$bbcode_data = $this->build_bbcode($bbcode_data);

			if ($bbcode = $this->bbcode_exists($bbcode_name, $bbcode_data['bbcode_tag']))
			{
				$this->update_bbcode($bbcode, $bbcode_data);
				continue;
			}

			$this->add_bbcode($bbcode_data);
		}
	}

	/**
	 * Get the acp_bbcodes class
	 *
	 * @return \acp_bbcodes
	 * @access public
	 */
	public function get_acp_bbcodes()
	{
		if (!class_exists('acp_bbcodes')) {
            $root_path = $this->ext_utils->phpbb_root_path;
            $ext = $this->ext_utils->php_ext;
			include $root_path . 'includes/acp/acp_bbcodes.' . $ext;
		}
		return new \acp_bbcodes();
	}

	/**
	 * Build the bbcode
	 *
	 * @param array $bbcode_data Initial bbcode data
	 * @return array Complete bbcode data array
	 * @access public
	 */
	public function build_bbcode(array $bbcode_data)
	{
		$data = $this->acp_bbcodes->build_regexp($bbcode_data['bbcode_match'], $bbcode_data['bbcode_tpl']);

		$bbcode_data = array_replace($bbcode_data, array(
			'bbcode_tag'          => $data['bbcode_tag'],
			'first_pass_match'    => $data['first_pass_match'],
			'first_pass_replace'  => $data['first_pass_replace'],
			'second_pass_match'   => $data['second_pass_match'],
			'second_pass_replace' => $data['second_pass_replace'],
		));

		return $bbcode_data;
	}

	/**
	 * Get the max bbcode id value
	 *
	 * @return int bbcode identifier
	 * @access public
	 */
	public function get_max_bbcode_id()
	{
		$sql = 'SELECT MAX(bbcode_id) AS maximum
			FROM ' . BBCODES_TABLE;
		$result = $this->db->sql_query($sql);
		$maximum = $this->db->sql_fetchfield('maximum');
		$this->db->sql_freeresult($result);

		return (int) $maximum;
	}

	/**
	 * Check if bbcode exists
	 *
	 * @param string $bbcode_name Name of bbcode
	 * @param string $bbcode_tag  Tag name of bbcode
	 * @return mixed Existing bbcode data array or false if not found
	 * @access public
	 */
	public function bbcode_exists($bbcode_name, $bbcode_tag)
	{
		$sql = 'SELECT bbcode_id
			FROM ' . BBCODES_TABLE . "
			WHERE LOWER(bbcode_tag) = '" . $this->db->sql_escape(strtolower($bbcode_name)) . "'
			OR LOWER(bbcode_tag) = '" . $this->db->sql_escape(strtolower($bbcode_tag)) . "'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row;
	}

	/**
	 * Update existing bbcode
	 *
	 * @param array $old_bbcode Existing bbcode data
	 * @param array $new_bbcode New bbcode data
	 * @access public
	 */
	public function update_bbcode(array $old_bbcode, array $new_bbcode)
	{
		$sql = 'UPDATE ' . BBCODES_TABLE . '
			SET ' . $this->db->sql_build_array('UPDATE', $new_bbcode) . '
			WHERE bbcode_id = ' . (int) $old_bbcode['bbcode_id'];
		$this->db->sql_query($sql);
	}

	/**
	 * Add new bbcode
	 *
	 * @param array $bbcode_data New bbcode data
	 * @access public
	 */
	public function add_bbcode(array $bbcode_data)
	{
		$bbcode_id = $this->get_max_bbcode_id() + 1;

		if ($bbcode_id <= NUM_CORE_BBCODES)
		{
			$bbcode_id = NUM_CORE_BBCODES + 1;
		}

		if ($bbcode_id <= BBCODE_LIMIT)
		{
			$bbcode_data['bbcode_id'] = (int) $bbcode_id;
			// set display_on_posting to 1 by default, so if 0 is desired, set it in our data array
			$bbcode_data['display_on_posting'] = (int) !array_key_exists('display_on_posting', $bbcode_data);

			$this->db->sql_query('INSERT INTO ' . BBCODES_TABLE . ' ' . $this->db->sql_build_array('INSERT', $bbcode_data));
		}
	}
}
