<x-mail::message>
@php($emailText = static fn (string $key, string $default): string => st("emails.$key", $default))
# {{ $emailText('admin_heading', 'Нове замовлення №') }}{{ $order->number ?? $order->id }}

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

**{{ $emailText('client', 'Клієнт') }}:** {{ trim(($client?->surname ? $client->surname . ' ' : '') . ($client?->name ?? '')) ?: '—' }}  
**{{ $emailText('phone', 'Телефон') }}:** {{ $client?->phone ?? $order->recipient_phone ?? '—' }}  
**Email:** {{ $client?->email ?? '—' }}  
**{{ $emailText('recipient', 'Отримувач') }}:** {{ $recipient ?: '—' }}  
**{{ $emailText('delivery', 'Доставка') }}:** {{ $deliveryTitle }}  
@if ($deliveryPlace !== '')
**{{ $emailText('address', 'Адреса') }}:** {{ $deliveryPlace }}  
@endif
**{{ $emailText('amount', 'Сума') }}:** {{ $money($order->grand_total) }}

@if (trim((string) $order->notes) !== '')
**{{ $emailText('comment', 'Коментар') }}:** {{ $order->notes }}
@endif

<x-mail::table>
| {{ $emailText('product', 'Товар') }} | {{ $emailText('quantity', 'Кількість') }} | {{ $emailText('amount', 'Сума') }} |
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

<x-mail::button :url="$adminOrderUrl">
{{ $emailText('open_admin', 'Відкрити замовлення в адмінці') }}
</x-mail::button>
</x-mail::message>
