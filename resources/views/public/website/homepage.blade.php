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
                                <img src="{{ asset('files/images/Google play.png') }}" alt="">
                            </button>
                            <button><img src="{{ asset('files/images/App Store.png') }}" alt="">
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
    </div>


@endsection
