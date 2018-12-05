<?php

namespace MundipaggModuleBackend\Hub\Commands;

use MundipaggModuleBackend\Core\Kernel\GatewayId\AccountId;
use MundipaggModuleBackend\Core\Interfaces\CommandInterface;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\HubAccessTokenKey;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\PublicKey;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\TestPublicKey;
use MundipaggModuleBackend\Core\Kernel\GatewayId\GUID;
use MundipaggModuleBackend\Core\Kernel\GatewayId\MerchantId;

abstract class AbstractCommand implements CommandInterface
{
    /** @var HubAccessTokenKey */
    protected $accessToken;
    /** @var AccountId */
    protected $accountId;
    /** @var PublicKey|TestPublicKey */
    protected $accountPublicKey;
    /** @var GUID */
    protected $installId;
    /** @var MerchantId */
    protected $merchantId;
    /** @var CommandType */
    protected $type;

    /**
     * @return HubAccessTokenKey
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param HubAccessTokenKey $accessToken
     * @return AbstractCommand
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return AccountId
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param AccountId $accountId
     * @return AbstractCommand
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return PublicKey|TestPublicKey
     */
    public function getAccountPublicKey()
    {
        return $this->accountPublicKey;
    }

    /**
     * @param PublicKey|TestPublicKey $accountPublicKey
     * @return AbstractCommand
     */
    public function setAccountPublicKey($accountPublicKey)
    {
        $this->accountPublicKey = $accountPublicKey;
        return $this;
    }

    /**
     * @return GUID
     */
    public function getInstallId()
    {
        return $this->installId;
    }

    /**
     * @param GUID $installId
     * @return AbstractCommand
     */
    public function setInstallId($installId)
    {
        $this->installId = $installId;
        return $this;
    }

    /**
     * @return MerchantId
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param MerchantId $merchantId
     * @return AbstractCommand
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
        return $this;
    }

    /**
     * @return CommandType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param CommandType $type
     * @return AbstractCommand
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}