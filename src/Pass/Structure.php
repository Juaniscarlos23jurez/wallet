<?php

namespace AppleWallet\Passbook\Pass;

class Structure
{
    protected array $primaryFields = [];
    protected array $secondaryFields = [];
    protected array $auxiliaryFields = [];
    protected array $backFields = [];
    protected array $headerFields = [];

    public function addPrimaryField(Field $field): self
    {
        $this->primaryFields[] = $field;
        return $this;
    }

    public function addSecondaryField(Field $field): self
    {
        $this->secondaryFields[] = $field;
        return $this;
    }

    public function addAuxiliaryField(Field $field): self
    {
        $this->auxiliaryFields[] = $field;
        return $this;
    }

    public function addBackField(Field $field): self
    {
        $this->backFields[] = $field;
        return $this;
    }

    public function addHeaderField(Field $field): self
    {
        $this->headerFields[] = $field;
        return $this;
    }

    public function getPrimaryFields(): array
    {
        return $this->primaryFields;
    }

    public function getSecondaryFields(): array
    {
        return $this->secondaryFields;
    }

    public function getAuxiliaryFields(): array
    {
        return $this->auxiliaryFields;
    }

    public function getBackFields(): array
    {
        return $this->backFields;
    }

    public function getHeaderFields(): array
    {
        return $this->headerFields;
    }

    public function toArray(): array
    {
        $data = [];

        if (!empty($this->primaryFields)) {
            $data['primaryFields'] = array_map(fn(Field $field) => $field->toArray(), $this->primaryFields);
        }

        if (!empty($this->secondaryFields)) {
            $data['secondaryFields'] = array_map(fn(Field $field) => $field->toArray(), $this->secondaryFields);
        }

        if (!empty($this->auxiliaryFields)) {
            $data['auxiliaryFields'] = array_map(fn(Field $field) => $field->toArray(), $this->auxiliaryFields);
        }

        if (!empty($this->backFields)) {
            $data['backFields'] = array_map(fn(Field $field) => $field->toArray(), $this->backFields);
        }

        if (!empty($this->headerFields)) {
            $data['headerFields'] = array_map(fn(Field $field) => $field->toArray(), $this->headerFields);
        }

        return $data;
    }
} 