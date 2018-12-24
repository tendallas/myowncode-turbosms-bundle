<?php

namespace Myowncode\TurboSmsBundle\Service;

use Myowncode\TurboSmsBundle\Entity\TurboSmsSent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TurboSms
 *
 * @package \App\Service
 */
class TurboSms
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * Debug mode
     *
     * @var bool
     */
    public $debug = false;

    /**
     * @var SoapTurboSmsStub
     */
    private $client;
    /**
     * Wsdl url
     *
     * @var string
     */
    private $wsdl;
    /**
     * @var string
     */
    private $lastSendMessageId = '';
    /**
     * @var array
     */
    private $lastSendMessagesIds = [];
    /**
     * @var bool
     */
    private $sendStatus;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * Turbosms constructor.
     *
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, ContainerInterface $container)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->container = $container;
        $this->wsdl = $this->container->getParameter('myowncode_turbosms_config.wsdl');
        $this->debug = $this->container->getParameter('myowncode_turbosms_config.debug');
        $this->repository = $em->getRepository(TurboSmsSent::class);
    }
    /**
     * Send sms and return array of message's ids in database
     *
     * @param string $text
     * @param $phones
     *
     * @return array
     *
     * @throws \Exception
     */
    public function send(string $text, $phones): array
    {
        if (!is_array($phones)) {
            $phones = [$phones];
        }
        foreach ($phones as $phone) {
            if (!$phone) {
                continue;
            }
            $message = $this->sendMessage($text, $phone);
            $this->saveToDb($text, $phone, $message);
        }
        return $this->lastSendMessagesIds;
    }

    /**
     * Connect to Turbosms by Soap
     *
     * @return SoapTurboSmsStub
     * @throws \Exception
     */
    private function connect(): SoapTurboSmsStub
    {
        if ($this->client) {
            return $this->client;
        }
        $login = $this->container->getParameter('myowncode.turbosms.login');
        $password = $this->container->getParameter('myowncode.turbosms.password');
        $client = new SoapTurboSmsStub($this->wsdl);
        if (!$login || !$password) {
            throw new \Exception($this->trans('Введите имя пользователя и пароль от Turbosms.'));
        }
        $result = $client->Auth([
            'login' => $login,
            'password' => $password,
        ]);
        if ((string)$result->AuthResult != 'Вы успешно авторизировались') {
            throw new \Exception($this->trans((string)$result->AuthResult));
        }
        $this->client = $client;
        return $this->client;
    }
    /**
     * Save sms to db
     *
     * @param string $text
     * @param string $phone
     * @param string $message
     *
     * @return bool
     */
    public function saveToDb($text, $phone, $message): bool
    {
        if (!$this->container->getParameter('myowncode_turbosms_config.save_to_db')) {
            return false;
        }
        $model = new TurboSmsSent();
        $model->setStatusMessage($message  . ' ' . ($this->debug ? $this->trans('(тестовый режим)') : ''));
        $model->setMessage($text);
        $model->setPhone($phone);
        $model->setCreatedAt(new \DateTime());
        $model->setUpdatedAt(new \DateTime());
        if ($this->lastSendMessageId) {
            $model->setMessageId($this->lastSendMessageId);
        }
        $model->setStatus($this->sendStatus);
        $this->em->persist($model);
        $this->em->flush();
        if ((int)$model->getId()) {
            $this->lastSendMessagesIds[$model->getId()] = $this->lastSendMessageId;
        }
        return true;
    }
    /**
     * Get balance
     *
     * @return int
     */
    public function getBalance(): int
    {
        return $this->debug ? 0 : (int)$this->getClient()->GetCreditBalance()->GetCreditBalanceResult;
    }
    /**
     * Get message status
     *
     * @param $messageId
     *
     * @return string
     */
    public function getMessageStatus($messageId): string
    {
        if ($this->debug || !$messageId) {
            return '';
        }
        return $this->getClient()->GetMessageStatus(['MessageId' => $messageId])->GetMessageStatusResult;
    }

    /**
     * Get Soap client
     *
     * @return SoapTurboSmsStub
     */
    private function getClient(): SoapTurboSmsStub
    {
        if (!$this->client) {
            return $this->connect();
        }
        return $this->client;
    }
    /**
     * @param $text
     * @param $phone
     * @return string
     */
    private function sendMessage($text, $phone): string
    {
        $message = $this->trans('Сообщения успешно отправлено.');
        $this->sendStatus = true;
        $this->lastSendMessageId = '';
        if ($this->debug) {
            return $message;
        }
        $result = $this->getClient()->SendSMS([
            'sender' => $this->container->getParameter('myowncode.turbosms.sender'),
            'destination' => $phone,
            'text' => $text
        ]);
        if (is_array($result->SendSMSResult->ResultArray) && !empty($result->SendSMSResult->ResultArray[1])) {
            $this->lastSendMessageId = $result->SendSMSResult->ResultArray[1];
        }
        if (empty($result->SendSMSResult->ResultArray[0]) ||
            $result->SendSMSResult->ResultArray[0] != 'Сообщения успешно отправлены'
        ) {
            $this->sendStatus = false;
            // @todo delete preg_replace
            $message = preg_replace('/%error%/i', $result->SendSMSResult->ResultArray,
                $this->trans('Сообщения не отправлено (ошибка: "%error%").'));
        }
        return $message;
    }
    /**
     * @param string $message
     * @param array  $params
     *
     * @return string
     */
    private function trans($message, array $params = array()): string
    {
        return $this->translator->trans($message, $params, 'messages');
    }
}