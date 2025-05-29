<?php

namespace AppleWallet\Passbook\Image;

class Image
{
    public const TYPE_ICON = 'icon';
    public const TYPE_ICON_RETINA = 'icon@2x';
    public const TYPE_LOGO = 'logo';
    public const TYPE_LOGO_RETINA = 'logo@2x';
    public const TYPE_STRIP = 'strip';
    public const TYPE_STRIP_RETINA = 'strip@2x';
    public const TYPE_THUMBNAIL = 'thumbnail';
    public const TYPE_THUMBNAIL_RETINA = 'thumbnail@2x';
    public const TYPE_FOOTER = 'footer';
    public const TYPE_FOOTER_RETINA = 'footer@2x';

    protected string $path;
    protected string $type;
    protected string $density = '1x';

    public function __construct(string $path, string $type)
    {
        $this->path = $path;
        $this->type = $type;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setDensity(string $density): self
    {
        $this->density = $density;
        return $this;
    }

    public function getDensity(): string
    {
        return $this->density;
    }

    public function getFilename(): string
    {
        return $this->type . ($this->density === '2x' ? '@2x' : '') . '.png';
    }
} 