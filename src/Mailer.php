<?php
/**
 * Mail.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version XXX
 * @link http://www.sweelix.net
 * @package sweelix\sendgrid
 */

namespace sweelix\sendgrid;


use SendGrid\Exception as SendGridException;
use SendGrid\Email as SendGridMail;
use SendGrid as SendGridClient;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\mail\BaseMailer;

/**
 * This component allow user to send an email
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version XXX
 * @link http://www.sweelix.net
 * @package sweelix\sendgrid
 * @since XXX
 * @todo implement batch messages using API
 */
class Mailer extends BaseMailer
{
    /**
     * @var string Sendgrid API Key
     */
    public $token;

    /**
     * @var string Sendgrid login
     */
    public $user;

    /**
     * @var string Sendgrid password
     */
    public $password;

    /**
     * @var array options as defined in https://github.com/sendgrid/sendgrid-php#usage
     */
    public $options;

    /**
     * @inheritdoc
     */
    public $messageClass = 'sweelix\sendgrid\Message';
    /**
     * @param Message $message
     * @since XXX
     * @throws InvalidConfigException
     */
    public function sendMessage($message)
    {
        try {
            if (($this->token === null) && ($this->user === null && $this->password === null)) {
                throw new InvalidConfigException('Token or login/password are missing');
            }
            $client = null;
            if ($this->token !== null) {
                $client = new SendGridClient($this->token, $this->options);
            } elseif(($this->user !== null) && ($this->password !== null)) {
                $client = new SendGridClient($this->user, $this->password, $this->options);
            }
            if ($client === null) {
                throw new InvalidParamException('Email transport must be configured');
            }
            $sendGridMail = new SendGridMail();
            $replyTo = $message->getReplyTo();
            if ($replyTo !== null) {
                $sendGridMail->setReplyTo($replyTo);
            }
            $sendGridMail->setFrom($message->getFrom());
            if ($message->getFromName() !== null) {
                $sendGridMail->setFromName($message->getFromName());
            }
            foreach($message->getTo() as $email => $name) {
                $sendGridMail->addTo($email, $name);
            }
            foreach($message->getCc() as $email => $name) {
                $sendGridMail->addCc($email, $name);
            }
            foreach($message->getBcc() as $email => $name) {
                $sendGridMail->addBcc($email, $name);
            }
            $sendGridMail->setSubject($message->getSubject());
            foreach($message->getHeaders() as $header) {
                list($key, $value) = each($header);
                $sendGridMail->addHeader($key, $value);
            }
            foreach($message->getAttachments() as $attachment) {
                $cid = isset($attachment['ContentID']) ? $attachment['ContentID'] : null;
                $sendGridMail->addAttachment($attachment['File'], $attachment['Name'], $cid);
            }

            $templateId = $message->getTemplateId();
            if ($templateId === null) {
                $data = $message->getHtmlBody();
                if ($data !== null) {
                    $sendGridMail->setHtml($data);
                }
                $data = $message->getTextBody();
                if ($data !== null) {
                    $sendGridMail->setText($data);
                }
            } else {
                $sendGridMail->setTemplateId($templateId);
                // trigger html template
                $sendGridMail->setHtml(' ');
                // trigger text template
                $sendGridMail->setText(' ');
                $templateModel = $message->getTemplateModel();
                if (empty($templateModel) === false) {
                    $sendGridMail->setSubstitutions($message->getTemplateModel());
                }
            }
            $result = $client->send($sendGridMail);
            /* @var \SendGrid\Response $result */
            return $result->code == 200;
        } catch (SendGridException $e) {
            throw $e;
        }
    }
}