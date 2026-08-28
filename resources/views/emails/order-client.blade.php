<x-mail::message>
# Дякуємо за замовлення

Ваше замовлення №{{ $order->number ?? $order->id }} прийнято в обробку.

@php
    $money = fn ($value) => number_format((float) $value, 0, ',', ' ') . ' грн';
    $payment = $order->payment instanceof \App\Enums\PaymentMethodEnum
        ? $order->payment
        : \App\Enums\PaymentMethodEnum::tryFrom((int) ($order->payment ?? 0));
    $deliveryTitle = $order->self_pickup
        ? 'Шоу-рум Sevia, самовивіз'
        : match ((string) ($order->nova_delivery_type ?? 'warehouse')) {
            'postomat' => 'Нова Пошта, поштомат',
            'courier' => 'Курʼєр Нової Пошти',
            default => 'Нова Пошта, відділення',
        };
    $deliveryPlace = $order->self_pickup
        ? 'вул. Хрещатик 22, Київ'
        : trim(implode(', ', array_filter([$order->nova_city, $order->nova_city_details, $order->nova_warehouse])));
@endphp

**Сума:** {{ $money($order->grand_total) }}  
**Оплата:** {{ $payment?->label('uk') ?? 'LiqPay' }}  
**Доставка:** {{ $deliveryTitle }}  
@if ($deliveryPlace !== '')
**Адреса:** {{ $deliveryPlace }}
@endif

<x-mail::table>
| Аромат | Кількість | Сума |
|:--|:--:|--:|
@foreach($order->items as $item)
@php
    $product = $item->product;
    $parent = $product?->parent ?: $product;
    $name = $parent?->display_name ?? $parent?->displayName ?? $parent?->title ?? 'Товар';
@endphp
| {{ $name }} | {{ (int) $item->qty }} | {{ $money((float) $item->qty * (float) $item->unit_price) }} |
@endforeach
</x-mail::table>

Якщо потрібно щось уточнити, ми звʼяжемося з вами.

Sevia
</x-mail::message>
