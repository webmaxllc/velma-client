<?php

use Webmax\VelmaClient\Model\Contact;

class ContactTests extends ClientTestCase
{
    const FIRST_NAME = 'First';
    const LAST_NAME = 'Last';
    const EMAIL = 'first.last@example.org';
    const CALLBACK_URL = 'http://example.org';
    const UNIQUE_ID = 'id';

    public function testCreatesUniqueIdByDefault()
    {
        $contact = $this->createContact();

        $this->assertRegExp("|[0-9a-f]{5,40}|", $contact->getAppUniqueId());
    }

    public function testAcceptsUniqueId()
    {
        $contact = $this->createContact(true);

        $this->assertSame(self::UNIQUE_ID, $contact->getAppUniqueId());
    }

    public function testAcceptsCallbackUrl()
    {
        $contact = $this->createContact(true);

        $this->assertSame(self::CALLBACK_URL, $contact->getCallbackUrl());
    }

    public function testFirstNameGetterAndSetter()
    {
        $contact = $this->createEmptyContact();

        $this->assertSame($contact, $contact->setFirstName(self::FIRST_NAME));
        $this->assertSame(self::FIRST_NAME, $contact->getFirstName());
    }

    public function testLastNameGetterAndSetter()
    {
        $contact = $this->createEmptyContact();

        $this->assertSame($contact, $contact->setLastName(self::LAST_NAME));
        $this->assertSame(self::LAST_NAME, $contact->getLastName());
    }

    public function testNameGetterAndSetter()
    {
        $name = 'Full Name';
        $contact = $this->createEmptyContact();

        $this->assertSame($contact, $contact->setName($name));
        $this->assertSame($name, $contact->getName());
    }

    public function testNameReturnsConcatenatedFirstAndLastNameWhenEmpty()
    {
        $expected = self::FIRST_NAME . ' ' . self::LAST_NAME;
        $contact = $this->createEmptyContact()
            ->setFirstName(self::FIRST_NAME)
            ->setLastName(self::LAST_NAME);

        $this->assertSame($expected, $contact->getName());
    }

    public function testEmailGetterAndSetter()
    {
        $contact = $this->createEmptyContact();

        $this->assertSame($contact, $contact->setEmail(self::EMAIL));
        $this->assertSame(self::EMAIL, $contact->getEmail());
    }

    public function testJsonArrayContainsRequiredKeys()
    {
        $contact = $this->createContact();
        $array = $contact->jsonSerialize();

        $this->assertArrayHasKey('appuniqueid', $array);
        $this->assertArrayHasKey('First Name', $array);
        $this->assertArrayHasKey('Last Name', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('email', $array);
    }

    public function testJsonArrayContainsOptionalKeysWhenSet()
    {
        $contact = $this->createContact(true);
        $array = $contact->jsonSerialize();

        $this->assertArrayHasKey('callbackUrl', $array);
    }

    protected function createEmptyContact()
    {
        return new Contact();
    }

    protected function createContact($fill = false)
    {
        $contact = Contact::create(
            self::FIRST_NAME,
            self::LAST_NAME,
            self::EMAIL,
            $fill ? self::CALLBACK_URL : null,
            $fill ? self::UNIQUE_ID : null
        );

        return $contact;
    }
}
