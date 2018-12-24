<?php

namespace Myowncode\TurboSmsBundle\Service;

/**
 * Class SoapTurboSmsStub
 *
 * @method object SendSMS(array $data)
 * @method object GetMessageStatus(array $data)
 * @method object Auth(array $data)
 * @method object GetCreditBalance()
 *
 * @package \Myowncode\TurboSmsBundle\Service
 */
final class SoapTurboSmsStub extends \SoapClient
{

}