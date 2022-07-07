<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_id' => 'order-id-123',
            'full_code' => 'PLACED',
            'code' => 'PLC',
            'event_id' => 'event-id-123',
            'customer_id' => 1
        ];
    }
}
