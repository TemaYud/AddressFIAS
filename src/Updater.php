<?php
namespace AddressFIAS;

use AddressFIAS\Updater\VersionsManager\VersionsManagerBase;
use AddressFIAS\Updater\VersionsManager\VersionsManagerCurl;
use AddressFIAS\Updater\VersionStorage\VersionStorageBase;
use AddressFIAS\Updater\VersionStorage\VersionStorageFile;
use AddressFIAS\Updater\Downloader\DownloaderBase;
use AddressFIAS\Updater\Downloader\DownloaderCurl;
use AddressFIAS\Archive\Zip;

use AddressFIAS\Exception\UpdaterException;

class Updater {

	protected $versionsManager;
	protected $versionStorage;
	protected $downloader;
	protected $archiveHandler;

	protected $processFileDir;



	protected $_db_table_prefix = 'fias_';
	protected $_db_update_table_prefix = 'update_';

	protected $replaceDataChunkSize = 100000;

	public function __construct(){
	}

	public function setVersionsManager(VersionsManagerBase $versionsManager){
		$this->versionsManager = $versionsManager;
	}

	public function getVersionsManager(){
		if (!$this->versionsManager){
			$this->setVersionsManager(new VersionsManagerCurl());
		}
		return $this->versionsManager;
	}

	public function setVersionStorage(VersionStorageBase $versionStorage){
		$this->versionStorage = $versionStorage;
	}

	public function getVersionStorage(){
		if (!$this->versionStorage){
			$this->setVersionStorage(new VersionStorageFile());
		}
		return $this->versionStorage;
	}

	public function setDownloader(DownloaderBase $downloader){
		$this->downloader = $downloader;
	}

	public function getDownloader(){
		if (!$this->downloader){
			$this->setDownloader(new DownloaderCurl());
		}
		return $this->downloader;
	}

	public function setArchiveHandler(\AddressFIAS\Updater\Archive\IArchive $archiveHandler){
		$this->archiveHandler = $archiveHandler;
	}

	public function getArchiveHandler(){
		if (!$this->archiveHandler){
			$this->setArchiveHandler(new Zip());
		}
		return $this->archiveHandler;
	}

	public function setProcessFileDir($processFileDir){
		$this->processFileDir = $processFileDir;
	}

	public function getProcessFileDir(){
		if (!$this->processFileDir){
			$this->setProcessFileDir(sys_get_temp_dir());
		}
		return $this->processFileDir;
	}

	public function upgradeDelta(){
		$versionId = $this->getVersionStorage()->getCurrentVersionId();

		$files = $this->getVersionsManager()->getDelta($versionId);
		if (!$files){
			return false;
		}

		foreach ($files as $farr){
			if (!$this->processGarFileDelta($farr)){
				break;
			}

			$this->getVersionStorage()->setCurrentVersionId($farr['VersionId'], $farr);
		}
	}

	public function upgradeFull(){
		$farr = $this->getVersionsManager()->getLast();
		if (!$farr){
			return false;
		}

		if (!$this->processGarFileFull($farr)){
			return false;
		}

		$this->getVersionStorage()->setCurrentVersionId($farr['VersionId'], $farr);
	}

	protected function downloadFile($fileURL, array $farr){
		$filepath = $this->getProcessFileDir() . DIRECTORY_SEPARATOR . 'addressfias_' . $farr['VersionId'] . '.' . pathinfo($fileURL)['extension'];
		if (!is_file($filepath)) #
		$this->getDownloader()->download($fileURL, $filepath);

		return $filepath;
	}

	public function processGarFileDelta(array $farr){
		$filepath = $this->downloadFile($farr['GarXMLDeltaURL'], $farr);
		$filepath = $this->downloadFile($farr['GarXMLDeltaURL'], $farr);

		var_dump($farr, $filepath);
	}

	public function processGarFileFull(array $farr){
		$xmlFileURL = $farr['GarXMLFullURL'];

		$basename = strftime('address_fias_updater');
		$filename = $basename . '.zip';
		$filepath = $this->getProcessFileDir() . DIRECTORY_SEPARATOR . $filename;

		$archHandler = $this->getArchiveHandler();
		$arch = new $archHandler(DOC_ROOT . $this->processFileDir . $filename);
		if (false === $arch){
			throw new UpdaterException('Open archive error');
		}

		$entries = $arch->getEntries();
		if (false === $entries){
			throw new UpdaterException('Get archive entries error');
		}

		/*if (!is_dir(DOC_ROOT . $this->processFileDir . $basename)){
			mkdir(DOC_ROOT . $this->processFileDir . $basename, 0755, true);
		}

		$files2tbls = [
			'AS_SOCRBASE_' => [
				'table' => 'SOCRBASE',
				'rows_identified' => 'AddressObjectType',
				'callback' => [$this, 'replaceAddressesSOCRBASE']
			],
			'AS_ADDROBJ_' => [
				'table' => 'ADDROBJ',
				'rows_identified' => 'Object',
				'callback' => [$this, 'replaceAddressesADDROBJ']
			],
			'AS_HOUSE_' => [
				'table' => 'HOUSE',
				'rows_identified' => 'House',
				'callback' => [$this, 'replaceAddressesHOUSE']
			],
		];

		foreach ($entries as $entry){
			//$entryFilename = $entry->getName();
			$entryFilename = $entry['name'];

			foreach ($files2tbls as $fp => $tarr){
				if (0 === strncmp($fp, $entryFilename, strlen($fp))){
					//if (!is_file(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename)) #
					//if (!$entry->extract(DOC_ROOT . $this->processFileDir . $basename . DS)){
					if (!$arch->extractEntriy($entry['name'], DOC_ROOT . $this->processFileDir . $basename . DS)){
						trigger_error('Extract file error. Archive: \'' . DOC_ROOT . $this->processFileDir . $filename . '\'. File: \'' . DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename . '\'.');
						continue 2;
					}

					if (!\Page::$DB->ping()){
						\Page::$DB->reconnect();
					}

					if (!($r = $this->createUpdateTable($tarr['table']))){
						trigger_error('Failed to create update database table');
						unlink(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename);
						continue 2;
					}

					if (!$this->truncateUpdateTable($tarr['table'])){
						trigger_error('Failed to truncate update database table');
						unlink(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename);
						continue 2;
					}

					#if (!$this->fillUpdateTable($tarr['table'])){
					#	trigger_error('Failed to fill update database table');
					#	unlink(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename);
					#	continue 2;
					#}

					$this->loadFromXML(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename, $this->_db_update_table_prefix . $tarr['table'], $tarr['rows_identified']);

					if (!\Page::$DB->ping()){
						\Page::$DB->reconnect();
					}

					if (!$this->replaceUpdatedData($tarr['table'])){
						trigger_error('Failed to replace updated data');
					}

					if (!empty($tarr['callback'])){
						call_user_func($tarr['callback'], $tarr['table']);
					}

					$this->dropUpdateTable($tarr['table']);

					unlink(DOC_ROOT . $this->processFileDir . $basename . DS . $entryFilename);
				}
			}
		}*/

		$arch->close();

		rmdir(DOC_ROOT . $this->processFileDir . $basename . DS);
		unlink(DOC_ROOT . $this->processFileDir . $filename);

		return true;
	}

	public function createUpdateTable($tbl){
		$sql = "CREATE TABLE IF NOT EXISTS `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "` LIKE `" . $this->_db_table_prefix . $tbl . "`;";
		return \Page::$DB->exec($sql);
	}

	public function dropUpdateTable($tbl){
		$sql = "DROP TABLE IF EXISTS `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`;";
		return \Page::$DB->exec($sql);
	}

	public function truncateUpdateTable($tbl){
		$sql = "TRUNCATE TABLE `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`;";
		return \Page::$DB->exec($sql);
	}

	public function fillUpdateTable($tbl){
		$sql = "INSERT INTO `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "` SELECT * FROM `" . $this->_db_table_prefix . $tbl . "`;";
		return \Page::$DB->exec($sql);
	}

	public function replaceUpdatedData($tbl){
		$result = true;

		$cnt = $this->countUpdatedData($tbl);
		$parts = 500000;
		for ($i = 0, $l = ceil($cnt / $parts); $i < $l; $i++){
			$sql = "REPLACE INTO `" . $this->_db_table_prefix . $tbl . "`
				SELECT *
				FROM `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`
				LIMIT " . ($i * $parts) . ", " . $parts . ";";
			if (!\Page::$DB->exec($sql)){
				$result = false;
			}
		}

		return $result;

		//$sql = "REPLACE INTO `" . $this->_db_table_prefix . $tbl . "` SELECT * FROM `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`;";
		//return \Page::$DB->exec($sql);
	}

	public function countUpdatedData($tbl, array $filter = [], $checkEndDate = false){
		$sql_w = [];
		if ($checkEndDate){
			$sql_w[] = "`ENDDATE` >= NOW()";
		}
		foreach ($filter as $f => $v){
			$sql_w[] = "`" .  $f . "` = '" . \Page::$DB->escape($v) . "'";
		}

		$sql = "SELECT COUNT(*)
		FROM `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`
		" . ($sql_w ? "WHERE " . implode(" AND ", $sql_w) : "") . "
		;";
		$res = \Page::$DB->query($sql);
		return $res->get_one();
	}

	public function loadFromXML($file, $tbl, $rows_id){
		$parser = new \libs\address\addrobj\update\xmlparser($file);
		$parser->parse($rows_id, function($attributes) use($tbl){
			$values = [];
			foreach ($attributes as $f => $v){
				$values[] = "`" .  $f . "` = '" . \Page::$DB->escape($v) . "'";
			}

			$sql = "REPLACE INTO `" . $this->_db_table_prefix . $tbl . "` SET " . implode(", ", $values) . ";";
			if (!\Page::$DB->exec($sql)){
				trigger_error('Replace into \'' . $this->_db_table_prefix . $tbl . '\' error: ' . \Page::$DB->error() . ' (values: ' . print_r($values, true) . ').');
			}
		});

		/*$sql = "LOAD XML LOCAL INFILE '" . \Page::$DB->escape($file) . "' REPLACE INTO TABLE `" . $this->_db_table_prefix . $tbl . "` ROWS IDENTIFIED BY '<" . \Page::$DB->escape($rows_id) . ">';";
		return \Page::$DB->exec($sql);*/
	}

	public function replaceAddressesSOCRBASE($tbl){
		$address = \libs\address\address::getInstance();
		$socrbase = \libs\address\socrbase::getInstance();

		$sql = "SELECT * FROM `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`;";
		$res = \Page::$DB->query($sql);
		while ($arr = $res->fetch_assoc()){
			if ($socrbase_id = $socrbase->getSocrbaseIDByKodTSt($arr['KOD_T_ST'])){
				continue;
			}

			$socrbase_id = $socrbase->addSocrbase([
				'kod_t_st' => $arr['KOD_T_ST'],
				'level' => $arr['LEVEL'],
				'scname' => htmlspecialchars($arr['SCNAME']),
				'socrname' => htmlspecialchars($arr['SOCRNAME']),
			]);
		}
	}

	public function replaceAddressesADDROBJ($tbl){
		$address = \libs\address\address::getInstance();

		$cnt = $this->countUpdatedData($tbl, [
			'ACTSTATUS' => '1',
		], true);

		for ($i = 0, $l = ceil($cnt / $this->replaceDataChunkSize); $i < $l; $i++){
			$sql = "SELECT * FROM `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`
			WHERE `ACTSTATUS` = 1
			LIMIT " . ($i * $this->replaceDataChunkSize) . ", " . $this->replaceDataChunkSize . ";";
			$res = \Page::$DB->query($sql);
			while ($arr = $res->fetch_assoc()){
				$address_parent = 0;
				if (!$arr['PARENTGUID']){
					$address_parent = 0;
				} elseif (false == ($address_parent = $address->getAddressIDByGUID($arr['PARENTGUID']))){
					$address_parent = null;

					trigger_error('Can\'t find address by PARENTGUID = \'' . $arr['PARENTGUID'] . '\'.');
				}

				if (false != ($address_id = $address->getAddressIDByGUID($arr['AOGUID']))){
					if (!is_null($address_parent) && $address_parent != $address->getAddressParent($address_id)){
						$address->moveAddress($address_id, $address_parent);
					}
				}

				$data = [
					'address_aoguid' => $arr['AOGUID'],
					'address_parentguid' => $arr['PARENTGUID'],
					'address_name' => htmlspecialchars($arr['FORMALNAME']),
					'address_shortname' => htmlspecialchars($arr['SHORTNAME']),
					'address_aolevel' => $arr['AOLEVEL'],
					'address_regioncode' => $arr['REGIONCODE'],
					'address_areacode' => $arr['AREACODE'],
					'address_autocode' => $arr['AUTOCODE'],
					'address_citycode' => $arr['CITYCODE'],
					'address_ctarcode' => $arr['CTARCODE'],
					'address_placecode' => $arr['PLACECODE'],
					'address_streetcode' => $arr['STREETCODE'],
					'address_extrcode' => $arr['EXTRCODE'],
					'address_sextcode' => $arr['SEXTCODE'],
					'address_plaincode' => $arr['PLAINCODE'],
					'address_code' => $arr['CODE'],
					'address_currstatus' => $arr['CURRSTATUS'],
					'address_actstatus' => $arr['ACTSTATUS'],
					'address_livestatus' => $arr['LIVESTATUS'],
					'address_centstatus' => $arr['CENTSTATUS'],
					'address_operstatus' => $arr['OPERSTATUS'],
					'address_ifnsfl' => $arr['IFNSFL'],
					'address_terrifnsfl' => $arr['TERRIFNSFL'],
					'address_ifnsul' => $arr['IFNSUL'],
					'address_terrifnsul' => $arr['TERRIFNSUL'],
					'address_okato' => $arr['OKATO'],
					'address_oktmo' => $arr['OKTMO'],
					'address_postalcode' => $arr['POSTALCODE'],
				];

				if ($address_id){
					$addr_old = $address->getAddress($address_id);

					$address->updAddress($address_id, $data);

					if ($addr_old['address_shortname'] != $data['address_shortname'] || $addr_old['address_aolevel'] != $data['address_aolevel']){
						$address->setAddressSocrBase($address_id);
						$address->setAddressFull($address_id);
					} elseif ($addr_old['address_name'] != $data['address_name']){
						$address->setAddressFull($address_id);
					}
				} else {
					$address_id = $address->addAddress($data, $address_parent);

					$address->setAddressSocrBase($address_id);
					$address->setAddressFull($address_id);

					$address->setAddressChildrensCount($address_id);
					$address->setAddressChildrensCount($address_parent);
				}
			}
		}

		if ($un_addrs = $address->getUndefinedParentsAddresses()){
			foreach ($un_addrs as $arr){
				$address_parent = 0;
				if (false == ($address_parent = $address->getAddressIDByGUID($arr['address_parentguid']))){
					continue;
				}

				$address->moveAddress($arr['address_id'], $address_parent);
			}
		}
	}

	public function replaceAddressesHOUSE($tbl){
		$address = \libs\address\address::getInstance();

		$cnt = $this->countUpdatedData($tbl, [], true);

		for ($i = 0, $l = ceil($cnt / $this->replaceDataChunkSize); $i < $l; $i++){
			$sql = "SELECT * FROM `" . $this->_db_table_prefix . $this->_db_update_table_prefix . $tbl . "`
			LIMIT " . ($i * $this->replaceDataChunkSize) . ", " . $this->replaceDataChunkSize . ";";
			//$sql = "SELECT * FROM `" . $this->_db_table_prefix . $tbl . "`
			//LIMIT " . ($i * $this->replaceDataChunkSize) . ", " . $this->replaceDataChunkSize . ";";
			$res = \Page::$DB->query($sql);
			while ($arr = $res->fetch_assoc()){
				$address_id = 0;
				if (!$arr['AOGUID']){
					$address_id = 0;
				} elseif (false == ($address_id = $address->getAddressIDByGUID($arr['AOGUID']))){
					$address_id = null;

					trigger_error('Can\'t find address by AOGUID = \'' . $arr['AOGUID'] . '\'.');
				}

				$data = [
					'house_houseid' => $arr['HOUSEID'],
					'house_houseguid' => $arr['HOUSEGUID'],
					'address_aoguid' => $arr['AOGUID'],
					'house_housenum' => htmlspecialchars($arr['HOUSENUM']),
					'house_strstatus' => $arr['STRSTATUS'],
					'house_eststatus' => $arr['ESTSTATUS'],
					'house_statstatus' => $arr['STATSTATUS'],
					'house_ifnsfl' => $arr['IFNSFL'],
					'house_ifnsul' => $arr['IFNSUL'],
					'house_terrifnsfl' => $arr['TERRIFNSFL'],
					'house_terrifnsul' => $arr['TERRIFNSUL'],
					'house_okato' => $arr['OKATO'],
					'house_oktmo' => $arr['OKTMO'],
					'house_postalcode' => $arr['POSTALCODE'],
					'house_startdate' => $arr['STARTDATE'],
					'house_enddate' => $arr['ENDDATE'],
					'house_updatedate' => $arr['UPDATEDATE'],
					'house_counter' => $arr['COUNTER'],
					'house_normdoc' => $arr['NORMDOC'],
					'house_buildnum' => $arr['BUILDNUM'],
					'house_strucnum' => $arr['STRUCNUM'],
					'house_divtype' => $arr['DIVTYPE'],
					'house_regioncode' => $arr['REGIONCODE'],
				];

				if (false != ($house_id = $address->getHouseIDByGUID($arr['HOUSEGUID']))){
					$data['address_id'] = $address_id;

					$address->updHouse($house_id, $data);
				} else {
					$house_id = $address->addHouse($address_id, $data);
				}
			}
		}
	}

}
?>
