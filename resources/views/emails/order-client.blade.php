<x-mail::message>
@php($emailText = static fn (string $key, string $default): string => st("emails.$key", $default))
# {{ $emailText('client_heading', 'Дякуємо за замовлення') }}

{{ str_replace(':order', (string) ($order->number ?? $order->id), $emailText('client_intro', 'Ваше замовлення №:order прийнято в обробку.')) }}

@php
    $money = fn ($value) => number_format((float) $value, 0, ',', ' ') . ' грн';
    $payment = $order->payment instanceof \App\Enums\PaymentMethodEnum
        ? $order->payment
        : \App\Enums\PaymentMethodEnum::tryFrom((int) ($order->payment ?? 0));
    $deliveryTitle = $order->self_pickup
        ? $emailText('pickup', 'Шоу-рум Sevia, самовивіз')
        : match ((string) ($order->nova_delivery_type ?? 'warehouse')) {
            'postomat' => $emailText('postomat', 'Нова Пошта, поштомат'),
            'courier' => $emailText('courier', 'Курʼєр Нової Пошти'),
            default => $emailText('warehouse', 'Нова Пошта, відділення'),
        };
    $deliveryPlace = $order->self_pickup
        ? $emailText('pickup_address', 'вул. Хрещатик 22, Київ')
        : trim(implode(', ', array_filter([$order->nova_city, $order->nova_city_details, $order->nova_warehouse])));
@endphp

**{{ $emailText('amount', 'Сума') }}:** {{ $money($order->grand_total) }}  
**{{ $emailText('payment', 'Оплата') }}:** {{ $payment?->label('uk') ?? 'LiqPay' }}  
**{{ $emailText('delivery', 'Доставка') }}:** {{ $deliveryTitle }}  
@if ($deliveryPlace !== '')
**{{ $emailText('address', 'Адреса') }}:** {{ $deliveryPlace }}
@endif

<x-mail::table>
| {{ $emailText('product', 'Аромат') }} | {{ $emailText('quantity', 'Кількість') }} | {{ $emailText('amount', 'Сума') }} |
|:--|:--:|--:|
@foreach($order->items as $item)
@php
    $product = $item->product;
    $parent = $product?->parent ?: $product;
    $name = $parent?->display_name ?? $parent?->displayName ?? $parent?->title ?? $emailText('product_fallback', 'Товар');
@endphp
| {{ $name }} | {{ (int) $item->qty }} | {{ $money((float) $item->qty * (float) $item->unit_price) }} |
@endforeach
</x-mail::table>

{{ $emailText('client_footer', 'Якщо потрібно щось уточнити, ми звʼяжемося з вами.') }}

Sevia
</x-mail::message>
