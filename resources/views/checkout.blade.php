@extends('layouts.app')

@section('title', 'Pagamento — ' . $plan['name'])

@section('content')
<div class="checkout">
    {{-- Back link --}}
    <a href="{{ url('/') }}#plans" class="checkout__back">
        <i class="fa-solid fa-arrow-left"></i> Voltar aos planos
    </a>

    <div class="checkout__grid">
        {{-- Left: Payment Methods --}}
        <div class="checkout__payment">
            <h1 class="checkout__title">Finalizar assinatura</h1>
            <p class="checkout__subtitle">Escolha a forma de pagamento (demonstração)</p>

            {{-- Payment method tabs --}}
            <div class="pay-tabs">
                <button class="pay-tab pay-tab--active" data-tab="pix" type="button">
                    <i class="fa-brands fa-pix"></i> PIX
                </button>
                <button class="pay-tab" data-tab="card" type="button">
                    <i class="fa-solid fa-credit-card"></i> Cartão
                </button>
                <button class="pay-tab" data-tab="boleto" type="button">
                    <i class="fa-solid fa-barcode"></i> Boleto
                </button>
            </div>

            {{-- PIX Panel --}}
            <div class="pay-panel pay-panel--active" id="panel-pix">
                <div class="pix-box">
                    <div class="pix-box__qr">
                        <div class="pix-box__qr-fake">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <p class="pix-box__label">Escaneie o QR Code com seu app de banco</p>
                    </div>
                    <div class="pix-box__copy">
                        <label class="pix-box__copy-label">Ou copie o código PIX:</label>
                        <div class="pix-box__copy-field">
                            <input type="text" value="00020126580014br.gov.bcb.pix0136demo-brainlab-{{ $plan['slug'] }}" readonly id="pixCode">
                            <button type="button" class="pix-box__copy-btn" onclick="copyPix()">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </div>
                        <span class="pix-box__copy-feedback" id="pixFeedback">Código copiado!</span>
                    </div>
                    <p class="pix-box__timer">
                        <i class="fa-solid fa-clock"></i> Expira em <strong id="pixTimer">29:59</strong>
                    </p>
                </div>
            </div>

            {{-- Card Panel --}}
            <div class="pay-panel" id="panel-card">
                <form class="card-form" onsubmit="event.preventDefault(); fakePayment();">
                    <div class="card-form__group">
                        <label>Nome no cartão</label>
                        <div class="card-form__input-wrap">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" placeholder="Ex: Maria Silva" required>
                        </div>
                    </div>
                    <div class="card-form__group">
                        <label>Número do cartão</label>
                        <div class="card-form__input-wrap">
                            <i class="fa-solid fa-credit-card"></i>
                            <input type="text" placeholder="0000 0000 0000 0000" maxlength="19" required id="cardNumber">
                        </div>
                    </div>
                    <div class="card-form__row">
                        <div class="card-form__group">
                            <label>Validade</label>
                            <div class="card-form__input-wrap">
                                <i class="fa-solid fa-calendar"></i>
                                <input type="text" placeholder="MM/AA" maxlength="5" required id="cardExpiry">
                            </div>
                        </div>
                        <div class="card-form__group">
                            <label>CVV</label>
                            <div class="card-form__input-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input type="text" placeholder="123" maxlength="4" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-form__group">
                        <label>CPF do titular</label>
                        <div class="card-form__input-wrap">
                            <i class="fa-solid fa-id-card"></i>
                            <input type="text" placeholder="000.000.000-00" maxlength="14" required id="cardCpf">
                        </div>
                    </div>
                    <button type="submit" class="checkout__pay-btn">
                        <i class="fa-solid fa-lock"></i> Pagar {{ $plan['price'] }}
                    </button>
                </form>
            </div>

            {{-- Boleto Panel --}}
            <div class="pay-panel" id="panel-boleto">
                <div class="boleto-box">
                    <div class="boleto-box__icon">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <h3 class="boleto-box__title">Boleto bancário</h3>
                    <p class="boleto-box__desc">
                        O boleto será gerado e poderá ser pago em qualquer banco, lotérica ou app bancário.
                        A confirmação pode levar até 3 dias úteis.
                    </p>
                    <div class="boleto-box__barcode">
                        <span>23793.38128 60000.000003 00000.000402 1 {{ rand(10000000, 99999999) }}00000{{ str_replace(['R$ ', ',', '/mês'], ['', '', ''], $plan['price']) }}00</span>
                    </div>
                    <button type="button" class="checkout__pay-btn" onclick="fakePayment()">
                        <i class="fa-solid fa-download"></i> Gerar boleto
                    </button>
                </div>
            </div>

            {{-- Security badges --}}
            <div class="checkout__security">
                <span><i class="fa-solid fa-shield-halved"></i> Pagamento seguro</span>
                <span><i class="fa-solid fa-lock"></i> Dados criptografados</span>
                <span><i class="fa-solid fa-rotate-left"></i> Cancele quando quiser</span>
            </div>
        </div>

        {{-- Right: Order Summary --}}
        <div class="checkout__summary">
            <div class="summary-card">
                <h2 class="summary-card__title">Resumo do pedido</h2>

                <div class="summary-card__plan">
                    <div class="summary-card__plan-icon">
                        <i class="fa-solid fa-{{ $plan['icon'] }}"></i>
                    </div>
                    <div>
                        <strong>Plano {{ $plan['name'] }}</strong>
                        <span>Assinatura mensal</span>
                    </div>
                </div>

                <ul class="summary-card__features">
                    @foreach($plan['features'] as $feature)
                        <li><i class="fa-solid fa-check"></i> {{ $feature }}</li>
                    @endforeach
                </ul>

                <div class="summary-card__divider"></div>

                <div class="summary-card__line">
                    <span>Subtotal</span>
                    <span>{{ $plan['price'] }}</span>
                </div>
                <div class="summary-card__line">
                    <span>Desconto</span>
                    <span class="summary-card__discount">- R$ 0,00</span>
                </div>
                <div class="summary-card__divider"></div>
                <div class="summary-card__line summary-card__line--total">
                    <span>Total</span>
                    <span>{{ $plan['price'] }}</span>
                </div>

                <p class="summary-card__recurrence">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    Renovação automática mensal. Cancele a qualquer momento.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Success Modal --}}
<div class="pay-modal" id="payModal">
    <div class="pay-modal__content">
        <div class="pay-modal__icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h2 class="pay-modal__title">Pagamento confirmado!</h2>
        <p class="pay-modal__desc">
            Sua assinatura do plano <strong>{{ $plan['name'] }}</strong> foi ativada com sucesso.
            <br>Este é um ambiente de demonstração.
        </p>
        <a href="{{ route('dashboard') }}" class="pay-modal__btn">
            <i class="fa-solid fa-arrow-right"></i> Ir para o Dashboard
        </a>
    </div>
</div>

<script>
    // Tab switching
    document.querySelectorAll('.pay-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('pay-tab--active'));
            document.querySelectorAll('.pay-panel').forEach(p => p.classList.remove('pay-panel--active'));
            tab.classList.add('pay-tab--active');
            document.getElementById('panel-' + tab.dataset.tab).classList.add('pay-panel--active');
        });
    });

    // Copy PIX code
    function copyPix() {
        const code = document.getElementById('pixCode');
        code.select();
        document.execCommand('copy');
        const fb = document.getElementById('pixFeedback');
        fb.classList.add('is-visible');
        setTimeout(() => fb.classList.remove('is-visible'), 2000);
    }

    // PIX timer countdown
    (function() {
        let seconds = 1799;
        const timer = document.getElementById('pixTimer');
        setInterval(() => {
            if (seconds <= 0) return;
            seconds--;
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            timer.textContent = m + ':' + s;
        }, 1000);
    })();

    // Card number formatting
    document.getElementById('cardNumber')?.addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '').substring(0, 16);
        e.target.value = v.replace(/(.{4})/g, '$1 ').trim();
    });

    // Expiry formatting
    document.getElementById('cardExpiry')?.addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '').substring(0, 4);
        if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2);
        e.target.value = v;
    });

    // CPF formatting
    document.getElementById('cardCpf')?.addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '').substring(0, 11);
        if (v.length > 9) v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
        else if (v.length > 6) v = v.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
        else if (v.length > 3) v = v.replace(/(\d{3})(\d{1,3})/, '$1.$2');
        e.target.value = v;
    });

    // Fake payment → show success modal
    function fakePayment() {
        const modal = document.getElementById('payModal');
        modal.classList.add('is-visible');
    }

    // Close modal on outside click
    document.getElementById('payModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('is-visible');
    });
</script>
@endsection
