import flatpickr from 'flatpickr';
import { Russian } from 'flatpickr/dist/l10n/ru.js';
import { Ukrainian } from 'flatpickr/dist/l10n/uk.js';
import 'flatpickr/dist/flatpickr.min.css';

flatpickr.l10ns.ru = Russian;
flatpickr.l10ns.uk = Ukrainian;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-birthday-picker]').forEach((input) => {
        if (input.readOnly) return;

        const locale = input.dataset.locale || 'uk';
        const form = input.closest('form');
        const dialog = form?.querySelector('[data-birthday-dialog]');
        let confirmed = false;
        const picker = flatpickr(input, {
            dateFormat: 'd.m.Y',
            allowInput: true,
            maxDate: input.dataset.maxDate,
            minDate: '01.01.1901',
            disableMobile: true,
            locale: locale === 'ru' ? flatpickr.l10ns.ru : locale === 'uk' ? flatpickr.l10ns.uk : flatpickr.l10ns.default,
        });

        form?.addEventListener('submit', (event) => {
            if (!input.value || confirmed) {
                confirmed = false;
                return;
            }

            event.preventDefault();
            dialog?.classList.remove('hidden');
            dialog?.querySelector('[data-birthday-dialog-date]')?.replaceChildren(document.createTextNode(input.value));
        });

        dialog?.querySelector('[data-birthday-dialog-cancel]')?.addEventListener('click', () => {
            dialog.classList.add('hidden');
            picker.clear();
        });

        dialog?.querySelector('[data-birthday-dialog-confirm]')?.addEventListener('click', () => {
            dialog.classList.add('hidden');
            confirmed = true;
            form.requestSubmit();
        });

        dialog?.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.querySelector('[data-birthday-dialog-cancel]')?.click();
        });
    });

    document.querySelectorAll('[data-full-name-mobile]').forEach((input) => {
        const form = input.closest('form');
        form?.addEventListener('submit', () => {
            if (!window.matchMedia('(max-width: 639px)').matches) {
                return;
            }

            const parts = input.value.trim().split(/\s+/).filter(Boolean);
            const name = form.querySelector('[name="name"]');
            const surname = form.querySelector('[name="surname"]');

            if (name) name.value = parts.shift() || '';
            if (surname) surname.value = parts.join(' ');
        });
    });
});

document.querySelectorAll('[data-menu-toggle]').forEach((toggle) => {
    const header = toggle.closest('[data-site-header]');
    const menu = header?.querySelector('[data-mobile-menu]');
    const closeButtons = menu?.querySelectorAll('[data-menu-close]') ?? [];

    const openMenu = () => {
        toggle.setAttribute('aria-expanded', 'true');

        if (menu) {
            menu.hidden = false;
            menu.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    };

    const closeMenu = () => {
        toggle.setAttribute('aria-expanded', 'false');

        if (menu) {
            menu.hidden = true;
            menu.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    };

    toggle.addEventListener('click', () => {
        const expanded = toggle.getAttribute('aria-expanded') === 'true';

        if (expanded) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', closeMenu);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menu && !menu.hidden) {
            closeMenu();
        }
    });
});

document.querySelectorAll('[data-search-overlay]').forEach((overlay) => {
    const input = overlay.querySelector('[data-search-input]');
    const openButtons = document.querySelectorAll('[data-search-open]');
    const closeButtons = overlay.querySelectorAll('[data-search-close]');

    const open = () => {
        overlay.hidden = false;
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        window.setTimeout(() => input?.focus(), 0);
    };

    const close = () => {
        overlay.hidden = true;
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', open);
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', close);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !overlay.hidden) {
            close();
        }
    });
});

document.querySelectorAll('[data-sort-sheet]').forEach((sheet) => {
    const openButtons = document.querySelectorAll('[data-sort-open]');
    const closeButtons = sheet.querySelectorAll('[data-sort-close]');
    const options = sheet.querySelectorAll('[data-sort-option]');

    const open = () => {
        sheet.hidden = false;
        sheet.classList.remove('hidden');
        sheet.dataset.open = 'true';
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        sheet.hidden = true;
        sheet.classList.add('hidden');
        sheet.dataset.open = 'false';
        document.body.classList.remove('overflow-hidden');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', open);
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', close);
    });

    options.forEach((option) => {
        option.addEventListener('click', (event) => {
            event.preventDefault();

            options.forEach((item) => {
                const selected = item === option;
                const radio = item.querySelector('[data-sort-radio]');
                const dot = item.querySelector('[data-sort-dot]');
                const label = item.querySelector('[data-sort-label]');

                item.dataset.selected = selected ? 'true' : 'false';
                radio?.classList.toggle('border-2', selected);
                radio?.classList.toggle('border-[#5B2730]', selected);
                radio?.classList.toggle('border', !selected);
                radio?.classList.toggle('border-[#E8DAD0]', !selected);
                dot?.classList.toggle('hidden', !selected);
                label?.classList.toggle('font-medium', selected);
                label?.classList.toggle('text-[#5B2730]', selected);
                label?.classList.toggle('font-normal', !selected);
                label?.classList.toggle('text-[#7A4751]', !selected);
            });

            window.setTimeout(() => {
                window.location.href = option.href;
            }, 160);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !sheet.hidden) {
            close();
        }
    });
});

document.querySelectorAll('[data-filter-drawer]').forEach((drawer) => {
    const openButtons = document.querySelectorAll('[data-filter-open]');
    const closeButtons = drawer.querySelectorAll('[data-filter-close]');

    const open = () => {
        drawer.hidden = false;
        drawer.classList.remove('hidden');
        drawer.dataset.open = 'true';
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        drawer.hidden = true;
        drawer.classList.add('hidden');
        drawer.dataset.open = 'false';
        document.body.classList.remove('overflow-hidden');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', open);
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', close);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !drawer.hidden) {
            close();
        }
    });
});

document.querySelectorAll('[data-filter-count-form]').forEach((form) => {
    const countTarget = form.querySelector('[data-filter-count]');
    const countUrl = form.dataset.filterCountUrl;

    if (!countTarget || !countUrl) {
        return;
    }

    let controller = null;
    let timer = null;

    const buildUrl = () => {
        const params = new URLSearchParams(new FormData(form));

        params.delete('page');

        [...params.entries()].forEach(([key, value]) => {
            if (value === '') {
                params.delete(key);
            }
        });

        const url = new URL(countUrl, window.location.origin);
        url.search = params.toString();

        return url;
    };

    const updateCount = () => {
        controller?.abort();
        controller = new AbortController();

        fetch(buildUrl(), {
            headers: {
                Accept: 'application/json',
            },
            signal: controller.signal,
        })
            .then((response) => (response.ok ? response.json() : Promise.reject(response)))
            .then((data) => {
                if (typeof data.count !== 'undefined') {
                    countTarget.textContent = data.count;
                }
            })
            .catch((error) => {
                if (error?.name !== 'AbortError') {
                    console.error(error);
                }
            });
    };

    const scheduleUpdate = () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(updateCount, 180);
    };

    form.addEventListener('input', scheduleUpdate);
    form.addEventListener('change', scheduleUpdate);
});

document.querySelectorAll('[data-price-filter]').forEach((filter) => {
    const form = filter.closest('form');
    const minBound = Number(filter.dataset.priceMin || 0);
    const maxBound = Number(filter.dataset.priceMax || minBound);
    const minInput = filter.querySelector('[data-price-min-input]');
    const maxInput = filter.querySelector('[data-price-max-input]');
    const minRange = filter.querySelector('[data-price-min-range]');
    const maxRange = filter.querySelector('[data-price-max-range]');
    const fill = filter.querySelector('[data-price-range-fill]');

    if (!minInput || !maxInput || !minRange || !maxRange || !fill || maxBound <= minBound) {
        return;
    }

    const clamp = (value, min, max) => Math.min(Math.max(Number(value) || min, min), max);

    const sync = (source = null) => {
        let minValue = clamp(source === minInput ? minInput.value : minRange.value, minBound, maxBound);
        let maxValue = clamp(source === maxInput ? maxInput.value : maxRange.value, minBound, maxBound);

        if (source === minRange || source === minInput) {
            minValue = Math.min(minValue, maxValue);
        } else {
            maxValue = Math.max(maxValue, minValue);
        }

        minInput.value = minValue;
        maxInput.value = maxValue;
        minRange.value = minValue;
        maxRange.value = maxValue;

        const left = ((minValue - minBound) / (maxBound - minBound)) * 100;
        const right = 100 - (((maxValue - minBound) / (maxBound - minBound)) * 100);

        fill.style.left = `${left}%`;
        fill.style.right = `${right}%`;
    };

    [minInput, maxInput, minRange, maxRange].forEach((control) => {
        control.addEventListener('input', () => sync(control));
        control.addEventListener('change', () => {
            sync(control);

            if (form?.matches('[data-filter-count-form]')) {
                form.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                form?.requestSubmit();
            }
        });
    });

    sync();
});

document.querySelectorAll('[data-brand-toggle]').forEach((toggle) => {
    const fieldset = toggle.closest('fieldset');
    const extras = fieldset?.querySelectorAll('[data-brand-extra]') ?? [];
    const collapsedLabel = toggle.dataset.collapsedLabel || toggle.textContent;
    const expandedLabel = toggle.dataset.expandedLabel || 'Згорнути';

    toggle.addEventListener('click', () => {
        const expanded = toggle.getAttribute('aria-expanded') === 'true';

        extras.forEach((item) => {
            item.classList.toggle('hidden', expanded);
            item.classList.toggle('flex', !expanded);
        });

        toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        toggle.textContent = expanded ? collapsedLabel : expandedLabel;
    });
});

document.querySelectorAll('[data-brand-search]').forEach((search) => {
    const fieldset = search.closest('fieldset');
    const items = fieldset?.querySelectorAll('[data-brand-list] label') ?? [];
    const toggle = fieldset?.querySelector('[data-brand-toggle]');

    search.addEventListener('input', () => {
        const term = search.value.trim().toLowerCase();

        items.forEach((item, index) => {
            const matches = item.textContent.toLowerCase().includes(term);

            const shouldHide = term ? !matches : index >= 5;

            item.classList.toggle('hidden', shouldHide);
            item.classList.toggle('flex', !shouldHide);
        });

        if (toggle) {
            toggle.hidden = Boolean(term);
            toggle.setAttribute('aria-expanded', 'false');
            toggle.textContent = toggle.dataset.collapsedLabel || toggle.textContent;
        }
    });
});

document.querySelectorAll('[data-cart-remove-form]').forEach((form) => {
    const openButton = form.querySelector('[data-cart-remove-open]');
    const cancelButton = form.querySelector('[data-cart-remove-cancel]');
    const confirm = form.querySelector('[data-cart-remove-confirm]');

    if (!openButton || !confirm) {
        return;
    }

    const close = () => {
        confirm.hidden = true;
        confirm.classList.add('hidden');
    };

    const open = () => {
        document.querySelectorAll('[data-cart-remove-confirm]').forEach((item) => {
            if (item !== confirm) {
                item.hidden = true;
                item.classList.add('hidden');
            }
        });

        confirm.hidden = false;
        confirm.classList.remove('hidden');
    };

    openButton.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (confirm.hidden) {
            open();
        } else {
            close();
        }
    });

    cancelButton?.addEventListener('click', (event) => {
        event.preventDefault();
        close();
    });

    document.addEventListener('click', (event) => {
        if (!confirm.hidden && !form.contains(event.target)) {
            close();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !confirm.hidden) {
            close();
        }
    });
});

(() => {
    const form = document.querySelector('[data-phone-auth-form]');
    if (!form) return;
    if (document.querySelector('[data-code-step]')) return;

    const phone = form.querySelector('[data-phone-input]');
    const code = form.querySelector('[data-code-input]');
    const codeWrap = form.querySelector('[data-code-wrap]');
    const submit = form.querySelector('[data-phone-submit]');
    const resend = form.querySelector('[data-phone-resend]');
    const message = form.querySelector('[data-phone-message]');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let verifyMode = false;

    const phoneDigits = () => phone.value.replace(/\D/g, '').replace(/^380/, '').replace(/^0+/, '').slice(0, 9);
    const formatPhone = (value) => {
        const digits = String(value).replace(/\D/g, '').replace(/^380/, '').replace(/^0+/, '').slice(0, 9);
        const parts = [digits.slice(0, 2), digits.slice(2, 5), digits.slice(5, 7), digits.slice(7, 9)].filter(Boolean);
        phone.value = `+380${parts.length ? ` ${parts.join(' ')}` : ' '}`;
    };
    const validPhone = () => phoneDigits().length === 9;
    const validCode = () => /^\d{4}$/.test(code?.value ?? '');

    formatPhone(phone.value);
    phone.addEventListener('input', () => formatPhone(phone.value));
    phone.addEventListener('beforeinput', (event) => {
        const inserted = String(event.data || '').replace(/\D/g, '');
        if (inserted.startsWith('0') && phoneDigits().length === 0) {
            event.preventDefault();
        }
    });
    phone.addEventListener('focus', () => window.setTimeout(() => phone.setSelectionRange(phone.value.length, phone.value.length)));
    phone.addEventListener('keydown', (event) => {
        if ((event.key === 'Backspace' || event.key === 'Delete') && phone.selectionStart <= 5 && phone.selectionEnd <= 5) {
            event.preventDefault();
        }
        if (event.key === '0' && phoneDigits().length === 0) {
            event.preventDefault();
        }
    });
    code?.addEventListener('input', () => {
        code.value = code.value.replace(/\D/g, '').slice(0, 4);
    });

    const post = async (url, payload) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify(payload),
        });
        const data = await response.json();
        if (!response.ok || data.ok === false) throw new Error(data.message || 'Не вдалося виконати запит.');
        return data;
    };

    const sendCode = async () => {
        formatPhone(phone.value);
        if (!validPhone()) {
            message.textContent = 'Введіть номер повністю: +380 XX XXX XX XX.';
            phone.focus();
            return;
        }

        submit.disabled = true;
        message.textContent = 'Надсилаємо код...';
        try {
            await post(form.action, { phone: `380${phoneDigits()}` });
            verifyMode = true;
            codeWrap.classList.remove('hidden');
            submit.textContent = 'Підтвердити';
            resend.classList.remove('hidden');
            code.focus();
            message.textContent = 'Код надіслано. Введіть 4 цифри з СМС.';
        } catch (error) {
            message.textContent = error.message;
        } finally {
            submit.disabled = false;
        }
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!verifyMode) {
            await sendCode();
            return;
        }

        submit.disabled = true;
        message.textContent = 'Перевіряємо код...';
        if (!validCode()) {
            message.textContent = 'Введіть 4 цифри з СМС.';
            submit.disabled = false;
            code.focus();
            return;
        }
        try {
            const data = await post('/auth/phone-sms/verify', { phone: `380${phoneDigits()}`, code: code.value });
            window.location.assign(data.redirect || '/checkout');
        } catch (error) {
            message.textContent = error.message;
            submit.disabled = false;
        }
    });

    resend?.addEventListener('click', sendCode);
})();

(() => {
    const buttons = document.querySelectorAll('[data-favorite-toggle]');
    if (!buttons.length) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const syncCount = (count) => {
        document.querySelectorAll('[data-favorites-count]').forEach((badge) => {
            badge.textContent = String(count);
            badge.classList.toggle('hidden', count < 1);
            if (count > 0) {
                badge.classList.toggle('inline-flex', badge.classList.contains('min-w-4'));
                badge.classList.toggle('grid', ! badge.classList.contains('min-w-4'));
            }
        });
    };

    buttons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();
            if (button.disabled) return;

            button.disabled = true;
            try {
                const response = await fetch(button.dataset.favoriteUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ product_id: button.dataset.productId }),
                });
                const data = await response.json();
                if (!response.ok || data.ok === false) return;

                button.textContent = data.favorite ? '♥' : '♡';
                button.setAttribute('aria-pressed', data.favorite ? 'true' : 'false');
                button.setAttribute('aria-label', data.favorite ? 'Видалити з обраного' : 'Додати в обране');
                button.classList.toggle('text-[#B03B45]', data.favorite);
                button.classList.toggle('text-[#7A4751]', !data.favorite);
                syncCount(Number(data.count ?? 0));

                if (!data.favorite && button.hasAttribute('data-favorite-remove')) {
                    button.closest('article')?.remove();
                    if (Number(data.count ?? 0) === 0) window.location.reload();
                }
            } finally {
                button.disabled = false;
            }
        });
    });
})();

(() => {
    const modal = document.querySelector('[data-logout-modal]');
    const forms = document.querySelectorAll('form[action*="account/logout"]');
    if (!modal || !forms.length) return;

    let activeForm = null;
    const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        activeForm = null;
    };
    const open = (form) => {
        activeForm = form;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        modal.querySelector('[data-logout-cancel]')?.focus();
    };

    forms.forEach((form) => {
        form.querySelector('button[type="submit"]')?.addEventListener('click', (event) => {
            event.preventDefault();
            open(form);
        });
    });
    modal.querySelector('[data-logout-cancel]')?.addEventListener('click', close);
    modal.querySelector('[data-logout-confirm]')?.addEventListener('click', () => activeForm?.submit());
    modal.addEventListener('click', (event) => {
        if (event.target === modal) close();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
    });
})();

// Account address uses the same Nova Poshta endpoints as the checkout form.
(() => {
    const cityWrap = document.querySelector('[data-account-nova-city-wrap]');
    const warehouseWrap = document.querySelector('[data-account-nova-warehouse-wrap]');

    if (!cityWrap || !warehouseWrap) return;

    const cityInput = cityWrap.querySelector('[data-account-city-input]');
    const cityRef = cityWrap.querySelector('[data-account-city-ref]');
    const cityMenu = cityWrap.querySelector('[data-account-city-menu]');
    const warehouseInput = warehouseWrap.querySelector('[data-account-warehouse-input]');
    const warehouseRef = warehouseWrap.querySelector('[data-account-warehouse-ref]');
    const warehouseMenu = warehouseWrap.querySelector('[data-account-warehouse-menu]');
    let cityTimer = null;
    let warehouseTimer = null;
    let cityController = null;
    let warehouseController = null;

    const open = (menu, value) => menu?.classList.toggle('hidden', !value);
    const message = (menu, text) => {
        if (!menu) return;
        menu.innerHTML = '';
        const item = document.createElement('div');
        item.className = 'px-4 py-3 text-[12.5px] leading-[19px] text-[#A98088]';
        item.textContent = text;
        menu.appendChild(item);
        open(menu, true);
    };
    const resultButton = (menu, title, meta, onClick, first) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `flex min-h-[58px] w-full flex-col items-start justify-center px-4 py-2 text-left transition hover:bg-[#FBF4F0] ${first ? '' : 'border-t border-[#F0E6DE]'}`;
        const titleNode = document.createElement('span');
        titleNode.className = 'text-[15px] leading-[23px] text-[#5B2730]';
        titleNode.textContent = title;
        const metaNode = document.createElement('span');
        metaNode.className = 'text-[12px] leading-[18px] text-[#A98088]';
        metaNode.textContent = meta || 'Нова Пошта';
        button.append(titleNode, metaNode);
        button.addEventListener('click', onClick);
        menu?.appendChild(button);
    };

    const renderCities = (cities) => {
        if (!cityMenu) return;
        cityMenu.innerHTML = '';
        if (!cities.length) {
            message(cityMenu, 'Місто не знайдено');
            return;
        }
        cities.forEach((city, index) => {
            const details = city.details || [city.area ? `${city.area} обл.` : '', city.region ? `${city.region} р-н.` : ''].filter(Boolean).join(', ');
            const name = city.display_name || city.name || city.label || '';
            resultButton(cityMenu, name, details, () => {
                cityInput.value = city.label || city.name || name;
                cityRef.value = city.ref || '';
                warehouseInput.value = '';
                warehouseRef.value = '';
                warehouseInput.disabled = !cityRef.value;
                open(cityMenu, false);
                warehouseMenu.innerHTML = '';
            }, index === 0);
        });
        open(cityMenu, true);
    };

    const renderWarehouses = (warehouses) => {
        if (!warehouseMenu) return;
        warehouseMenu.innerHTML = '';
        if (!warehouses.length) {
            message(warehouseMenu, 'Відділення не знайдено');
            return;
        }
        warehouses.forEach((warehouse, index) => {
            const name = warehouse.label || warehouse.name || '';
            const meta = warehouse.schedule?.weekday ? `Пн - Пт: ${warehouse.schedule.weekday}` : 'Нова Пошта';
            resultButton(warehouseMenu, name, meta, () => {
                warehouseInput.value = name;
                warehouseRef.value = warehouse.ref || '';
                open(warehouseMenu, false);
            }, index === 0);
        });
        open(warehouseMenu, true);
    };

    const fetchCities = async () => {
        const query = cityInput.value.trim();
        if (!query) {
            cityRef.value = '';
            open(cityMenu, false);
            return;
        }
        cityController?.abort();
        cityController = new AbortController();
        message(cityMenu, 'Завантажуємо міста...');
        try {
            const response = await fetch(`${cityWrap.dataset.citiesUrl}?${new URLSearchParams({ q: query, limit: '20' })}`, { headers: { Accept: 'application/json' }, signal: cityController.signal });
            const data = response.ok ? await response.json() : {};
            renderCities(Array.isArray(data.cities) ? data.cities : []);
        } catch (error) {
            if (error.name !== 'AbortError') message(cityMenu, 'Не вдалося завантажити міста');
        }
    };

    const fetchWarehouses = async () => {
        const ref = cityRef.value.trim();
        if (!ref) {
            message(warehouseMenu, 'Спочатку оберіть місто.');
            return;
        }
        warehouseController?.abort();
        warehouseController = new AbortController();
        message(warehouseMenu, 'Завантажуємо відділення...');
        try {
            const response = await fetch(`${warehouseWrap.dataset.warehousesUrl}?${new URLSearchParams({ city_ref: ref, q: warehouseInput.value.trim(), limit: '30', type: 'warehouse' })}`, { headers: { Accept: 'application/json' }, signal: warehouseController.signal });
            const data = response.ok ? await response.json() : {};
            renderWarehouses(Array.isArray(data.warehouses) ? data.warehouses : []);
        } catch (error) {
            if (error.name !== 'AbortError') message(warehouseMenu, 'Не вдалося завантажити відділення');
        }
    };

    cityInput.addEventListener('input', () => {
        cityRef.value = '';
        warehouseInput.value = '';
        warehouseRef.value = '';
        warehouseInput.disabled = true;
        window.clearTimeout(cityTimer);
        cityTimer = window.setTimeout(fetchCities, 220);
    });
    warehouseInput.addEventListener('input', () => {
        warehouseRef.value = '';
        window.clearTimeout(warehouseTimer);
        warehouseTimer = window.setTimeout(fetchWarehouses, 220);
    });
    cityInput.addEventListener('focus', () => { if (cityInput.value.trim()) fetchCities(); });
    warehouseInput.addEventListener('focus', () => { if (cityRef.value) fetchWarehouses(); });
    document.addEventListener('click', (event) => {
        if (!cityWrap.contains(event.target)) open(cityMenu, false);
        if (!warehouseWrap.contains(event.target)) open(warehouseMenu, false);
    });
    warehouseInput.disabled = !cityRef.value;
})();

(() => {
    const form = document.querySelector('[data-phone-auth-form]');
    const phoneStep = document.querySelector('[data-phone-step]');
    const codeStep = document.querySelector('[data-code-step]');
    const recipientStep = document.querySelector('[data-recipient-step]');
    if (!form || !phoneStep || !codeStep) return;

    const phone = form.querySelector('[data-phone-input]');
    const submit = form.querySelector('[data-phone-submit]');
    const message = form.querySelector('[data-phone-message]');
    const codePhone = document.querySelector('[data-code-phone]');
    const digits = [...document.querySelectorAll('[data-code-digit]')];
    const confirm = document.querySelector('[data-code-submit]');
    const resend = document.querySelector('[data-code-resend]');
    const timer = document.querySelector('[data-code-timer]');
    const codeMessage = document.querySelector('[data-code-message]');
    const codeError = document.querySelector('[data-code-error]');
    const codeActions = document.querySelector('[data-code-actions]');
    const phoneBack = document.querySelector('[data-phone-back]');
    const recipientPhone = document.querySelector('[data-recipient-phone]');
    const authVisual = document.querySelector('[data-auth-visual]');
    const orderSummary = document.querySelector('[data-auth-order-summary]');
    const orderMeta = document.querySelector('[data-auth-order-meta]');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let timerId;
    let verifying = false;
    let codeHasError = false;

    const phoneDigits = () => phone.value.replace(/\D/g, '').replace(/^380/, '').replace(/^0+/, '').slice(0, 9);
    const formatPhone = (value) => {
        const digits = String(value).replace(/\D/g, '').replace(/^380/, '').replace(/^0+/, '').slice(0, 9);
        const parts = [digits.slice(0, 2), digits.slice(2, 5), digits.slice(5, 7), digits.slice(7, 9)].filter(Boolean);
        phone.value = `+380${parts.length ? ` ${parts.join(' ')}` : ' '}`;
    };
    formatPhone(phone.value);
    phone.addEventListener('input', () => formatPhone(phone.value));
    phone.addEventListener('beforeinput', (event) => {
        const inserted = String(event.data || '').replace(/\D/g, '');
        if (inserted.startsWith('0') && phoneDigits().length === 0) event.preventDefault();
    });
    phone.addEventListener('focus', () => window.setTimeout(() => phone.setSelectionRange(phone.value.length, phone.value.length)));
    phone.addEventListener('keydown', (event) => {
        if ((event.key === 'Backspace' || event.key === 'Delete') && phone.selectionStart <= 5 && phone.selectionEnd <= 5) event.preventDefault();
        if (event.key === '0' && phoneDigits().length === 0) event.preventDefault();
    });
    const codeValue = () => digits.map((input) => input.value).join('');
    const setError = (visible) => {
        codeHasError = visible;
        codeError?.classList.toggle('hidden', !visible);
        codeError?.classList.toggle('flex', visible);
        digits.forEach((input) => input.classList.toggle('border-[#B84249]', visible));
        digits.forEach((input) => input.classList.toggle('border-[#E8DAD0]', !visible));
    };
    const updateConfirmState = () => {
        const valid = /^\d{4}$/.test(codeValue());
        confirm.disabled = !valid;
        confirm.classList.toggle('bg-[#5B2730]', valid);
        confirm.classList.toggle('text-[#FFF8F4]', valid);
        confirm.classList.toggle('border-[#5B2730]', valid);
        confirm.classList.toggle('text-[#C9A9B0]', !valid);
        return valid;
    };
    const request = async (url, payload) => {
        const response = await fetch(url, { method: 'POST', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(payload) });
        const data = await response.json();
        if (!response.ok || data.ok === false) throw new Error(data.message || 'Не вдалося виконати запит.');
        return data;
    };
    const startTimer = (seconds) => {
        clearInterval(timerId);
        let left = seconds;
        codeActions?.classList.add('hidden');
        if (codeActions) codeActions.style.display = 'none';
        resend?.classList.add('hidden');
        timer.textContent = `Надіслати повторно можна через 0:${String(left).padStart(2, '0')}`;
        timerId = setInterval(() => {
            left -= 1;
            if (left <= 0) {
                clearInterval(timerId);
                timer.textContent = 'Можна надіслати код повторно.';
                codeActions?.classList.remove('hidden');
                if (codeActions) codeActions.style.display = 'flex';
                resend?.classList.remove('hidden');
            } else {
                timer.textContent = `Надіслати повторно можна через 0:${String(left).padStart(2, '0')}`;
            }
        }, 1000);
    };
    const sendCode = async () => {
        if (phoneDigits().length !== 9) { message.textContent = 'Введіть номер повністю: +380 XX XXX XX XX.'; phone.focus(); return; }
        submit.disabled = true;
        try {
            const data = await request(form.action, { phone: `380${phoneDigits()}` });
            phoneStep.classList.add('hidden');
            codeStep.classList.remove('hidden');
            codePhone.textContent = phone.value;
            codeMessage.textContent = '';
            digits[0]?.focus();
            startTimer(Number(data.resend_in ?? 60));
        } catch (error) { message.textContent = error.message; }
        submit.disabled = false;
    };

    const verifyCode = async () => {
        if (verifying || !/^\d{4}$/.test(codeValue())) return;
        verifying = true;
        setError(false);
        try {
            await request('/auth/phone-sms/verify', { phone: `380${phoneDigits()}`, code: codeValue() });
            window.location.assign('/checkout');
        } catch (error) {
            setError(true);
            digits[0]?.focus();
        } finally {
            verifying = false;
        }
    };

    digits.forEach((input, index) => {
        input.addEventListener('click', () => {
            if (!codeHasError) return;
            digits.forEach((digit) => { digit.value = ''; });
            setError(false);
            updateConfirmState();
            digits[0]?.focus();
        });
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(-1);
            if (input.value) digits[index + 1]?.focus();
            setError(false);
            updateConfirmState();
            if (/^\d{4}$/.test(codeValue())) verifyCode();
        });
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace' && !input.value) digits[index - 1]?.focus();
        });
        input.addEventListener('paste', (event) => {
            const pasted = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 4);
            if (!pasted) return;
            event.preventDefault();
            pasted.split('').forEach((digit, digitIndex) => { if (digits[digitIndex]) digits[digitIndex].value = digit; });
            setError(false);
            updateConfirmState();
            digits[Math.min(pasted.length, 4) - 1]?.focus();
            if (/^\d{4}$/.test(codeValue())) verifyCode();
        });
    });
    confirm?.classList.add('hidden');
    form.addEventListener('submit', (event) => { event.preventDefault(); sendCode(); });
    confirm?.addEventListener('click', async () => {
        if (!/^\d{4}$/.test(codeValue())) { codeMessage.textContent = 'Введіть 4 цифри з СМС.'; return; }
        confirm.disabled = true;
        try {
            const data = await request('/auth/phone-sms/verify', { phone: `380${phoneDigits()}`, code: codeValue() });
            window.location.assign(data.redirect || '/checkout');
        } catch (error) {
            setError(true);
            confirm.disabled = false;
            updateConfirmState();
        }
    });
    resend?.addEventListener('click', sendCode);
    phoneBack?.addEventListener('click', () => {
        clearInterval(timerId);
        codeStep.classList.add('hidden');
        phoneStep.classList.remove('hidden');
        phone.focus();
    });
})();

(() => {
    const form = document.querySelector('[data-recipient-form]');
    if (!form) return;

    const fields = [...form.querySelectorAll('[data-recipient-field]')];
    const submitButtons = [...document.querySelectorAll(`[form="${form.id}"], [data-recipient-form] button[type="submit"]`)];
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const messages = {
        first_name: 'Вкажіть імʼя.',
        last_name: 'Вкажіть прізвище.',
        emailRequired: 'Вкажіть електронну пошту.',
        emailMissingAt: 'Перевірте пошту. Схоже, в адресі бракує @.',
        emailMissingDomain: 'Схоже, в адресі загубилася частина після @',
        emailInvalid: 'Введіть коректну адресу електронної пошти.',
    };

    const emailIsValid = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);

    const errorFor = (input) => form.querySelector(`[data-recipient-error-for="${input.name}"]`);
    const helpFor = (input) => form.querySelector(`[data-recipient-help-for="${input.name}"]`);

    const setFieldError = (input, message = '') => {
        const hasError = message !== '';
        const label = input.closest('label');
        const error = errorFor(input);
        const help = helpFor(input);

        input.classList.toggle('border-[#B84249]', hasError);
        input.classList.toggle('border-[#E8DAD0]', !hasError);
        input.setAttribute('aria-invalid', hasError ? 'true' : 'false');
        label?.classList.toggle('text-[#B84249]', hasError);
        label?.classList.toggle('text-[#A98088]', !hasError);

        if (error) {
            error.textContent = message;
            error.classList.toggle('hidden', !hasError);
        }

        help?.classList.toggle('hidden', hasError);
    };

    const getFieldError = (input) => {
        const value = input.value.trim();

        if (input.dataset.recipientRequired !== undefined && value === '') {
            return input.name === 'email'
                ? messages.emailRequired
                : (messages[input.name] || messages.emailInvalid);
        }

        if (input.name !== 'email' || value === '') {
            return '';
        }

        if (!value.includes('@')) {
            return messages.emailMissingAt;
        }

        const [, domain = ''] = value.split('@');
        if (domain.trim() === '' || !domain.includes('.')) {
            return messages.emailMissingDomain;
        }

        return emailIsValid(value) ? '' : messages.emailInvalid;
    };

    const validateField = (input) => {
        const message = getFieldError(input);
        setFieldError(input, message);
        return message === '';
    };

    const showServerErrors = (errors = {}) => {
        Object.entries(errors).forEach(([field, fieldMessages]) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (!input) return;

            setFieldError(input, Array.isArray(fieldMessages) ? fieldMessages[0] : String(fieldMessages));
        });
    };

    fields.forEach((input) => {
        input.addEventListener('input', () => {
            if (input.getAttribute('aria-invalid') === 'true' || input.dataset.recipientTouched === 'true') {
                validateField(input);
            }
        });

        input.addEventListener('blur', () => {
            input.dataset.recipientTouched = 'true';
            validateField(input);
        });
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const isValid = fields.map(validateField).every(Boolean);
        if (!isValid) {
            fields.find((input) => input.getAttribute('aria-invalid') === 'true')?.focus();
            return;
        }

        const payload = Object.fromEntries(new FormData(form).entries());
        submitButtons.forEach((button) => { button.disabled = true; });

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            });
            const data = await response.json();

            if (!response.ok || data.ok === false) {
                showServerErrors(data.errors || {});
                throw new Error(data.message || 'Не вдалося зберегти контактні дані.');
            }

            window.location.assign(data.redirect || '/checkout');
        } catch (error) {
            fields.find((input) => input.getAttribute('aria-invalid') === 'true')?.focus();
        } finally {
            submitButtons.forEach((button) => { button.disabled = false; });
        }
    });

    document.querySelectorAll('[data-edit-recipient]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelector('[data-delivery-step]')?.classList.add('hidden');
            document.querySelector('[data-delivery-step]')?.classList.remove('flex');
            document.querySelector('[data-recipient-step]')?.classList.remove('hidden');
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
})();

(() => {
    const wrap = document.querySelector('[data-delivery-method-wrap]');
    if (!wrap) return;

    const toggle = wrap.querySelector('[data-delivery-method-toggle]');
    const menu = wrap.querySelector('[data-delivery-method-menu]');
    const input = wrap.querySelector('[data-delivery-method-input]');
    const deliveryPriceInput = wrap.querySelector('[data-delivery-price-input]');
    const options = [...wrap.querySelectorAll('[data-delivery-option]')];
    const selectedIcon = wrap.querySelector('[data-delivery-selected-icon]');
    const selectedTitle = wrap.querySelector('[data-delivery-selected-title]');
    const selectedMeta = wrap.querySelector('[data-delivery-selected-meta]');
    const selectedPrice = wrap.querySelector('[data-delivery-selected-price]');
    const deliveryStep = document.querySelector('[data-delivery-step]');
    const summaryTitle = document.querySelector('[data-delivery-summary-title]');
    const summaryPrice = document.querySelector('[data-delivery-summary-price]');
    const grandTotalTargets = [...document.querySelectorAll('[data-order-grand-total]')];
    const confirmTotal = document.querySelector('[data-confirm-grand-total]');
    const novaDeliveryFields = document.querySelector('[data-nova-delivery-fields]');
    const warehouseWrap = document.querySelector('[data-nova-warehouse-wrap]');
    const courierPanel = document.querySelector('[data-nova-courier-panel]');
    const cashPaymentLabel = document.querySelector('[data-payment-cash-label]');
    const cashPaymentInput = cashPaymentLabel?.querySelector('input[type="radio"]');
    const liqpayPaymentInput = document.querySelector('[data-payment-liqpay]');
    const otherRecipientLabel = document.querySelector('[data-other-recipient-toggle-label]');
    const otherRecipientToggle = document.querySelector('[data-other-recipient-toggle]');
    const otherRecipientPanel = document.querySelector('[data-other-recipient-panel]');
    const cartTotal = Number(deliveryStep?.dataset.cartTotal || 0);
    const freeShippingFrom = Number(deliveryStep?.dataset.freeShippingFrom || 0);
    const hasFreeShipping = freeShippingFrom > 0 && cartTotal >= freeShippingFrom;
    const pricesUrl = deliveryStep?.dataset.deliveryPricesUrl;

    const formatMoney = (value) => `${Math.round(Number(value) || 0).toLocaleString('uk-UA').replace(/\u00a0/g, ' ')} ₴`;
    const deliveryPriceValue = (price) => (hasFreeShipping ? 0 : Number(price) || 0);
    const deliveryPriceLabel = (price) => (hasFreeShipping ? 'безкоштовно' : `від ${formatMoney(price)}`);
    const summaryLabelFor = (title) => {
        if (title.startsWith('Нова Пошта')) return 'Нова Пошта';
        if (title.startsWith('Шоу-рум')) return 'Самовивіз';
        return title;
    };

    const setOpen = (isOpen) => {
        if (!menu || !toggle) return;

        menu.classList.toggle('hidden', !isOpen);
        menu.classList.toggle('flex', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    const optionTitle = (option) => option.querySelector('span.flex.flex-col > span:first-child');

    const setActiveOption = (selected) => {
        options.forEach((option) => {
            const isActive = option === selected;
            const title = optionTitle(option);
            let bar = option.querySelector('[data-delivery-active-bar]');

            option.dataset.active = isActive ? 'true' : 'false';
            option.classList.toggle('relative', isActive);
            title?.classList.toggle('text-[#5B2730]', isActive);
            title?.classList.toggle('text-[#7A4751]', !isActive);

            if (isActive && !bar) {
                bar = document.createElement('span');
                bar.dataset.deliveryActiveBar = '';
                bar.className = 'absolute bottom-0 left-0 top-0 w-0.5 bg-[#5B2730]';
                option.appendChild(bar);
            }

            if (!isActive) {
                bar?.remove();
            }
        });
    };

    const selectOption = (option, emitEvent = true) => {
        const title = option.dataset.title || '';
        const meta = option.dataset.meta || '';
        const price = deliveryPriceValue(option.dataset.price || 0);
        const priceLabel = option.dataset.priceLabel || formatMoney(price);
        const icon = option.dataset.icon || '';
        const method = option.dataset.value || '';
        const grandTotal = formatMoney(cartTotal);
        const isPickup = method === 'sevia_pickup';
        const isCourier = method === 'nova_courier';

        if (input) input.value = method;
        if (deliveryPriceInput) deliveryPriceInput.value = String(price);
        if (selectedIcon) selectedIcon.textContent = icon;
        if (selectedTitle) selectedTitle.textContent = title;
        if (selectedMeta) selectedMeta.textContent = meta;
        if (selectedPrice) selectedPrice.textContent = priceLabel;
        if (summaryTitle) summaryTitle.textContent = ` · ${summaryLabelFor(title)}`;
        if (summaryPrice) summaryPrice.textContent = priceLabel;
        grandTotalTargets.forEach((target) => { target.textContent = grandTotal; });
        if (confirmTotal) confirmTotal.textContent = grandTotal;
        novaDeliveryFields?.classList.toggle('hidden', isPickup);
        novaDeliveryFields?.classList.toggle('flex', !isPickup);
        warehouseWrap?.classList.toggle('hidden', isPickup || isCourier);
        warehouseWrap?.classList.toggle('flex', !isPickup && !isCourier);
        courierPanel?.classList.toggle('hidden', !isCourier);
        courierPanel?.classList.toggle('flex', isCourier);
        cashPaymentLabel?.classList.toggle('hidden', !isPickup);
        cashPaymentLabel?.classList.toggle('flex', isPickup);
        otherRecipientLabel?.classList.toggle('hidden', isPickup);
        otherRecipientLabel?.classList.toggle('flex', !isPickup);

        if (isPickup) {
            if (otherRecipientToggle) otherRecipientToggle.checked = false;
            otherRecipientPanel?.classList.add('hidden');
            otherRecipientPanel?.classList.remove('flex');
        }

        if (!isPickup && cashPaymentInput?.checked && liqpayPaymentInput) {
            liqpayPaymentInput.checked = true;
        }

        setActiveOption(option);
        setOpen(false);
        if (emitEvent) {
            document.dispatchEvent(new CustomEvent('delivery-method:selected', { detail: { method } }));
        }
    };

    const updateOptionPrice = (value, price) => {
        const option = options.find((item) => item.dataset.value === value);
        if (!option || price === null || price === undefined) return;

        const numericPrice = deliveryPriceValue(price);
        if (!Number.isFinite(numericPrice)) return;

        const priceLabel = deliveryPriceLabel(price);
        option.dataset.price = String(numericPrice);
        option.dataset.priceLabel = priceLabel;
        option.querySelector('[data-delivery-option-price]')?.replaceChildren(document.createTextNode(priceLabel));

        if (option.dataset.active === 'true') {
            selectOption(option, false);
        }
    };

    const fetchDeliveryPrices = async (cityRef) => {
        if (!pricesUrl || !cityRef) return;

        try {
            const response = await fetch(`${pricesUrl}?${new URLSearchParams({ city_ref: cityRef })}`, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            const prices = data.prices || {};
            updateOptionPrice('nova_branch', prices.nova_branch);
            updateOptionPrice('nova_postomat', prices.nova_postomat);
            updateOptionPrice('nova_courier', prices.nova_courier);
        } catch (error) {
            // Keep the last known tariff when Nova Post pricing is temporarily unavailable.
        }
    };

    toggle?.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    options.forEach((option) => {
        option.addEventListener('click', () => selectOption(option, true));
    });

    document.addEventListener('click', (event) => {
        if (!wrap.contains(event.target)) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
            toggle?.focus();
        }
    });

    document.addEventListener('nova-city:selected', (event) => {
        fetchDeliveryPrices(event.detail?.ref || '');
    });

    fetchDeliveryPrices(document.querySelector('[data-nova-city-ref]')?.value || '');
    selectOption(options.find((option) => option.dataset.active === 'true') || options[0], false);
})();

(() => {
    const form = document.querySelector('[data-delivery-form]');
    if (!form || !form.dataset.deliverySaveUrl) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let debounceId = null;
    let activeController = null;
    let hasUnsavedChanges = false;

    const fieldValue = (name) => {
        const field = form.querySelector(`[name="${name}"]`);

        if (!field) return '';
        if (field.type === 'checkbox') return field.checked ? true : false;
        if (field.type === 'radio') return form.querySelector(`[name="${name}"]:checked`)?.value || '';

        return field.value;
    };

    const payload = () => ({
        delivery_method: fieldValue('delivery_method'),
        city: fieldValue('city'),
        city_ref: fieldValue('city_ref'),
        city_name: fieldValue('city_name'),
        city_display_name: fieldValue('city_display_name'),
        city_details: fieldValue('city_details'),
        warehouse_ref: fieldValue('warehouse_ref'),
        warehouse_name: fieldValue('warehouse_name'),
        street_ref: fieldValue('street_ref'),
        street: fieldValue('street'),
        house: fieldValue('house'),
        apartment: fieldValue('apartment'),
        floor: fieldValue('floor'),
        entrance: fieldValue('entrance'),
        elevator: fieldValue('elevator'),
        bring_to_floor: fieldValue('bring_to_floor'),
        payment: fieldValue('payment'),
        shipping_price: fieldValue('shipping_price'),
        comment: fieldValue('comment'),
        other_recipient: fieldValue('other_recipient'),
        other_recipient_surname: fieldValue('other_recipient_surname'),
        other_recipient_name: fieldValue('other_recipient_name'),
        other_recipient_patronymic: fieldValue('other_recipient_patronymic'),
        other_recipient_phone: fieldValue('other_recipient_phone'),
        confirm_without_call: fieldValue('confirm_without_call'),
        gift_no_receipt: fieldValue('gift_no_receipt'),
    });

    const save = async () => {
        activeController?.abort();
        activeController = new AbortController();

        try {
            await fetch(form.dataset.deliverySaveUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload()),
                keepalive: true,
                signal: activeController.signal,
            });
            hasUnsavedChanges = false;
        } catch (error) {
            if (error.name !== 'AbortError') {
                // Delivery choices are also kept in the visible form; retry on the next change.
            }
        }
    };

    const scheduleSave = () => {
        hasUnsavedChanges = true;
        window.clearTimeout(debounceId);
        debounceId = window.setTimeout(save, 220);
    };

    const saveBeforeUnload = () => {
        if (!hasUnsavedChanges || !navigator.sendBeacon) return;

        const formData = new FormData();
        formData.append('_token', csrf);
        Object.entries(payload()).forEach(([key, value]) => {
            formData.append(key, value === true ? '1' : value === false ? '0' : String(value ?? ''));
        });
        navigator.sendBeacon(form.dataset.deliverySaveUrl, formData);
    };

    form.addEventListener('input', scheduleSave);
    form.addEventListener('change', scheduleSave);
    document.addEventListener('delivery-method:selected', () => {
        hasUnsavedChanges = true;
        save();
    });
    document.addEventListener('nova-city:selected', () => {
        hasUnsavedChanges = true;
        save();
    });
    document.addEventListener('nova-warehouse:selected', () => {
        hasUnsavedChanges = true;
        save();
    });
    document.addEventListener('nova-courier-address:selected', () => {
        hasUnsavedChanges = true;
        save();
    });
    window.addEventListener('beforeunload', saveBeforeUnload);
})();

(() => {
    const wrap = document.querySelector('[data-nova-city-wrap]');
    if (!wrap) return;

    const input = wrap.querySelector('[data-nova-city-input]');
    const openButton = wrap.querySelector('[data-nova-city-open]');
    const modal = wrap.querySelector('[data-nova-city-modal]');
    const closeButton = wrap.querySelector('[data-nova-city-close]');
    const searchInput = wrap.querySelector('[data-nova-city-search]');
    const selected = wrap.querySelector('[data-nova-city-selected]');
    const selectedName = wrap.querySelector('[data-nova-city-selected-name]');
    const selectedDetails = wrap.querySelector('[data-nova-city-selected-details]');
    const refInput = wrap.querySelector('[data-nova-city-ref]');
    const nameInput = wrap.querySelector('[data-nova-city-name]');
    const displayNameInput = wrap.querySelector('[data-nova-city-display-name]');
    const detailsInput = wrap.querySelector('[data-nova-city-details]');
    const menu = wrap.querySelector('[data-nova-city-menu]');
    const url = wrap.dataset.citiesUrl;
    let activeController = null;
    let debounceId = null;

    const setResultsOpen = (isOpen) => {
        menu?.classList.toggle('hidden', !isOpen);
        menu?.classList.toggle('flex', isOpen);
    };

    const setModalOpen = (isOpen) => {
        modal?.classList.toggle('hidden', !isOpen);
        modal?.classList.toggle('flex', isOpen);
        document.body.classList.toggle('overflow-hidden', isOpen);

        if (isOpen) {
            window.setTimeout(() => {
                searchInput?.focus();
                searchInput?.select();
            }, 0);
        }
    };

    const clearMenu = () => {
        if (menu) {
            menu.innerHTML = '';
        }
    };

    const renderMessage = (message) => {
        if (!menu) return;

        menu.innerHTML = `<div class="px-4 py-3 text-[12.5px] leading-[19px] text-[#A98088]">${message}</div>`;
        setResultsOpen(true);
    };

    const cityDetails = (city) => city.details || [
        city.area ? `${city.area} обл.` : '',
        city.region ? `${city.region} р-н.` : '',
    ].filter(Boolean).join(', ');

    const showSelectedCity = (city) => {
        const details = cityDetails(city);

        if (selectedName) selectedName.textContent = city.display_name || city.name || city.label || '';
        if (selectedDetails) selectedDetails.textContent = details;
        selected?.classList.remove('hidden');
        selected?.classList.add('flex');
    };

    const renderCities = (cities) => {
        if (!menu) return;

        clearMenu();

        if (cities.length === 0) {
            renderMessage('Місто не знайдено');
            return;
        }

        cities.forEach((city, index) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = [
                'flex min-h-[58px] w-full flex-col items-start justify-center px-4 py-2 text-left normal-case tracking-normal',
                index > 0 ? 'border-t border-[#F0E6DE]' : '',
            ].filter(Boolean).join(' ');
            button.innerHTML = `
                <span class="text-[16px] leading-[24px] text-[#5B2730]"></span>
                <span class="text-[12.5px] leading-[19px] text-[#A98088]"></span>
            `;

            const title = button.querySelector('span:first-child');
            const meta = button.querySelector('span:last-child');
            const details = cityDetails(city);

            title.textContent = city.display_name || city.name || city.label || '';
            meta.textContent = details || 'Нова Пошта';

            button.addEventListener('click', () => {
                input.value = city.label || city.name || '';
                if (refInput) refInput.value = city.ref || '';
                if (nameInput) nameInput.value = city.name || '';
                if (displayNameInput) displayNameInput.value = city.display_name || city.name || city.label || '';
                if (detailsInput) detailsInput.value = details;
                showSelectedCity(city);
                document.dispatchEvent(new CustomEvent('nova-city:selected', { detail: city }));
                setResultsOpen(false);
                setModalOpen(false);
            });

            menu.appendChild(button);
        });

        setResultsOpen(true);
    };

    const searchCities = async () => {
        const query = searchInput?.value.trim() || '';
        if (!searchInput || !url || query === '') {
            clearMenu();
            setResultsOpen(false);
            return;
        }

        activeController?.abort();
        activeController = new AbortController();

        try {
            const response = await fetch(`${url}?${new URLSearchParams({ q: query, limit: '20' })}`, {
                headers: { Accept: 'application/json' },
                signal: activeController.signal,
            });

            if (!response.ok) {
                renderMessage('Не вдалося завантажити міста');
                return;
            }

            const data = await response.json();
            renderCities(Array.isArray(data.cities) ? data.cities : []);
        } catch (error) {
            if (error.name !== 'AbortError') {
                renderMessage('Не вдалося завантажити міста');
            }
        }
    };

    searchInput?.addEventListener('input', () => {
        window.clearTimeout(debounceId);
        debounceId = window.setTimeout(searchCities, 220);
    });

    openButton?.addEventListener('click', () => {
        if (searchInput && input) {
            searchInput.value = nameInput?.value || input.value || '';
        }
        setModalOpen(true);
        searchCities();
    });

    closeButton?.addEventListener('click', () => {
        setModalOpen(false);
    });

    wrap.querySelectorAll('[data-nova-popular-city]').forEach((button) => {
        button.addEventListener('click', () => {
            if (!searchInput) return;

            searchInput.value = button.dataset.novaPopularCity || button.textContent.trim();
            searchCities();
        });
    });

    searchInput?.addEventListener('focus', () => {
        if (menu?.children.length) {
            setResultsOpen(true);
        }
    });

    document.addEventListener('click', (event) => {
        if (modal && event.target === modal) {
            setModalOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setResultsOpen(false);
            setModalOpen(false);
        }
    });
})();

(() => {
    const panel = document.querySelector('[data-nova-courier-panel]');
    if (!panel) return;

    const streetInput = panel.querySelector('[data-nova-street-input]');
    const streetRefInput = panel.querySelector('[data-nova-street-ref]');
    const houseInput = panel.querySelector('[data-nova-house-input]');
    const streetMenu = panel.querySelector('[data-nova-street-menu]');
    const cityRefInput = document.querySelector('[data-nova-city-ref]');
    const deliveryMethodInput = document.querySelector('[data-delivery-method-input]');
    const confirmButton = document.querySelector('[data-delivery-confirm]');
    const url = panel.dataset.streetsUrl;
    let debounceId = null;
    let activeController = null;

    const isCourierDelivery = () => deliveryMethodInput?.value === 'nova_courier';

    const setMenuOpen = (isOpen) => {
        streetMenu?.classList.toggle('hidden', !isOpen);
        streetMenu?.classList.toggle('flex', isOpen);
    };

    const setFieldError = (name, message = '') => {
        const error = panel.querySelector(`[data-nova-courier-error-for="${name}"]`);
        const input = name === 'street' ? streetInput : houseInput;
        const hasError = message !== '';

        error?.classList.toggle('hidden', !hasError);
        if (error) error.textContent = message;
        input?.classList.toggle('border-[#B84249]', hasError);
        input?.classList.toggle('border-[#E8DAD0]', !hasError);
    };

    const renderMessage = (message) => {
        if (!streetMenu) return;
        streetMenu.innerHTML = `<div class="px-4 py-4 text-[12.5px] leading-[19px] text-[#A98088]">${message}</div>`;
    };

    const renderStreets = (streets) => {
        if (!streetMenu) return;
        streetMenu.innerHTML = '';

        if (streets.length === 0) {
            renderMessage('Вулиці не знайдено');
            return;
        }

        streets.forEach((street, index) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = [
                'flex min-h-[50px] w-full items-center px-4 py-2 text-left text-[15px] leading-[22px] text-[#5B2730] transition hover:bg-[#FBF4F0]',
                index > 0 ? 'border-t border-[#F0E6DE]' : '',
            ].filter(Boolean).join(' ');
            button.textContent = street.label || street.name || '';
            button.addEventListener('click', () => {
                if (streetInput) streetInput.value = street.label || street.name || '';
                if (streetRefInput) streetRefInput.value = street.ref || '';
                setFieldError('street');
                setMenuOpen(false);
                document.dispatchEvent(new CustomEvent('nova-courier-address:selected', { detail: street }));
            });
            streetMenu.appendChild(button);
        });
    };

    const fetchStreets = async () => {
        const cityRef = cityRefInput?.value.trim() || '';
        const query = streetInput?.value.trim() || '';

        if (!isCourierDelivery() || query.length < 2) {
            setMenuOpen(false);
            return;
        }

        if (!cityRef) {
            renderMessage('Спочатку оберіть місто.');
            setMenuOpen(true);
            return;
        }

        activeController?.abort();
        activeController = new AbortController();
        renderMessage('Шукаємо вулиці...');
        setMenuOpen(true);

        try {
            const response = await fetch(`${url}?${new URLSearchParams({ city_ref: cityRef, q: query, limit: '20' })}`, {
                headers: { Accept: 'application/json' },
                signal: activeController.signal,
            });

            if (!response.ok) {
                renderMessage('Не вдалося завантажити вулиці');
                return;
            }

            const data = await response.json();
            renderStreets(Array.isArray(data.streets) ? data.streets : []);
        } catch (error) {
            if (error.name !== 'AbortError') {
                renderMessage('Не вдалося завантажити вулиці');
            }
        }
    };

    streetInput?.addEventListener('input', () => {
        if (streetRefInput) streetRefInput.value = '';
        setFieldError('street');
        window.clearTimeout(debounceId);
        debounceId = window.setTimeout(fetchStreets, 220);
    });

    houseInput?.addEventListener('input', () => setFieldError('house'));

    document.addEventListener('nova-city:selected', () => {
        if (streetInput) streetInput.value = '';
        if (streetRefInput) streetRefInput.value = '';
        if (houseInput) houseInput.value = '';
        setFieldError('street');
        setFieldError('house');
        setMenuOpen(false);
    });

    document.addEventListener('click', (event) => {
        if (!panel.contains(event.target)) {
            setMenuOpen(false);
        }
    });

    confirmButton?.addEventListener('click', (event) => {
        if (!isCourierDelivery()) return;

        const hasStreet = (streetInput?.value.trim() || '') !== '';
        const hasHouse = (houseInput?.value.trim() || '') !== '';

        setFieldError('street', hasStreet ? '' : 'Вкажіть вулицю.');
        setFieldError('house', hasHouse ? '' : 'Вкажіть будинок.');

        if (!hasStreet || !hasHouse) {
            event.preventDefault();
            panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
})();

(() => {
    const wrap = document.querySelector('[data-nova-warehouse-wrap]');
    if (!wrap) return;

    const toggle = wrap.querySelector('[data-nova-warehouse-toggle]');
    const panel = wrap.querySelector('[data-nova-warehouse-panel]');
    const searchInput = wrap.querySelector('[data-nova-warehouse-search]');
    const menu = wrap.querySelector('[data-nova-warehouse-menu]');
    const label = wrap.querySelector('[data-nova-warehouse-label]');
    const selected = wrap.querySelector('[data-nova-warehouse-selected]');
    const refInput = wrap.querySelector('[data-nova-warehouse-ref]');
    const nameInput = wrap.querySelector('[data-nova-warehouse-name]');
    const error = wrap.querySelector('[data-nova-warehouse-error]');
    const cityRefInput = document.querySelector('[data-nova-city-ref]');
    const deliveryMethodInput = document.querySelector('[data-delivery-method-input]');
    const confirmButton = document.querySelector('[data-delivery-confirm]');
    const url = wrap.dataset.warehousesUrl;
    let debounceId = null;
    let activeController = null;

    const setOpen = (isOpen) => {
        panel?.classList.toggle('hidden', !isOpen);
        panel?.classList.toggle('flex', isOpen);
        toggle?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

        if (isOpen) {
            window.setTimeout(() => searchInput?.focus(), 0);
        }
    };

    const setError = (message = '') => {
        const hasError = message !== '';
        error?.classList.toggle('hidden', !hasError);
        if (error) error.textContent = message;
        toggle?.classList.toggle('border-[#B84249]', hasError);
        toggle?.classList.toggle('border-[#E8DAD0]', !hasError);
    };

    const warehouseType = () => deliveryMethodInput?.value === 'nova_postomat' ? 'postomat' : 'warehouse';
    const warehouseLabel = () => warehouseType() === 'postomat' ? 'Поштомат' : 'Відділення';
    const warehousePlaceholder = () => warehouseType() === 'postomat' ? 'Оберіть поштомат' : 'Оберіть відділення';
    const warehouseEmptyMessage = () => warehouseType() === 'postomat' ? 'Поштомати не знайдено' : 'Відділення не знайдено';
    const warehouseLoadingMessage = () => warehouseType() === 'postomat' ? 'Завантажуємо поштомати...' : 'Завантажуємо відділення...';
    const warehouseErrorMessage = () => warehouseType() === 'postomat' ? 'Оберіть поштомат Нової Пошти.' : 'Оберіть відділення Нової Пошти.';

    const syncWarehouseCopy = () => {
        if (label) label.textContent = warehouseLabel();
        if (searchInput) {
            searchInput.placeholder = warehouseType() === 'postomat'
                ? 'Введіть адресу або номер поштомата'
                : 'Введіть адресу або номер відділення';
        }
    };

    const resetWarehouse = () => {
        if (refInput) refInput.value = '';
        if (nameInput) nameInput.value = '';
        if (selected) {
            selected.textContent = 'Оберіть відділення';
            selected.textContent = warehousePlaceholder();
            selected.classList.add('text-[#C9A9B0]');
            selected.classList.remove('text-[#5B2730]');
        }
        if (searchInput) searchInput.value = '';
        if (menu) menu.innerHTML = '';
        setError('');
    };

    const isPickupDelivery = () => deliveryMethodInput?.value === 'sevia_pickup';
    const needsWarehouse = () => ['nova_branch', 'nova_postomat'].includes(deliveryMethodInput?.value || '');

    const renderMessage = (message) => {
        if (!menu) return;
        menu.innerHTML = `<div class="px-4 py-4 text-[12.5px] leading-[19px] text-[#A98088]">${message}</div>`;
    };

    const renderWarehouses = (warehouses) => {
        if (!menu) return;
        menu.innerHTML = '';

        if (warehouses.length === 0) {
            renderMessage(warehouseEmptyMessage());
            return;
        }

        warehouses.forEach((warehouse, index) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = [
                'flex min-h-[58px] w-full flex-col items-start justify-center px-4 py-2 text-left normal-case tracking-normal transition hover:bg-[#FBF4F0]',
                index > 0 ? 'border-t border-[#F0E6DE]' : '',
            ].filter(Boolean).join(' ');
            button.innerHTML = `
                <span class="text-[15.5px] leading-[23px] text-[#5B2730]"></span>
                <span class="text-[12px] leading-[18px] text-[#A98088]"></span>
            `;

            const title = button.querySelector('span:first-child');
            const meta = button.querySelector('span:last-child');
            title.textContent = warehouse.label || warehouse.name || '';
            meta.textContent = warehouse.schedule?.weekday ? `Пн - Пт: ${warehouse.schedule.weekday}` : 'Нова Пошта';

            button.addEventListener('click', () => {
                if (refInput) refInput.value = warehouse.ref || '';
                if (nameInput) nameInput.value = warehouse.label || warehouse.name || '';
                if (selected) {
                    selected.textContent = warehouse.label || warehouse.name || '';
                    selected.classList.remove('text-[#C9A9B0]');
                    selected.classList.add('text-[#5B2730]');
                }
                setError('');
                setOpen(false);
                document.dispatchEvent(new CustomEvent('nova-warehouse:selected', { detail: warehouse }));
            });

            menu.appendChild(button);
        });
    };

    const fetchWarehouses = async () => {
        const cityRef = cityRefInput?.value.trim() || '';
        const query = searchInput?.value.trim() || '';

        if (!cityRef) {
            renderMessage('Спочатку оберіть місто.');
            setOpen(true);
            return;
        }

        activeController?.abort();
        activeController = new AbortController();
        renderMessage(warehouseLoadingMessage());

        try {
            const response = await fetch(`${url}?${new URLSearchParams({ city_ref: cityRef, q: query, limit: '30', type: warehouseType() })}`, {
                headers: { Accept: 'application/json' },
                signal: activeController.signal,
            });

            if (!response.ok) {
                renderMessage('Не вдалося завантажити відділення');
                return;
            }

            const data = await response.json();
            renderWarehouses(Array.isArray(data.warehouses) ? data.warehouses : []);
        } catch (error) {
            if (error.name !== 'AbortError') {
                renderMessage('Не вдалося завантажити відділення');
            }
        }
    };

    toggle?.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') !== 'true';
        setOpen(isOpen);
        if (isOpen && menu?.children.length === 0) {
            fetchWarehouses();
        }
    });

    searchInput?.addEventListener('input', () => {
        window.clearTimeout(debounceId);
        debounceId = window.setTimeout(fetchWarehouses, 220);
    });

    document.addEventListener('nova-city:selected', () => {
        resetWarehouse();
        setOpen(false);
    });

    document.addEventListener('click', (event) => {
        if (!wrap.contains(event.target)) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    confirmButton?.addEventListener('click', (event) => {
        if (isPickupDelivery() || !needsWarehouse()) {
            setError('');
            setOpen(false);
            return;
        }

        if (!refInput?.value) {
            event.preventDefault();
            setError(warehouseErrorMessage());
            setOpen(true);
            if (menu?.children.length === 0) {
                fetchWarehouses();
            }
        }
    });

    document.addEventListener('delivery-method:selected', (event) => {
        if (event.detail?.method === 'sevia_pickup') {
            setError('');
            setOpen(false);
            return;
        }

        if (!['nova_branch', 'nova_postomat'].includes(event.detail?.method || '')) {
            return;
        }

        syncWarehouseCopy();
        resetWarehouse();
        if (panel && !panel.classList.contains('hidden')) {
            fetchWarehouses();
        }
    });

    syncWarehouseCopy();
})();

(() => {
    const options = [...document.querySelectorAll('[data-payment-option]')];
    if (options.length === 0) return;

    const sync = () => {
        options.forEach((option) => {
            const input = option.querySelector('input[type="radio"]');
            const isActive = input?.checked === true;

            option.classList.toggle('border', isActive);
            option.classList.toggle('border-[#5B2730]', isActive);
            option.classList.toggle('bg-[#FBF4F0]', isActive);
            option.classList.toggle('border-transparent', !isActive);
            option.classList.toggle('bg-transparent', !isActive);
        });
    };

    options.forEach((option) => {
        option.querySelector('input[type="radio"]')?.addEventListener('change', sync);
    });

    document.addEventListener('delivery-method:selected', sync);
    sync();
})();

(() => {
    const toggle = document.querySelector('[data-other-recipient-toggle]');
    const panel = document.querySelector('[data-other-recipient-panel]');
    if (!toggle || !panel) return;

    const fields = [...panel.querySelectorAll('[data-other-recipient-field]')];
    const phoneInput = panel.querySelector('[data-other-recipient-phone]');
    const summary = panel.querySelector('[data-other-recipient-summary]');
    const confirmButton = document.querySelector('[data-delivery-confirm]');

    const setError = (field, message = '') => {
        const error = panel.querySelector(`[data-other-recipient-error-for="${field.name}"]`);
        const hasError = message !== '';

        error?.classList.toggle('hidden', !hasError);
        if (error) error.textContent = message;
        field.classList.toggle('border-[#B84249]', hasError);
        field.classList.toggle('border-[#E8DAD0]', !hasError);
    };

    const recipientPhoneDigits = () => (phoneInput?.value || '')
        .replace(/\D/g, '')
        .replace(/^380/, '')
        .replace(/^0+/, '')
        .slice(0, 9);

    const formatRecipientPhone = () => {
        if (!phoneInput) return;

        const digits = recipientPhoneDigits();
        const parts = [
            digits.slice(0, 2),
            digits.slice(2, 5),
            digits.slice(5, 7),
            digits.slice(7, 9),
        ].filter(Boolean);

        phoneInput.value = `+380${parts.length ? ` ${parts.join(' ')}` : ' '}`;
    };

    const phoneIsValid = () => {
        return recipientPhoneDigits().length === 9;
    };

    const updateSummary = () => {
        if (!summary) return;

        const parts = [
            panel.querySelector('[name="other_recipient_surname"]')?.value.trim(),
            panel.querySelector('[name="other_recipient_name"]')?.value.trim(),
            panel.querySelector('[name="other_recipient_patronymic"]')?.value.trim(),
        ].filter(Boolean);

        summary.textContent = parts.length > 0 ? parts.join(' ') : 'Новий отримувач';
    };

    const validateField = (field) => {
        const label = field.dataset.otherRecipientLabel || 'Поле';
        const value = field.value.trim();

        if (value === '') {
            setError(field, `${label} обов'язкове.`);
            return false;
        }

        if (field === phoneInput && !phoneIsValid()) {
            setError(field, 'Введіть коректний мобільний номер.');
            return false;
        }

        setError(field);
        return true;
    };

    const validate = () => {
        if (!toggle.checked) return true;

        let firstInvalid = null;

        fields.forEach((field) => {
            if (!validateField(field) && !firstInvalid) {
                firstInvalid = field;
            }
        });

        firstInvalid?.focus();

        return firstInvalid === null;
    };

    const setOpen = (isOpen) => {
        panel.classList.toggle('hidden', !isOpen);
        panel.classList.toggle('flex', isOpen);

        fields.forEach((field) => {
            field.required = isOpen;
            if (!isOpen) setError(field);
        });

        if (isOpen) {
            window.setTimeout(() => fields[0]?.focus(), 0);
        }
    };

    toggle.addEventListener('change', () => {
        setOpen(toggle.checked);
    });

    fields.forEach((field) => {
        field.addEventListener('input', () => {
            if (field === phoneInput) {
                formatRecipientPhone();
            }

            updateSummary();
            if (toggle.checked) validateField(field);
        });
    });

    phoneInput?.addEventListener('focus', () => {
        window.setTimeout(() => phoneInput.setSelectionRange(phoneInput.value.length, phoneInput.value.length));
    });

    phoneInput?.addEventListener('keydown', (event) => {
        if ((event.key === 'Backspace' || event.key === 'Delete') && phoneInput.selectionStart <= 5 && phoneInput.selectionEnd <= 5) {
            event.preventDefault();
        }
    });

    confirmButton?.addEventListener('click', (event) => {
        if (!validate()) {
            event.preventDefault();
        }
    });

    document.addEventListener('delivery-method:selected', (event) => {
        if (event.detail?.method === 'sevia_pickup') {
            toggle.checked = false;
            setOpen(false);
        }
    });

    setOpen(toggle.checked);
    formatRecipientPhone();
})();

(() => {
    const form = document.querySelector('[data-delivery-form]');
    const confirmButton = document.querySelector('[data-delivery-confirm]');

    if (!form || !confirmButton || !form.dataset.checkoutSubmitUrl) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const originalHtml = confirmButton.innerHTML;

    const fieldValue = (name) => {
        const field = form.querySelector(`[name="${name}"]`);

        if (!field) return '';
        if (field.type === 'checkbox') return field.checked ? true : false;
        if (field.type === 'radio') return form.querySelector(`[name="${name}"]:checked`)?.value || '';

        return field.value;
    };

    const payload = () => ({
        delivery_method: fieldValue('delivery_method'),
        city: fieldValue('city'),
        city_ref: fieldValue('city_ref'),
        city_name: fieldValue('city_name'),
        city_display_name: fieldValue('city_display_name'),
        city_details: fieldValue('city_details'),
        warehouse_ref: fieldValue('warehouse_ref'),
        warehouse_name: fieldValue('warehouse_name'),
        street_ref: fieldValue('street_ref'),
        street: fieldValue('street'),
        house: fieldValue('house'),
        apartment: fieldValue('apartment'),
        floor: fieldValue('floor'),
        entrance: fieldValue('entrance'),
        elevator: fieldValue('elevator'),
        bring_to_floor: fieldValue('bring_to_floor'),
        payment: fieldValue('payment'),
        shipping_price: fieldValue('shipping_price'),
        comment: fieldValue('comment'),
        other_recipient: fieldValue('other_recipient'),
        other_recipient_surname: fieldValue('other_recipient_surname'),
        other_recipient_name: fieldValue('other_recipient_name'),
        other_recipient_patronymic: fieldValue('other_recipient_patronymic'),
        other_recipient_phone: fieldValue('other_recipient_phone'),
        confirm_without_call: fieldValue('confirm_without_call'),
        gift_no_receipt: fieldValue('gift_no_receipt'),
    });

    const postJson = async (url, body = null) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: body === null ? '{}' : JSON.stringify(body),
        });
        const data = await response.json().catch(() => ({}));

        if (!response.ok || data.ok === false) {
            const message = data.message || Object.values(data.errors || {})?.[0]?.[0] || 'Не вдалося оформити замовлення.';
            throw new Error(message);
        }

        return data;
    };

    const setFormError = (message) => {
        let error = form.querySelector('[data-checkout-submit-error]');

        if (!error) {
            error = document.createElement('div');
            error.dataset.checkoutSubmitError = '';
            error.className = 'border border-[#B84249]/35 bg-[#FCF4F3] px-4 py-3 text-[13.5px] font-medium leading-5 text-[#B84249]';
            form.insertBefore(error, form.firstElementChild);
        }

        error.textContent = message;
        error.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    confirmButton.addEventListener('click', async (event) => {
        if (event.defaultPrevented || confirmButton.disabled) return;

        event.preventDefault();
        form.querySelector('[data-checkout-submit-error]')?.remove();
        confirmButton.disabled = true;
        confirmButton.classList.add('opacity-70', 'cursor-wait');
        confirmButton.innerHTML = 'Оформлюємо...';

        try {
            await postJson(form.dataset.deliverySaveUrl, payload());
            const data = await postJson(form.dataset.checkoutSubmitUrl);

            if (data.redirect) {
                window.location.assign(data.redirect);
                return;
            }

            window.location.assign('/checkout');
        } catch (error) {
            setFormError(error.message || 'Не вдалося оформити замовлення.');
            confirmButton.disabled = false;
            confirmButton.classList.remove('opacity-70', 'cursor-wait');
            confirmButton.innerHTML = originalHtml;
        }
    });
})();

document.querySelectorAll('[data-review-modal]').forEach((modal) => {
    const openButtons = document.querySelectorAll('[data-review-open]');
    const closeButtons = modal.querySelectorAll('[data-review-close]');
    const form = modal.querySelector('[data-review-form]');
    const ratingInput = modal.querySelector('[data-review-rating]');
    const starButtons = modal.querySelectorAll('[data-rating-value]');
    const submitButton = modal.querySelector('[data-review-submit]');
    const success = modal.querySelector('[data-review-success]');

    const open = () => {
        modal.hidden = false;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        modal.hidden = true;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    const setRating = (rating) => {
        if (ratingInput) {
            ratingInput.value = String(rating);
        }

        starButtons.forEach((button) => {
            const value = Number(button.dataset.ratingValue || 0);
            button.classList.toggle('text-[#A85D66]', value <= rating);
            button.classList.toggle('text-[#E8DAD0]', value > rating);
        });
    };

    const clearErrors = () => {
        modal.querySelectorAll('[data-error-for]').forEach((error) => {
            error.textContent = '';
            error.classList.add('hidden');
        });
    };

    const showErrors = (errors) => {
        Object.entries(errors || {}).forEach(([field, messages]) => {
            const error = modal.querySelector(`[data-error-for="${field}"]`);

            if (!error) {
                return;
            }

            error.textContent = Array.isArray(messages) ? messages[0] : String(messages);
            error.classList.remove('hidden');
        });
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', open);
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', close);
    });

    starButtons.forEach((button) => {
        button.addEventListener('click', () => {
            setRating(Number(button.dataset.ratingValue || 5));
        });
    });

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearErrors();
        success?.classList.add('hidden');

        if (submitButton) {
            submitButton.disabled = true;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(form),
            });

            if (response.ok) {
                form.reset();
                setRating(5);
                success?.classList.remove('hidden');

                window.setTimeout(close, 1600);

                return;
            }

            if (response.status === 422) {
                const payload = await response.json();
                showErrors(payload.errors);

                return;
            }

            showErrors({ content: ['Не вдалося відправити відгук. Спробуйте пізніше.'] });
        } catch (error) {
            showErrors({ content: ['Мережа недоступна. Спробуйте пізніше.'] });
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.hidden) {
            close();
        }
    });

    setRating(Number(ratingInput?.value || 5));
});

document.querySelectorAll('[data-volume-option]').forEach((button) => {
    button.addEventListener('click', () => {
        const section = button.closest('[data-product-card]') ?? button.closest('section');
        const price = section?.querySelector('[data-product-price-display]');
        const volumeLine = section?.querySelector('[data-product-volume-line]');
        const unitPrice = section?.querySelector('[data-product-unit-price]');
        const options = section?.querySelectorAll('[data-volume-option]') ?? [];

        options.forEach((option) => {
            const selected = option === button;

            option.dataset.selected = selected ? 'true' : 'false';
            option.classList.toggle('sevia-volume-option--selected', selected);
            option.classList.toggle('border-[#5B2730]', selected);
            option.classList.toggle('bg-[#5B2730]', selected);
            option.classList.toggle('text-[#FDFBF8]', selected);
            option.classList.toggle('border-[#E8DAD0]', !selected);
            option.classList.toggle('bg-[#FDFBF8]', !selected);
            option.classList.toggle('text-[#7A4751]', !selected);
        });

        if (price && button.dataset.priceLabel) {
            price.textContent = button.dataset.priceLabel;
        }

        if (volumeLine) {
            volumeLine.textContent = 'за 1 мл';
        }

        if (unitPrice && button.dataset.unitPriceLabel) {
            unitPrice.textContent = button.dataset.unitPriceLabel;
        }

        const volumeLabel = section?.querySelector('[data-product-volume-label]');

        if (volumeLabel && button.dataset.volumeLabel) {
            volumeLabel.textContent = `/ ${button.dataset.volumeLabel}`;
        }

        section?.querySelectorAll('[data-cart-add]').forEach((cartButton) => {
            if (button.dataset.productId) {
                cartButton.dataset.productId = button.dataset.productId;
            }

            if (button.dataset.productPrice) {
                cartButton.dataset.productPrice = button.dataset.productPrice;
            }

            if (button.dataset.cartProductLabel) {
                cartButton.dataset.cartProductLabel = button.dataset.cartProductLabel;
            }

            if (button.dataset.volumeLabel) {
                cartButton.dataset.volumeLabel = button.dataset.volumeLabel;
            }
        });

        section?.querySelector('[data-cart-action-row]')?.dispatchEvent(new CustomEvent('cart-volume-changed', {
            detail: { productId: Number(button.dataset.productId ?? 0), volume: button.dataset.volumeLabel ?? '' },
        }));
    });
});

(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    const counts = document.querySelectorAll('[data-cart-count]');
    const statuses = document.querySelectorAll('[data-cart-status]');
    const cartQuantityByVariant = new Map();

    const variantKey = (productId, volume) => `${Number(productId) || 0}|${String(volume || '').trim().toLowerCase()}`;
    const buttonVariantKey = (button) => variantKey(button?.dataset.productId, button?.dataset.volumeLabel);
    const itemVolume = (item) => item?.meta?.volume || String(item?.meta?.cart_label || '').match(/\d+(?:[.,]\d+)?\s*(?:мл|ml)/iu)?.[0] || '';
    const closeStatusButtons = document.querySelectorAll('[data-cart-status-close]');
    let statusTimer = null;

    const updateCount = (qty) => {
        const normalizedQty = Math.max(0, Number.parseInt(qty ?? 0, 10) || 0);

        counts.forEach((count) => {
            count.textContent = String(normalizedQty);
            count.classList.toggle('hidden', normalizedQty <= 0);
            count.classList.toggle('grid', normalizedQty > 0);
        });
    };

    const hideStatus = () => {
        statuses.forEach((status) => {
            status.hidden = true;
            status.classList.add('hidden');
            status.classList.remove('flex');
        });

        if (statusTimer) {
            window.clearTimeout(statusTimer);
            statusTimer = null;
        }
    };

    const showStatus = (line) => {
        if (!statuses.length) {
            return;
        }

        statuses.forEach((status) => {
            const statusLine = status.querySelector('[data-cart-status-line]');

            if (statusLine) {
                statusLine.textContent = line || '';
            }

            status.hidden = false;
            status.classList.remove('hidden');
            status.classList.add('flex');
        });

        if (statusTimer) {
            window.clearTimeout(statusTimer);
        }

        statusTimer = window.setTimeout(hideStatus, 4200);
    };

    closeStatusButtons.forEach((button) => {
        button.addEventListener('click', hideStatus);
    });

    document.querySelectorAll('[data-cart-add]').forEach((button) => {
        button.addEventListener('click', async () => {
            const productId = Number.parseInt(button.dataset.productId ?? '0', 10);

            if (!productId || button.disabled) {
                return;
            }

            const addUrl = button.dataset.cartAddUrl || '/cart/add';
            const price = Number.parseFloat(button.dataset.productPrice ?? '');
            const payload = {
                product_id: productId,
                qty: 1,
            };

            if (Number.isFinite(price) && price > 0) {
                payload.price = price;
            }

            if (button.dataset.cartProductLabel) {
                payload.meta = {
                    cart_label: button.dataset.cartProductLabel,
                    volume: button.dataset.volumeLabel ?? '',
                };
            }

            button.disabled = true;

            try {
                const response = await fetch(addUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (!response.ok || data.ok === false) {
                    return;
                }

                updateCount(data.qty ?? 0);
                const row = button.closest('[data-cart-action-row]');
                const controls = row?.querySelector('[data-cart-quantity-controls]');
                const quantity = row?.querySelector('[data-cart-quantity]');
                if (controls && quantity) {
                    const nextQuantity = Number(quantity.textContent || 0) + 1;
                    quantity.textContent = String(nextQuantity);
                    cartQuantityByVariant.set(buttonVariantKey(button), nextQuantity);
                    controls.classList.remove('hidden');
                    controls.classList.add('flex');
                    button.classList.add('hidden');
                }
                showStatus(button.dataset.cartProductLabel || '');

                window.dispatchEvent(new CustomEvent('sevia-cart-updated', { detail: data }));
            } catch (error) {
                console.error('Cart add failed', error);
            } finally {
                button.disabled = false;
            }
        });
    });

    const setQuantityState = (row, quantityValue) => {
        const quantity = row.querySelector('[data-cart-quantity]');
        const controls = row.querySelector('[data-cart-quantity-controls]');
        const addButton = row.querySelector('[data-cart-add]');
        const quantityValueSafe = Math.max(0, Number(quantityValue) || 0);

        if (quantity) quantity.textContent = String(quantityValueSafe);
        if (controls) {
            controls.classList.toggle('hidden', quantityValueSafe <= 0);
            controls.classList.toggle('flex', quantityValueSafe > 0);
        }
        if (addButton) addButton.classList.toggle('hidden', quantityValueSafe > 0);
    };

    const changeQuantity = async (button, delta) => {
        const row = button.closest('[data-cart-action-row]');
        const addButton = row?.querySelector('[data-cart-add]');
        if (!row || !addButton || button.disabled) return;

        const productId = Number.parseInt(addButton.dataset.productId ?? '0', 10);
        const current = Number.parseInt(row.querySelector('[data-cart-quantity]')?.textContent ?? '0', 10);
        if (!productId || current + delta < 0) return;

        button.disabled = true;
        try {
            const response = await fetch('/cart/quantity', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    product_id: productId,
                    delta,
                    price: Number.parseFloat(addButton.dataset.productPrice ?? '0'),
                    meta: { cart_label: addButton.dataset.cartProductLabel ?? '', volume: addButton.dataset.volumeLabel ?? '' },
                }),
            });
            const data = await response.json();
            if (!response.ok || data.ok === false) return;

            setQuantityState(row, current + delta);
            cartQuantityByVariant.set(buttonVariantKey(addButton), current + delta);
            updateCount(data.qty ?? 0);
        } finally {
            button.disabled = false;
        }
    };

    document.querySelectorAll('[data-cart-action-row]').forEach((row) => {
        row.addEventListener('cart-volume-changed', (event) => {
            setQuantityState(row, cartQuantityByVariant.get(variantKey(event.detail?.productId, event.detail?.volume)) ?? 0);
        });
        row.querySelector('[data-cart-quantity-minus]')?.addEventListener('click', (event) => changeQuantity(event.currentTarget, -1));
        row.querySelector('[data-cart-quantity-plus]')?.addEventListener('click', (event) => changeQuantity(event.currentTarget, 1));
    });

    fetch('/cart/info', { headers: { Accept: 'application/json' } })
        .then((response) => response.ok ? response.json() : null)
        .then((data) => {
            (data?.items ?? []).forEach((item) => {
                const volume = itemVolume(item);
                cartQuantityByVariant.set(variantKey(item.product_id, volume), Number(item.qty ?? 0));
                document.querySelectorAll('[data-cart-action-row]').forEach((row) => {
                    const addButton = row.querySelector('[data-cart-add]');
                    if (buttonVariantKey(addButton) === variantKey(item.product_id, volume)) {
                        setQuantityState(row, item.qty);
                    }
                });
            });
        })
        .catch(() => {});
})();
