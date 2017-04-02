<?php

namespace app\entities\Employee;

use Assert\Assertion;
use app\repositories\InstantiateTrait;
use yii\db\ActiveRecord;

class Phone extends ActiveRecord
{
    use InstantiateTrait;

    private $country;
    private $code;
    private $number;

    public function __construct(int $country, string $code, string $number)
    {
        Assertion::notEmpty($country);
        Assertion::notEmpty($code);
        Assertion::notEmpty($number);

        $this->country = $country;
        $this->code = $code;
        $this->number = $number;
        parent::__construct();
    }

    public function isEqualTo(self $phone): bool
    {
        return $this->getFull() === $phone->getFull();
    }

    public function getFull(): string
    {
        return '+' . $this->country . ' (' . $this->code . ') ' . $this->number;
    }

    public function getCountry(): int { return $this->country; }
    public function getCode(): string { return $this->code; }
    public function getNumber(): string { return $this->number; }

    ######## INFRASTRUCTURE #########

    public static function tableName(): string
    {
        return '{{%ar_employee_phones}}';
    }

    public function afterFind(): void
    {
        $this->country = $this->getAttribute('phone_country');
        $this->code = $this->getAttribute('phone_code');
        $this->number = $this->getAttribute('phone_number');

        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('phone_country', $this->country);
        $this->setAttribute('phone_code', $this->code);
        $this->setAttribute('phone_number', $this->number);

        return parent::beforeSave($insert);
    }
}
