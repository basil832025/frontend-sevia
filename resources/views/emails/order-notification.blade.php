<x-mail::message>
# Нове замовлення №{{ $order->number ?? $order->id }}

@php
    $money = fn ($value) => number_format((float) $value, 0, ',', ' ') . ' грн';
    $adminOrderUrl = rtrim((string) config('app.url'), '/') . '/admin/callcenter/orders/' . $order->id . '/edit';
    $client = $order->clients;
    $recipient = trim(implode(' ', array_filter([
        $order->recipient_surname,
        $order->recipient_name,
        $order->recipient_patronymic,
    ])));
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

**Клієнт:** {{ trim(($client?->surname ? $client->surname . ' ' : '') . ($client?->name ?? '')) ?: '—' }}  
**Телефон:** {{ $client?->phone ?? $order->recipient_phone ?? '—' }}  
**Email:** {{ $client?->email ?? '—' }}  
**Отримувач:** {{ $recipient ?: '—' }}  
**Доставка:** {{ $deliveryTitle }}  
@if ($deliveryPlace !== '')
**Адреса:** {{ $deliveryPlace }}  
@endif
**Сума:** {{ $money($order->grand_total) }}

@if (trim((string) $order->notes) !== '')
**Коментар:** {{ $order->notes }}
@endif

<x-mail::table>
| Товар | Кількість | Сума |
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

<x-mail::button :url="$adminOrderUrl">
Відкрити замовлення в адмінці
</x-mail::button>
</x-mail::message>
