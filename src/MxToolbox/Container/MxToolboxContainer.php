<?php
/**
 * DI Container
 *
 * container of services for MxToolbox
 *
 * @author Lubomir Spacek
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/heximcz/mxtoolbox
 * @link https://best-hosting.cz
 */

namespace MxToolbox\Container;

use MxToolbox\DataGrid\MxToolboxDataGrid;
use MxToolbox\FileSystem\BlacklistsHostnameFile;
use MxToolbox\NetworkTools\NetworkTools;
use MxToolbox\NetworkTools\SmtpServerChecks;

/**
 * Class MxToolboxContainer
 * @package MxToolbox\Container
 */
class MxToolboxContainer
{

    /** @var array Container of any services */
    private $container = array();

    /** 
     * Create service NetworkTool
     */
    protected function createServiceNetworkTool()
    {
        if (!isset($this->container['netTool']) || !$this->container['netTool'] instanceof NetworkTools)
            $this->container['netTool'] = new NetworkTools();
        return $this->container['netTool'];
    }

    /**
     * Create service BlacklistsHostnameFile
     */
    protected function createServiceBlacklistsHostnameFile()
    {
        if (!isset($this->container['fileSys']) || !$this->container['fileSys'] instanceof BlacklistsHostnameFile)
            $this->container['fileSys'] = new BlacklistsHostnameFile();
        return $this->container['fileSys'];
    }

    /**
     * Create service MxToolboxDataGrid
     */
    protected function createServiceMxToolboxDataGrid()
    {
        if (!isset($this->container['dataGrid']) || !$this->container['dataGrid'] instanceof MxToolboxDataGrid)
            $this->container['dataGrid'] = new MxToolboxDataGrid(
                $this->createServiceBlacklistsHostnameFile(),
                $this->createServiceNetworkTool()
            );
        return $this->container['dataGrid'];
    }

    /**
     * @param string $addr
     * @param string $myHostName
     * @param string $mailFrom
     * @param string $mailRcptTo
     * @return SmtpServerChecks
     */
    protected function createServiceSmtpServerChecks($addr, $myHostName, $mailFrom, $mailRcptTo)
    {
        if (!isset($this->container['smtpDiagnostics']) || !$this->container['smtpDiagnostics'] instanceof SmtpServerChecks)
            $this->container['smtpDiagnostics'] = new SmtpServerChecks(
                $this->createServiceNetworkTool(),
                $addr,
                $myHostName,
                $mailFrom,
                $mailRcptTo
            );
        return $this->container['smtpDiagnostics'];
    }

}