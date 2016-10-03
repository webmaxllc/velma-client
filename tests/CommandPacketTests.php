<?php

use Mockery as M;
use Webmax\VelmaClient\CommandPacket;

class CommandPacketTests extends ClientTestCase
{
    public function testSetup()
    {
        $cp = $this->createCommandPacket();

        $this->assertInstanceOf('Webmax\VelmaClient\CommandPacket', $cp);
    }

    public function testDefaultVersionMatch()
    {
        $cp = $this->createCommandPacket();

        $this->assertSame(CommandPacket::DEFAULT_VERSION, $cp->getVersion());
    }

    public function testDefaultTypeMatch()
    {
        $cp = $this->createCommandPacket();

        $this->assertSame(CommandPacket::TYPE_EMAIL, $cp->getType());
    }

    public function testUniqueJobIdIsCreatedOnConstruction()
    {
        $cp = $this->createCommandPacket();

        $this->assertNotNull($cp->getJobUniqueId());
        $this->assertRegExp('|[0-9a-f]{5,40}|', $cp->getJobUniqueId());
    }

    public function testSponsorNameGetter()
    {
        $cp = $this->createCommandPacket();

        $this->assertSame(self::SPONSOR, $cp->getSponsorName());
    }

    public function testSponsorKeyGetter()
    {
        $cp = $this->createCommandPacket();

        $this->assertSame(self::SPONSOR_KEY, $cp->getSponsorKey());
    }

    public function testClientKeyGetter()
    {
        $cp = $this->createCommandPacket();

        $this->assertSame(self::CLIENT_KEY, $cp->getClientKey());
    }

    public function testCallbackUriGetterAndSetter()
    {
        $callbackUri = 'http://example.org';
        $cp = $this->createCommandPacket();

        $this->assertSame($cp, $cp->setCallbackUri($callbackUri));
        $this->assertSame($callbackUri, $cp->getCallbackUri());
    }

    public function testEmailSubjectGetterAndSetter()
    {
        $subject = 'subject';
        $cp = $this->createCommandPacket();

        $this->assertSame($cp, $cp->setEmailSubject($subject));
        $this->assertSame($subject, $cp->getEmailSubject());
    }

    public function testFromNameGetterAndSetter()
    {
        $name = 'Name';
        $cp = $this->createCommandPacket();

        $this->assertSame($cp, $cp->setFromName($name));
        $this->assertSame($name, $cp->getFromName());
    }

    public function testFromEmailGetterAndSetter()
    {
        $email = 'test@test.com';
        $cp = $this->createCommandPacket();

        $this->assertSame($cp, $cp->setFromEmail($email));
        $this->assertSame($email, $cp->getFromEmail());
    }

    public function testProductIdGetterAndSetter()
    {
        $id = 'id';
        $cp = $this->createCommandPacket();

        $this->assertSame($cp, $cp->setProductId($id));
        $this->assertSame($id, $cp->getProductId());
    }

    public function testProductTemplateGetterAndSetter()
    {
        $template = 'file://template/path.txt';
        $cp = $this->createCommandPacket();

        $this->assertSame($cp, $cp->setProductTemplate($template));
        $this->assertSame($template, $cp->getProductTemplate());
    }

    public function testContactsReturnsEmptyInitially()
    {
        $cp = $this->createCommandPacket();

        $this->assertEmpty($cp->getContacts());
    }

    public function testContactCanBeAdded()
    {
        $cp = $this->createCommandPacket();
        $contact = $this->createContactMock();

        $this->assertSame($cp, $cp->addContact($contact));
        $this->assertCount(1, $cp->getContacts());
    }

    public function testContactCannotBeAddedTwice()
    {
        $cp = $this->createCommandPacket();
        $contact = $this->createContactMock();

        $cp->addContact($contact);
        $cp->addContact($contact);

        $this->assertCount(1, $cp->getContacts());
    }

    public function testContactCanBeFoundOnceAdded()
    {
        $cp = $this->createCommandPacket();
        $contact = $this->createContactMock();

        $cp->addContact($contact);

        $this->assertTrue($cp->hasContact($contact));
    }

    public function testContactCanBeRemoved()
    {
        $cp = $this->createCommandPacket();
        $contact = $this->createContactMock();

        $cp->addContact($contact);

        $this->assertSame($cp, $cp->removeContact($contact));
        $this->assertEmpty($cp->getContacts());
    }

    public function testContactCanBeCreated()
    {
        $cp = $this->createCommandPacket();
        $cp->createContact('First', 'Last', 'Email');

        $contact = $cp->getContacts()[0];

        $this->assertInstanceOf('Webmax\VelmaClient\Model\Contact', $contact);
        $this->assertTrue($cp->hasContact($contact));
    }

    public function testUserVarsAreEmptyInitially()
    {
        $cp = $this->createCommandPacket();

        $this->assertEmpty($cp->getUserVariables());
    }

    public function testUserVarCanBeAdded()
    {
        $key = 'key';
        $val = 'value';

        $cp = $this->createCommandPacket();

        $this->assertSame($cp, $cp->addUserVariable($key, $val));
        $this->assertCount(1, $cp->getUserVariables());
    }

    public function testUserVarCanBeFoundOnceCreated()
    {
        $key = 'key';
        $val = 'value';

        $cp = $this->createCommandPacket();
        $cp->addUserVariable($key, $val);

        $this->assertTrue($cp->hasUserVariable($key));
        $this->assertSame($val, $cp->getUserVariable($key));
    }

    public function testUserVarReturnsDefaultValueWhenNotFound()
    {
        $default = 'test';
        $cp = $this->createCommandPacket();

        $this->assertSame($default, $cp->getUserVariable('null', $default));
    }

    public function testUserVarCanBeRemoved()
    {
        $key = 'key';
        $val = 'value';

        $cp = $this->createCommandPacket();
        $cp->addUserVariable($key, $val);

        $this->assertSame($cp, $cp->removeUserVariable($key));
        $this->assertEmpty($cp->getUserVariables());
    }

    /**
     * @expectedException Webmax\VelmaClient\Exception\BadPacketDefinitionException
     */
    public function testNonEmailPacketThrowsOnEmailField()
    {
        $cp = $this->createCommandPacket(false, 'null');

        $cp->setEmailSubject('subject');
    }

    public function testJsonArrayContainsVersion()
    {
        $cp = $this->createCommandPacket();

        $this->assertArrayHasKey('version', $cp->jsonSerialize());
        $this->assertSame(CommandPacket::DEFAULT_VERSION, $cp->jsonSerialize()['version']);
    }

    public function testJsonArrayContainsType()
    {
        $cp = $this->createCommandPacket();

        $this->assertArrayHasKey('type', $cp->jsonSerialize());
        $this->assertSame(CommandPacket::TYPE_EMAIL, $cp->jsonSerialize()['type']);
    }

    public function testJsonArrayContainsUniqueJobId()
    {
        $cp = $this->createCommandPacket();

        $this->assertArrayHasKey('jobuniqueid', $cp->jsonSerialize());
    }

    public function testJsonArrayContainsSponsorName()
    {
        $cp = $this->createCommandPacket();

        $this->assertArrayHasKey('sponsor', $cp->jsonSerialize());

        $sponsor = $cp->jsonSerialize()['sponsor'];

        $this->assertSame(self::SPONSOR, $sponsor['name']);
    }

    public function testJsonArrayContainsSponsorKey()
    {
        $cp = $this->createCommandPacket();

        $this->assertArrayHasKey('authentication', $cp->jsonSerialize());

        $authentication = $cp->jsonSerialize()['authentication'];

        $this->assertSame(self::SPONSOR_KEY, $authentication['sponsorkey']);
    }

    public function testJsonArrayContainsClientKey()
    {
        $cp = $this->createCommandPacket();

        $this->assertArrayHasKey('authentication', $cp->jsonSerialize());

        $authentication = $cp->jsonSerialize()['authentication'];

        $this->assertSame(self::CLIENT_KEY, $authentication['clientkey']);
    }

    public function testJsonArrayContainsCallbackUriWhenPresent()
    {
        $uri = 'http://example.org';
        $cp = $this->createCommandPacket();

        $this->assertArrayNotHasKey('callbackUri', $cp->jsonSerialize());
        $this->assertArrayHasKey('callbackUri', $cp->setCallbackUri($uri)->jsonSerialize());
    }

    public function testJsonArrayContainsProductIdWhenPresent()
    {
        $id = 'id';
        $cp = $this->createCommandPacket();

        $this->assertArrayNotHasKey('product', $cp->jsonSerialize());
        $this->assertArrayHasKey('product', $cp->setProductId($id)->jsonSerialize());

        $product = $cp->jsonSerialize()['product'];

        $this->assertArrayHasKey('id', $product);
    }

    public function testJsonArrayContainsProductTemplateWhenPresent()
    {
        $template = 'template';
        $cp = $this->createCommandPacket();

        $this->assertArrayNotHasKey('product', $cp->jsonSerialize());
        $this->assertArrayHasKey('product', $cp->setProductTemplate($template)->jsonSerialize());

        $product = $cp->jsonSerialize()['product'];

        $this->assertArrayHasKey('template', $product);
    }

    protected function createContactMock()
    {
        return M::mock('Webmax\VelmaClient\Model\Contact');
    }

    protected function createCommandPacket(
        $fill = false,
        $type = null,
        $version = null,
        $jobUniqueId = null
    ) {
        $cp = CommandPacket::create(
            self::SPONSOR,
            self::SPONSOR_KEY,
            self::CLIENT_KEY,
            $type,
            $version,
            $jobUniqueId
        );

        if ($fill) {
        }

        return $cp;
    }
}
