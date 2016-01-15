<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Dependency\Facade;

use Spryker\Zed\Messenger\Business\MessengerFacade;
use Generated\Shared\Transfer\FlashMessagesTransfer;

class KernelToMessengerBridge implements KernelToMessengerInterface
{

    /**
     * @var MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @param MessengerFacade $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return FlashMessagesTransfer
     */
    public function getStoredMessages()
    {
        return $this->messengerFacade->getStoredMessages();
    }

}