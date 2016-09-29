<?php

namespace Webmax\VelmaClient\Model;

use JsonSerializable;

/**
 * Velma contact model
 *
 * @author Frank Bardon Jr. <frankbardon@gmail.com>
 * @todo Fully unit test.
 */
class Contact implements JsonSerializable
{
    /**
     * Create a new contact
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string|null $callbackUrl
     * @param string|null $appUniqueId
     * @return self
     */
    static public function create($firstName, $lastName, $email, $callbackUrl = null, $appUniqueId = null)
    {
        $contact = new self($appUniqueId);
        $contact->setFirstName($firstName);
        $contact->setLastName($lastName);
        $contact->setEmail($email);

        if ($callbackUrl) {
            $contact->setCallbackUrl($callbackUrl);
        }

        return $contact;
    }

    private $appUniqueId;
    private $callbackUrl;
    private $firstName;
    private $lastName;
    private $name;
    private $email;

    /**
     * Constructor
     *
     * Auto creates a new unique application id if none has been provided.
     *
     * @param string|integer|null $appUniqueId
     */
    public function __construct($appUniqueId = null)
    {
        $this->appUniqueId = $appUniqueId ?: sha1(microtime(true));
    }

    /**
     * Get unique application id for contact
     *
     * @return string
     */
    public function getAppUniqueId()
    {
        return $this->appUniqueId;
    }

    /**
     * Get callback URL
     *
     * @return string|null
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * Set callback URL
     *
     * @param string $callbackUrl
     * @return self
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set first name
     *
     * @param string $firstName
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set last name
     *
     * @param string $lastName
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get name
     *
     * If no explicit name has been provided, this creates one from the first
     * and last name.
     *
     * @return string
     */
    public function getName()
    {
        if ($this->name) {
            return $this->name;
        }

        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $out = array(
            'appuniqueid' => $this->appUniqueId,
            'First Name' => $this->firstName,
            'Last Name' => $this->lastName,
            'name' => $this->getName(),
            'email' => $this->email,
        );

        if ($this->callbackUrl) {
            $out['callbackUrl'] = $this->callbackUrl;
        }

        return $out;
    }
}
