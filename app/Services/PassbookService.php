<?php

namespace App\Services;

use AppleWallet\Passbook\PassFactory;
use AppleWallet\Passbook\Type\EventTicket;
use AppleWallet\Passbook\Pass\Field;
use AppleWallet\Passbook\Pass\Structure;
use AppleWallet\Passbook\Pass\Barcode;
use AppleWallet\Passbook\Image\Image;

class PassbookService
{
    protected PassFactory $factory;

    public function __construct()
    {
        $this->factory = new PassFactory(
            config('passbook.pass.type_identifier'),
            config('passbook.pass.team_identifier'),
            config('passbook.pass.organization_name'),
            $this->getAbsolutePath(config('passbook.certificates.p12.path')),
            config('passbook.certificates.p12.password'),
            $this->getAbsolutePath(config('passbook.certificates.wwdr.path'))
        );

        $this->factory->setOutputPath($this->getAbsolutePath(config('passbook.output.path')));
    }

    protected function getAbsolutePath(string $relativePath): string
    {
        return base_path($relativePath);
    }

    public function createEventTicket(
        string $serialNumber,
        string $description,
        string $eventName,
        string $location,
        string $dateTime,
        string $iconPath,
        string $barcodeMessage
    ): string {
        // Create pass
        $pass = new EventTicket($serialNumber, $description);
        $pass->setBackgroundColor('rgb(60, 65, 76)');
        $pass->setLogoText(config('passbook.pass.organization_name'));

        // Create structure
        $structure = new Structure();

        // Add primary field
        $primary = new Field('event', $eventName);
        $primary->setLabel('Event');
        $structure->addPrimaryField($primary);

        // Add secondary field
        $secondary = new Field('location', $location);
        $secondary->setLabel('Location');
        $structure->addSecondaryField($secondary);

        // Add auxiliary field
        $auxiliary = new Field('datetime', $dateTime);
        $auxiliary->setLabel('Date & Time');
        $structure->addAuxiliaryField($auxiliary);

        // Add icon image
        $icon = new Image($this->getAbsolutePath($iconPath), Image::TYPE_ICON);
        $pass->addImage($icon);

        // Set pass structure
        $pass->setStructure($structure);

        // Add barcode
        $barcode = new Barcode(Barcode::TYPE_QR, $barcodeMessage);
        $pass->setBarcode($barcode);

        // Package the pass
        return $this->factory->package($pass);
    }
} 