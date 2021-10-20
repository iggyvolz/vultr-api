<?php

namespace Iggyvolz\Vultr\Account;

use Iggyvolz\Vultr\Vultr;

class AccountInfo
{
    public readonly string $name;
    public readonly string $email;
    /**
     * @var list<string>
     */
    public readonly array $acls;
    public readonly int|float $balance;
    public readonly int|float $pendingCharges;
    public readonly \DateTime $lastPaymentDate;
    public readonly int|float $lastPaymentAmount;
    public function __construct(array $data)
    {
        $data = Vultr::assertArray($data["account"] ?? null);
        $this->name = Vultr::assertString($data["name"] ?? null);
        $this->email = Vultr::assertString($data["email"] ?? null);
        $this->acls = Vultr::assertStringList($data["acls"] ?? null);
        $this->balance = Vultr::assertNumber($data["balance"] ?? null);
        $this->pendingCharges = Vultr::assertNumber($data["pending_charges"] ?? null);
        $this->lastPaymentDate = Vultr::assertDate($data["last_payment_date"] ?? null);
        $this->lastPaymentAmount = Vultr::assertNumber($data["last_payment_amount"] ?? null);
    }
}