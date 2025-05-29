<?php

namespace AppleWallet\Passbook\Pass;

class Barcode
{
    public const TYPE_PDF417 = 'PKBarcodeFormatPDF417';
    public const TYPE_QR = 'PKBarcodeFormatQR';
    public const TYPE_AZTEC = 'PKBarcodeFormatAztec';
    public const TYPE_CODE128 = 'PKBarcodeFormatCode128';

    protected string $format;
    protected string $message;
    protected string $messageEncoding = 'iso-8859-1';
    protected ?string $altText = null;

    public function __construct(string $format, string $message)
    {
        $this->format = $format;
        $this->message = $message;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessageEncoding(string $messageEncoding): self
    {
        $this->messageEncoding = $messageEncoding;
        return $this;
    }

    public function getMessageEncoding(): string
    {
        return $this->messageEncoding;
    }

    public function setAltText(?string $altText): self
    {
        $this->altText = $altText;
        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function toArray(): array
    {
        $data = [
            'format' => $this->format,
            'message' => $this->message,
            'messageEncoding' => $this->messageEncoding,
        ];

        if ($this->altText !== null) {
            $data['altText'] = $this->altText;
        }

        return $data;
    }
} 