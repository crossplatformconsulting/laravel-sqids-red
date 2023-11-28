<?php

declare(strict_types=1);

namespace RedExplosion\Sqids\Tests;

use RedExplosion\Sqids\Sqids;
use Workbench\App\Models\Customer;
use Workbench\Database\Factories\ChargeFactory;
use Workbench\Database\Factories\CustomerFactory;

it('can generate a sqid for a model', function (): void {
    $customer = CustomerFactory::new()->create();

    expect(Sqids::forModel(model: $customer))
        ->toBeString()
        ->toStartWith('cst_');
});

it('can get the sqid prefix for a model', function (): void {
    $customer = CustomerFactory::new()->create();
    $charge = ChargeFactory::new()->create();

    expect($customer->getSqidPrefix())
        ->toBeNull()
        ->and(Sqids::prefixForModel(model: $customer::class))
        ->toBe('cst')
        ->and($charge->getSqidPrefix())
        ->toBe('ch')
        ->and(Sqids::prefixForModel(model: $charge::class))
        ->toBe('ch');
    ;
});

it('can encode an id and decode ids', function (): void {
    $sqid = Sqids::encodeId(model: Customer::class, id: 1);

    expect($sqid)
        ->toBeString()
        ->and(Sqids::decodeId(model: Customer::class, id: $sqid)[0])
        ->toBeInt()
        ->toBe(1);
});

it('can get the encoder instance', function (): void {
    expect(Sqids::encoder(model: Customer::class))
        ->toBeInstanceOf(\Sqids\Sqids::class);
});
