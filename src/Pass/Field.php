<?php

namespace AppleWallet\Passbook\Pass;

class Field
{
    protected string $key;
    protected string $value;
    protected ?string $label = null;
    protected ?string $dateStyle = null;
    protected ?string $timeStyle = null;
    protected ?string $numberStyle = null;
    protected ?string $currencyCode = null;
    protected ?string $attributedValue = null;
    protected ?string $changeMessage = null;
    protected ?string $textAlignment = null;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setDateStyle(?string $dateStyle): self
    {
        $this->dateStyle = $dateStyle;
        return $this;
    }

    public function getDateStyle(): ?string
    {
        return $this->dateStyle;
    }

    public function setTimeStyle(?string $timeStyle): self
    {
        $this->timeStyle = $timeStyle;
        return $this;
    }

    public function getTimeStyle(): ?string
    {
        return $this->timeStyle;
    }

    public function setNumberStyle(?string $numberStyle): self
    {
        $this->numberStyle = $numberStyle;
        return $this;
    }

    public function getNumberStyle(): ?string
    {
        return $this->numberStyle;
    }

    public function setCurrencyCode(?string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setAttributedValue(?string $attributedValue): self
    {
        $this->attributedValue = $attributedValue;
        return $this;
    }

    public function getAttributedValue(): ?string
    {
        return $this->attributedValue;
    }

    public function setChangeMessage(?string $changeMessage): self
    {
        $this->changeMessage = $changeMessage;
        return $this;
    }

    public function getChangeMessage(): ?string
    {
        return $this->changeMessage;
    }

    public function setTextAlignment(?string $textAlignment): self
    {
        $this->textAlignment = $textAlignment;
        return $this;
    }

    public function getTextAlignment(): ?string
    {
        return $this->textAlignment;
    }

    public function toArray(): array
    {
        $data = [
            'key' => $this->key,
            'value' => $this->value,
        ];

        if ($this->label !== null) {
            $data['label'] = $this->label;
        }

        if ($this->dateStyle !== null) {
            $data['dateStyle'] = $this->dateStyle;
        }

        if ($this->timeStyle !== null) {
            $data['timeStyle'] = $this->timeStyle;
        }

        if ($this->numberStyle !== null) {
            $data['numberStyle'] = $this->numberStyle;
        }

        if ($this->currencyCode !== null) {
            $data['currencyCode'] = $this->currencyCode;
        }

        if ($this->attributedValue !== null) {
            $data['attributedValue'] = $this->attributedValue;
        }

        if ($this->changeMessage !== null) {
            $data['changeMessage'] = $this->changeMessage;
        }

        if ($this->textAlignment !== null) {
            $data['textAlignment'] = $this->textAlignment;
        }

        return $data;
    }
} 