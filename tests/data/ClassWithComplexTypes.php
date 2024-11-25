<?php

namespace WebFu\Reflection\Tests\data;

class ClassWithComplexTypes
{
    private \DateTime $dateTime;
    private BasicEnum $basicEnum;
    private BackedEnum $backedEnum;

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getBasicEnum(): BasicEnum
    {
        return $this->basicEnum;
    }

    public function setBasicEnum(BasicEnum $basicEnum): void
    {
        $this->basicEnum = $basicEnum;
    }

    public function getBackedEnum(): BackedEnum
    {
        return $this->backedEnum;
    }

    public function setBackedEnum(BackedEnum $backedEnum): void
    {
        $this->backedEnum = $backedEnum;
    }
}