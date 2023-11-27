<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedExplosion\Sqids\Model;
use Workbench\App\Models\Charge;
use Workbench\App\Models\Customer;
use Workbench\Database\Factories\ChargeFactory;
use Workbench\Database\Factories\CustomerFactory;

it('can find a model from the sqid', function (): void {
    $john = CustomerFactory::new()->create(['name' => 'John']);
    $jane = CustomerFactory::new()->create(['name' => 'Jane']);

    $charge = ChargeFactory::new()->create(['amount' => 2000]);

    expect(Model::find(sqid: 'invalid-sqid'))
        ->toBeNull()
        ->and(Model::find(sqid: $jane->sqid))
        ->toBeInstanceOf(Customer::class)
        ->name->toBe('Jane')
        ->and(Model::find(sqid: $charge->sqid))
        ->toBeInstanceOf(Charge::class)
        ->amount->toBe(2000)
        ->and(Model::find(sqid: $john->sqid))
        ->toBeInstanceOf(Customer::class)
        ->name->toBe('John');
});

it('can find a model from the sqid or throw an exception', function (): void {
    $customer = CustomerFactory::new()->create();

    expect(Model::findOrFail(sqid: $customer->sqid))
        ->toBeInstanceOf(Customer::class)
        ->name->toBe($customer->name);

    $this->expectException(ModelNotFoundException::class);

    Model::findOrFail(sqid: 'invalid-sqid');
});
