<?php

namespace AppleWallet\Passbook\Pass;

use AppleWallet\Passbook\Exception\PassException;

abstract class Pass
{
    protected string $serialNumber;
    protected string $description;
    protected string $organizationName = '';
    protected string $passTypeIdentifier = '';
    protected string $teamIdentifier = '';
    protected string $backgroundColor = '';
    protected string $foregroundColor = '';
    protected string $labelColor = '';
    protected string $logoText = '';
    protected Structure $structure;
    protected array $images = [];
    protected ?Barcode $barcode = null;
    protected array $locations = [];
    protected array $beacons = [];
    protected array $webService = [];

    public function __construct(string $serialNumber, string $description)
    {
        $this->serialNumber = $serialNumber;
        $this->description = $description;
        $this->structure = new Structure();
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setOrganizationName(string $organizationName): self
    {
        $this->organizationName = $organizationName;
        return $this;
    }

    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }

    public function setPassTypeIdentifier(string $passTypeIdentifier): self
    {
        $this->passTypeIdentifier = $passTypeIdentifier;
        return $this;
    }

    public function getPassTypeIdentifier(): string
    {
        return $this->passTypeIdentifier;
    }

    public function setTeamIdentifier(string $teamIdentifier): self
    {
        $this->teamIdentifier = $teamIdentifier;
        return $this;
    }

    public function getTeamIdentifier(): string
    {
        return $this->teamIdentifier;
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function setForegroundColor(string $foregroundColor): self
    {
        $this->foregroundColor = $foregroundColor;
        return $this;
    }

    public function getForegroundColor(): string
    {
        return $this->foregroundColor;
    }

    public function setLabelColor(string $labelColor): self
    {
        $this->labelColor = $labelColor;
        return $this;
    }

    public function getLabelColor(): string
    {
        return $this->labelColor;
    }

    public function setLogoText(string $logoText): self
    {
        $this->logoText = $logoText;
        return $this;
    }

    public function getLogoText(): string
    {
        return $this->logoText;
    }

    public function setStructure(Structure $structure): self
    {
        $this->structure = $structure;
        return $this;
    }

    public function getStructure(): Structure
    {
        return $this->structure;
    }

    public function addImage(Image $image): self
    {
        $this->images[] = $image;
        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setBarcode(Barcode $barcode): self
    {
        $this->barcode = $barcode;
        return $this;
    }

    public function getBarcode(): ?Barcode
    {
        return $this->barcode;
    }

    abstract public function getType(): string;

    public function toArray(): array
    {
        $data = [
            'formatVersion' => 1,
            'passTypeIdentifier' => $this->passTypeIdentifier,
            'serialNumber' => $this->serialNumber,
            'teamIdentifier' => $this->teamIdentifier,
            'organizationName' => $this->organizationName,
            'description' => $this->description,
            'style' => $this->getType(),
        ];

        if (!empty($this->backgroundColor)) {
            $data['backgroundColor'] = $this->backgroundColor;
        }

        if (!empty($this->foregroundColor)) {
            $data['foregroundColor'] = $this->foregroundColor;
        }

        if (!empty($this->labelColor)) {
            $data['labelColor'] = $this->labelColor;
        }

        if (!empty($this->logoText)) {
            $data['logoText'] = $this->logoText;
        }

        if ($this->barcode) {
            $data['barcode'] = $this->barcode->toArray();
        }

        $data = array_merge($data, $this->structure->toArray());

        return $data;
    }
} 