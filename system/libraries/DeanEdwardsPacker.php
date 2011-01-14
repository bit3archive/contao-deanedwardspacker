<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    DeanEdwardsPacker
 * @license    LGPL
 * @filesource
 */

/**
 * Class DeanEdwardsPacker
 *
 * wrapper class for the Dean Edwards Packer (http://dean.edwards.name/packer/)
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Library
 */
class DeanEdwardsPacker
{
	/**
	 * Contains all JS code
	 * @var string
	 */
	protected $strJs;
	
	/**
	 * load the cssmin class
	 */
	public function  __construct()
	{
		include_once TL_ROOT . '/plugins/DeanEdwardsPacker/class.JavaScriptPacker.php';
	}

	/**
	 * Load JS code from a string
	 * 
	 * @param string $strCssContent
	 */
	public function loadJsFromString($strJsContent)
	{
		$this->strJs .= $strJsContent;
	}

	/**
	 * Load JS code from a file or from an array witch contains filenames.
	 * The path must relativ from the contao root directory (ex: tl_files/js/myscript.js)
	 * 
	 * @param mixed $strFile
	 */
	public function loadCssFromFile($strFile)
	{
		// if we get an array of files, we load everyone
		if (is_array($strFile))
		{
			foreach ($strFile as $v)
			{
				if (file_exists(TL_ROOT . '/' . $v))
				{
					$objFile = new File($v);
					$this->strJs .= $objFile->getContent();
					$objFile->close();
				}
			}

			// end the script because we only have an array
			return;
		}

		// load the single file
		if (file_exists(TL_ROOT . '/' . $strFile))
		{
			$objFile = new File($strFile);
			$this->strJs .= $objFile->getContent();
			$objFile->close();
		}
	}

	/**
	 * Minimize the JS code and returm minimized code.
	 * 
	 * @return string
	 */
	public function minimize()
	{
		$objPacker = new JavaScriptPacker($this->strJs);
		return $objPacker->pack();
	}

	/**
	 * Minimize the JS code and write them to a file (ex: tl_files/js/myscript_min.js)
	 * 
	 * @param string $strFilename
	 */
	public function minimizeToFile($strFilename)
	{
		$objFile = new File($strFilename);
		$objFile->write($this->minimize());
		$objFile->close();
	}

}
?>