<?php

namespace Webmax\VelmaClient;

use JsonSerializable;
use Webmax\VelmaClient\Model\Contact;
use Webmax\VelmaClient\Exception\BadPacketDefinitionException;

/**
 * Command packet builder
 *
 * @author Frank Bardon Jr. <frankbardon@gmail.com>
 * @todo Fully unit test.
 */
class CommandPacket implements JsonSerializable
{
    const DEFAULT_VERSION = '1.5';
    const TYPE_EMAIL = 'email';

    /**
     * Static constructor
     *
     * @return self
     */
    public static function create(
        $sponsorName,
        $sponsorKey,
        $clientKey,
        $type = null,
        $version = null,
        $jobUniqueId = null
    ) {
        return new self($sponsorName, $sponsorKey, $clientKey, $type, $version, $jobUniqueId);
    }


    private $version;
    private $type;
    private $callbackUri;
    private $jobUniqueId;
    private $sponsorName;
    private $sponsorKey;
    private $clientKey;
    private $emailSubject;
    private $fromName;
    private $fromEmail;
    private $productId;
    private $productTemplate;
    private $contacts = array();
    private $userVariables = array();


    /**
     * Constructor
     *
     * @param string $sponsorName
     * @param string $sponsorKey
     * @param string $clientKey
     * @param string $type
     * @param string $version
     * @param string|integer|null $jobUniqueId
     */
    public function __construct(
        $sponsorName,
        $sponsorKey,
        $clientKey,
        $type = null,
        $version = null,
        $jobUniqueId = null
    ) {
        $this->sponsorName = $sponsorName;
        $this->sponsorKey = $sponsorKey;
        $this->clientKey = $clientKey;
        $this->type = $type ?: self::TYPE_EMAIL;
        $this->version = $version ?: self::DEFAULT_VERSION;
        $this->jobUniqueId = $jobUniqueId ?: sha1(microtime(true));
    }

    /**
     * Get API version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get command packet type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get callback URI
     *
     * @return string|null
     */
    public function getCallbackUri()
    {
        return $this->callbackUri;
    }

    /**
     * Set callback URI
     *
     * @param string $callbackUri
     * @return self
     */
    public function setCallbackUri($callbackUri)
    {
        $this->callbackUri = $callbackUri;

        return $this;
    }

    /**
     * Get job's unique id
     *
     * @return string|integer
     */
    public function getJobUniqueId()
    {
        return $this->jobUniqueId;
    }

    /**
     * Get sponsor name
     *
     * @return string
     */
    public function getSponsorName()
    {
        return $this->sponsorName;
    }

    /**
     * Get sponsor key
     *
     * @return string
     */
    public function getSponsorKey()
    {
        return $this->sponsorKey;
    }

    /**
     * Get client key
     *
     * @return string
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * Get email subject (only for email type packets)
     *
     * @return string
     */
    public function getEmailSubject()
    {
        $this->assertEmailType();

        return $this->emailSubject;
    }

    /**
     * Set email subject (only for email type packets)
     *
     * @param string $emailSubject
     * @return self
     */
    public function setEmailSubject($emailSubject)
    {
        $this->assertEmailType();
        $this->emailSubject = $emailSubject;

        return $this;
    }

    /**
     * Get email from name (only for email type packets)
     *
     * @return string
     */
    public function getFromName()
    {
        $this->assertEmailType();

        return $this->fromName;
    }

    /**
     * Set email from name (only for email type packets)
     *
     * @param string $fromName
     * @return self
     */
    public function setFromName($fromName)
    {
        $this->assertEmailType();
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Get from email (only for email type packets)
     *
     * @return string
     */
    public function getFromEmail()
    {
        $this->assertEmailType();

        return $this->fromEmail;
    }

    /**
     * Set from email (only for email type packets)
     *
     * @param string $fromEmail
     * @return self
     */
    public function setFromEmail($fromEmail)
    {
        $this->assertEmailType();
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * Get product id
     *
     * @return string|integer|null
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set product id
     *
     * @param string|integer $productId
     * @return self
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get product template
     *
     * @return string
     */
    public function getProductTemplate()
    {
        return $this->productTemplate;
    }

    /**
     * Set product template
     *
     * @param string $productTemplate
     * @return self
     */
    public function setProductTemplate($productTemplate)
    {
        $this->productTemplate = $productTemplate;

        return $this;
    }

    /**
     * Get all contacts
     *
     * @return array
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Test if contact is present
     *
     * @param Contact $contact
     * @return boolean
     */
    public function hasContact(Contact $contact)
    {
        return false !== array_search($contact, $this->contacts, true);
    }

    /**
     * Add contact to contacts array
     *
     * @param Contact $contact
     * @return self
     */
    public function addContact(Contact $contact)
    {
        if (!$this->hasContact($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    /**
     * Remove contact from contacts array
     *
     * @param Contact $contact
     * @return self
     */
    public function removeContact(Contact $contact)
    {
        if ($this->hasContact($contact)) {
            $index = array_search($contact, $this->contacts, true);
            unset($this->contacts[$index]);
        }

        return $this;
    }

    /**
     * Contact creation convenience method
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string|null $callbackUrl
     * @param string|integer|null $appUniqueId
     * @return self
     */
    public function createContact($firstName, $lastName, $email, $callbackUrl = null, $appUniqueId = null)
    {
        $contact = Contact::create($firstName, $lastName, $email, $callbackUrl, $appUniqueId);
        $this->addContact($contact);

        return $this;
    }

    /**
     * Get all user variables
     *
     * @return array
     */
    public function getUserVariables()
    {
        return $this->userVariables;
    }

    /**
     * Get a single user variable
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getUserVariable($key, $default = null)
    {
        if ($this->hasUserVariable($key)) {
            return $this->userVariables[$key];
        }

        return $default;
    }

    /**
     * Test for presence of a user variables
     *
     * @param string $key
     * @return boolean
     */
    public function hasUserVariable($key)
    {
        return isset($this->userVariables[$key]);
    }

    /**
     * Add a user variable to the user variables array
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function addUserVariable($key, $value)
    {
        $this->userVariables[$key] = $value;

        return $this;
    }

    /**
     * Remove user variable from the user variables array
     *
     * @param string $key
     * @return self
     */
    public function removeUserVariable($key)
    {
        if ($this->hasUserVariable($key)) {
            unset($this->userVariables[$key]);
        }

        return $this;
    }

    /**
     * Test if this packet is an email command packet
     *
     * @throws BadPacketDefinitionException If packet is not set to email type
     */
    private function assertEmailType()
    {
        if (self::TYPE_EMAIL !== $this->type) {
            throw new BadPacketDefinitionException();
        }
    }


    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $out = array(
            'version' => $this->version,
            'type' => $this->type,
            'jobuniqueid' => $this->jobUniqueId,
            'sponsor' => array(
                'name' => $this->sponsorName,
            ),
            'authentication' => array(
                'sponsorkey' => $this->sponsorKey,
                'clientkey' => $this->clientKey,
            ),
            'user' => $this->userVariables,
            // Product section
            'contact' => $this->contacts
        );

        if ($this->callbackUri) {
            $out['callbackUri'] = $this->callbackUri;
        }

        if (self::TYPE_EMAIL === $this->type) {
            $out['email'] = array(
                'subject' => $this->emailSubject,
                'from' => array(
                    'name' => $this->fromName,
                    'email' => $this->fromEmail,
                ),
            );
        }

        if ($this->productId || $this->productTemplate) {
            $out['product'] = array();
            if ($this->productId) {
                $out['product']['id'] = $this->productId;
            }
            if ($this->productTemplate) {
                $out['product']['template'] = $this->productTemplate;
            }
        }

        return $out;
    }
}
