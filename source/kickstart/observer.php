<?php

/**
 * Akeeba Kickstart
 * A JSON-powered archive extraction tool
 *
 * @copyright   2008-2017 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license     GNU GPL v2 or - at your option - any later version
 * @package     akeebabackup
 * @subpackage  kickstart
 */
class ExtractionObserver extends AKAbstractPartObserver
{
	public $compressedTotal = 0;
	public $uncompressedTotal = 0;
	public $filesProcessed = 0;
	public $totalSize = null;
	public $fileList = null;
	public $lastFile = '';

	public function update($object, $message)
	{
		if (!is_object($message))
		{
			return;
		}

		if (!array_key_exists('type', get_object_vars($message)))
		{
			return;
		}

		switch ($message->type)
		{
			// Sent when we read the list of archive parts and their total size
			case 'totalsize':
				$this->totalSize = $message->content->totalsize;
				$this->fileList  = $message->content->filelist;
				break;

			// Sent when a file header is read from the archive
			case 'startfile':
				$this->lastFile = $message->content->file;
				$this->filesProcessed++;
				$this->compressedTotal += $message->content->compressed;
				$this->uncompressedTotal += $message->content->uncompressed;
				break;
		}

	}

	public function __toString()
	{
		return __CLASS__;
	}

}