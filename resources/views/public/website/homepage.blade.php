@extends('public.website.layout.layout')

@section('content')
    <div class="main">
        <div class="section-one">
            <!-- Blurred image inside section one -->
            <div class="section-one-image">
                <img src="{{ asset('files/images/background.jpg') }}" alt="">
            </div>
            <div class="section-one-shadow-wrapper">
                <div class="section-one-inner-wrapper">
                    <div class="section-one-upper-info">
                        <div class="section-one-upper-info-1">
                            <img src="{{ asset('files/images/Colors Logo.png') }}" alt="">
                        </div>
                        <div class="section-one-upper-info-2">
                            <i class="fas fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="section-one-middle-info">
                        <div class="section-one-middle-info1">
                            <h4>{{ __(' ZA SVE KOJI VOLE FUDBAL / NOGOMET ')}}</h4>
                        </div>
                        <div class="section-one-middle-info2">
                            <h1> <span style="">TVOJA EKIPA</span>
                                <span style="color: white;">TE TREBA</span><br>
                                <span style="color: white;">NEKA SE ČUJE </span>
                                TVOJ GLAS
                            </h1>
                        </div>
                        <div class="section-one-middle-info3">
                            <h3>{{ __('Budi dio najvažnije sporedne aplikacije na svijetu.')}}</h3>
                            <h3>{{ __('Odaberi svoje navijačke boje, poveži se sa svojom grupom,')}}</h3>
                            <h3>{{ __('daj svoj doprinos i proširi navijački angažman.')}}</h3>

                        </div>
                        <div class="section-one-middle-info4">
                            <button>
                                <img src="{{ asset('files/images/google-play.png') }}" alt="">
                            </button>
                            <button><img src="{{ asset('files/images/app-store.png') }}" alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-two">
            <div class="section-two-countries">
                <div class="section-two-text">
                    <h3>{{ __('Aplikacija je namijenjena navijačima i simpatizerima')}}</h3>
                    <h3>{{ __('fudbalskih / nogometnih reprezentacija i ligaških timova iz')}}</h3>
                </div>
                <div class="section-two-img">
                    <img src="{{ asset('files/images/Zastave.png') }}" alt="">
                </div>
            </div>
        </div>

        <!-- Two phones -->
        <div class="section-three">
            <div class="st__inner_w">
                <div class="st__left st__inner">
                    <div class="st__left__text_w">
                        <h2>{{ __('KOJE SU TVOJE BOJE?') }}</h2>
                        <h3>
                            {{ __('IZABERI JEDNU NACIONALNU') }}
                            <span>{{ __('REPREZENTACIJU') }}</span>
                            {{ __('I JEDAN') }}
                            <span>{{ __('LIGAŠKI TIM') }}</span>
                            {{ __('ČIME ĆEŠ ODREDITI SVOJE BUDUĆE ISKUSTVO U APLIKACIJI') }}
                        </h3>
                    </div>
                    <div class="st__left__text_w st__lt__ar">
                        <h3>{{ __('Jezici aplikacije') }}</h3>
                        <h4>{{ __('albanski') }}</h4>
                        <h4>{{ __('bosanski') }}</h4>
                        <h4>{{ __('crnogorski') }}</h4>
                        <h4>{{ __('engleski') }}</h4>
                        <h4>{{ __('hrvatski') }}</h4>
                        <h4>{{ __('makedonski') }}</h4>
                        <h4>{{ __('slovenački') }}</h4>
                        <h4>{{ __('srpski') }}</h4>
                    </div>

                    <div class="st__left__text_w st__lt__btns">
                        <a href="#">
                            <button>
                                <img src="{{ asset('files/images/google-play.png') }}" alt="">
                            </button>
                        </a>
                        <a href="#">
                            <button><img src="{{ asset('files/images/app-store.png') }}" alt="">
                            </button>
                        </a>
                    </div>
                </div>
                <div class="st__right st__inner">
                    <img src="{{ asset('files/images/website/phones.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
