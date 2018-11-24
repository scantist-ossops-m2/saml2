<?php

namespace SAML2\XML\saml;

use SAML2\Constants;
use SAML2\Utils;

/**
 * Class representing SAML 2 Attribute.
 *
 * @package SimpleSAMLphp
 */
class Attribute
{
    /**
     * The Name of this attribute.
     *
     * @var string
     */
    public $Name;

    /**
     * The NameFormat of this attribute.
     *
     * @var string|null
     */
    public $NameFormat;

    /**
     * The FriendlyName of this attribute.
     *
     * @var string|null
     */
    public $FriendlyName = null;

    /**
     * List of attribute values.
     *
     * Array of \SAML2\XML\saml\AttributeValue elements.
     *
     * @var \SAML2\XML\saml\AttributeValue[]
     */
    public $AttributeValue = [];

    /**
     * Initialize an Attribute.
     *
     * @param \DOMElement|null $xml The XML element we should load.
     * @throws \Exception
     */
    public function __construct(\DOMElement $xml = null)
    {
        if ($xml === null) {
            return;
        }

        if (!$xml->hasAttribute('Name')) {
            throw new \Exception('Missing Name on Attribute.');
        }
        $this->setName($xml->getAttribute('Name'));

        if ($xml->hasAttribute('NameFormat')) {
            $this->setNameFormat($xml->getAttribute('NameFormat'));
        }

        if ($xml->hasAttribute('FriendlyName')) {
            $this->setFriendlyName($xml->getAttribute('FriendlyName'));
        }

        foreach (Utils::xpQuery($xml, './saml_assertion:AttributeValue') as $av) {
            $this->addAttributeValue(new AttributeValue($av));
        }
    }

    /**
     * Collect the value of the Name-property
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * Set the value of the Name-property
     * @param string $name
     */
    public function setName($name)
    {
        assert(is_string($name));
        $this->Name = $name;
    }

    /**
     * Collect the value of the NameFormat-property
     * @return string|null
     */
    public function getNameFormat()
    {
        return $this->NameFormat;
    }

    /**
     * Set the value of the NameFormat-property
     * @param string|null $NameFormat
     */
    public function setNameFormat($nameFormat = null)
    {
        assert(is_string($nameFormat) || is_null($nameFormat));
        $this->NameFormat = $nameFormat;
    }

    /**
     * Collect the value of the FriendlyName-property
     * @return string|null
     */
    public function getFriendlyName()
    {
        return $this->FriendlyName;
    }

    /**
     * Set the value of the FriendlyName-property
     * @param string|null $friendlyName
     */
    public function setFriendlyName($friendlyName = null)
    {
        assert(is_string($friendlyName) || is_null($friendlyName));
        $this->FriendlyName = $friendlyName;
    }

    /**
     * Collect the value of the AttributeValue-property
     * @return SAML2\XML\saml\AttributeValue[]
     */
    public function getAttributeValue()
    {
        return $this->AttributeValue;
    }

    /**
     * Set the value of the AttributeValue-property
     * @param array $attributeValue
     */
    public function setAttributeValue(array $attributeValue)
    {
        $this->AttributeValue = $attributeValue;
    }

    /**
     * Add the value to the AttributeValue-property
     * @param \SAML2\XML\saml\AttributeValue $attributeValue
     */
    public function addAttributeValue(AttributeValue $attributeValue)
    {
        $this->AttributeValue[] = $attributeValue;
    }

    /**
     * Internal implementation of toXML.
     * This function allows RequestedAttribute to specify the element name and namespace.
     *
     * @param \DOMElement $parent    The element we should append this Attribute to.
     * @param string     $namespace The namespace the element should be created in.
     * @param string     $name      The name of the element.
     * @return \DOMElement
     */

    protected function toXMLInternal(\DOMElement $parent, $namespace, $name)
    {
        assert(is_string($namespace));
        assert(is_string($name));
        assert(is_string($this->getName()));
        assert(is_null($this->getNameFormat()) || is_string($this->getNameFormat()));
        assert(is_null($this->getFriendlyName()) || is_string($this->getFriendlyName()));
        assert(is_array($this->getAttributeValue()));

        $e = $parent->ownerDocument->createElementNS($namespace, $name);
        $parent->appendChild($e);

        $e->setAttribute('Name', $this->getName());

        if ($this->getNameFormat() !== null) {
            $e->setAttribute('NameFormat', $this->NameFormat);
        }

        if ($this->FriendlyName !== null) {
            $e->setAttribute('FriendlyName', $this->getFriendlyName());
        }

        foreach ($this->getAttributeValue() as $av) {
            $av->toXML($e);
        }

        return $e;
    }

    /**
     * Convert this Attribute to XML.
     *
     * @param \DOMElement $parent The element we should append this Attribute to.
     * @return \DOMElement
     */
    public function toXML(\DOMElement $parent)
    {
        return $this->toXMLInternal($parent, Constants::NS_SAML, 'saml:Attribute');
    }
}
