{{--<div class="s-top-menu">--}}
{{--    <div class="logo-part">--}}
{{--        <img src="{{ asset('files/images/default/ekipa.png') }}" alt="">--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="s-left-menu">--}}
{{--    <div class="s-lm-subheader">--}}
{{--        <h5>{{ __('Grafičko sučelje') }}</h5>--}}
{{--        <img src="{{ asset('files/images/svg-icons/chart-area-solid.svg') }}" alt="">--}}
{{--    </div>--}}
{{--    <div class="s-lm-row-wrapper">--}}
{{--        <a href="#">--}}
{{--            <div class="s-lm-rw-inline-row">--}}
{{--                <div class="s-lm-rw-ir-left">--}}
{{--                    <img src="{{ asset('files/images/svg-icons/house-chimney-solid.svg') }}" alt="">--}}
{{--                    <p>{{ __('Dashboard') }}</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </a>--}}
{{--    </div>--}}

{{--    <div class="s-lm-row-wrapper">--}}
{{--        <a href="#">--}}
{{--            <div class="s-lm-rw-inline-row">--}}
{{--                <div class="s-lm-rw-ir-left">--}}
{{--                    <img src="{{ asset('files/images/svg-icons/house-chimney-solid.svg') }}" alt="">--}}
{{--                    <p>{{ __('Dashboard') }}</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </a>--}}
{{--    </div>--}}

{{--    <div class="s-lm-user-wrapper">--}}
{{--        <div class="lm-u-img-w">--}}
{{--            <img src="{{ asset('files/images/default/sparrow.webp') }}" alt="">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


<div class="s-top-menu">
    <div class="app-name">
        <a title="{{__('Homepage')}}">
            <img src="{{ asset('files/images/logo.png') }}" alt="">
        </a>
        <i class="fas fa-bars t-3 system-m-i-t" title="{{__('Otvorite / zatvorite MENU')}}"></i>
    </div>

    <div class="top-menu-links">
        <!-- Left top icons -->
        <div class="left-icons">
{{--            <div class="single-li">--}}
{{--                <a href="{{route('public-part.shop.cart.cart-preview')}}" target="_blank" title="{{__($inCart.' artikal/a u košarici')}}">--}}
{{--                    <i class="fas fa-shopping-cart"></i>--}}
{{--                    <div class="number-of"><p>{{$inCart}}</p></div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="single-li" title="Odaberite jezik">--}}
{{--                <i class="fas fa-globe-americas"></i>--}}
{{--                <div class="number-of"><p>3</p></div>--}}
{{--            </div>--}}

            <a href="#" target="_blank">
                <div class="single-li">
                    <p> {{__('Web site')}} </p>
                </div>
            </a>

            <a href="#">
                <div class="single-li">
                    <p> {{__('Blog section')}} </p>
                </div>
            </a>
        </div>

        <!-- Right top icons -->
        <div class="right-icons">
            <div class="single-li main-search-w" title="">
                <i class="fas fa-search main-search-t" title="{{__('Search')}}"></i>
{{--                @include('system.template.menu.menu-includes.search')--}}
            </div>
            <div class="single-li m-show-notifications" title="Pregled obavijesti">
                <i class="fas fa-bell"></i>
                <div class="number-of"><p id="no-unread-notifications">12</p></div>

{{--                @include('system.template.menu.menu-includes.notifications')--}}
            </div>
            <div class="single-li main-search-w" title="">
                <a href="{{ route('public.auth.logout') }}">
                    <i class="fas fa-power-off" title="{{__('Sign out')}}"></i>
                </a>
            </div>
            <a href="{{ route('admin.users.my-profile') }}">
                <div class="single-li user-name">
                    <p><b> {{ Auth()->user()->name }} </b></p>
{{--                    {!! Form::hidden('user_id', json_encode($loggedUser), ['class' => 'form-control', 'id' => 'loggedUser']) !!}--}}
                </div>
            </a>
        </div>
    </div>
</div>

<!--------------------------------------------------------------------------------------------------------------------->

<div class="s-left-menu t-3">
    <!-- user Info -->
    <div class="user-info">
        <div class="user-image">
            <img class="mp-profile-image" title="{{__('Change profile picture')}}" src="{{ isset($loggedUser->profileImgRel) ? asset( $loggedUser->profileImgRel->getFile()) : asset('files/images/user.png')}}" alt="">
{{--            <img class="mp-profile-image" title="{{__('Promijenite sliku profila')}}" src="{{ isset($loggedUser->profileImgRel) ? asset( $loggedUser->profileImgRel->getFile()) : asset('images/user.png')}}" alt="">--}}
        </div>
        <div class="user-desc">
            <h4> {{ Auth()->user()->name }} </h4>
            <p> {{ Auth()->user()->role }} </p>
            <p>
                <i class="fas fa-circle"></i>
                Online
            </p>
        </div>
    </div>
    <hr>

    <!-- Menu subsection -->
    <div class="s-lm-subsection">

        <div class="subtitle">
            <h4>{{__('Graphical User Interface')}}</h4>
            <div class="subtitle-icon">
                <i class="fas fa-chart-area"></i>
            </div>
        </div>
        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-home"></i>
                    </div>
                    <p>{{__('Dashboard')}}</p>
                    <div class="extra-elements">
                        <div class="ee-t ee-t-b"><p>{{__('Graph')}}</p></div>
                    </div>
                </div>
                <div class="inside-links active-links">
                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Dashboard')}}</p>
                        </div>
                    </a>

                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p> {{__('Kalendar')}} </p>
                        </div>
                    </a>
                </div>
            </div>
        </a>

        <div class="subtitle">
            <h4>{{__('User interface')}}</h4>
            <div class="subtitle-icon">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>

        <a href="{{ route('admin.users.my-profile') }}" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <p>{{__('My profile')}}</p>
                    <div class="extra-elements">
                        <div class="ee-t ee-t-g"><p>{{__('Info')}}</p></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Only for admins -->
        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-users"></i>
                    </div>
                    <p>{{__('Users')}}</p>
                    <div class="extra-elements">
                        <div class="rotate-element"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="inside-links active-links">
                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Add new')}}</p>
                            <div class="additional-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Preview all')}}</p>
                        </div>
                    </a>
                </div>
            </div>
        </a>

        <div class="subtitle">
            <h4>{{__('System core')}}</h4>
            <div class="subtitle-icon">
                <i class="fas fa-app"></i>
            </div>
        </div>

        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <p>{{__('Clubs')}}</p>
                    <div class="extra-elements">
                        <div class="rotate-element"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="inside-links active-links">
                    <a href="{{ route('admin.core.clubs.create') }}">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Add new')}}</p>
                            <div class="additional-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.core.clubs') }}">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Preview all')}}</p>
                        </div>
                    </a>

                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Venues')}}</p>
                        </div>
                    </a>
                </div>
            </div>
        </a>
        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-feather"></i>
                    </div>
                    <p>{{__('Leagues')}}</p>
                    <div class="extra-elements">
                        <div class="rotate-element"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="inside-links active-links">
                    <a href="{{ route('admin.core.league.create') }}">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Add new')}}</p>
                            <div class="additional-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.core.league') }}">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Preview all')}}</p>
                        </div>
                    </a>
                </div>
            </div>
        </a>
        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-wind"></i>
                    </div>
                    <p>{{__('Seasons')}}</p>
                    <div class="extra-elements">
                        <div class="rotate-element"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="inside-links active-links">
                    <a href="{{ route('admin.core.seasons.create') }}">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Add new')}}</p>
                            <div class="additional-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.core.seasons') }}">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Preview all')}}</p>
                        </div>
                    </a>
                </div>
            </div>
        </a>
{{--        <div class="subtitle">--}}
{{--            <h4> {{__('Historija kupovanja')}} </h4>--}}
{{--            <div class="subtitle-icon">--}}
{{--                <i class="fas fa-history"></i>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <a href="#" class="menu-a-link">--}}
{{--            <div class="s-lm-wrapper">--}}
{{--                <div class="s-lm-s-elements">--}}
{{--                    <div class="s-lms-e-img">--}}
{{--                        <i class="far fa-file-alt"></i>--}}
{{--                    </div>--}}
{{--                    <p>{{__('Moje narudžbe')}}</p>--}}
{{--                    <div class="extra-elements">--}}
{{--                        <div class="rotate-element"><i class="fas fa-angle-right"></i></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="inside-links active-links">--}}
{{--                    <a href="#">--}}
{{--                        <div class="inside-lm-link">--}}
{{--                            <div class="ilm-l"></div><div class="ilm-c"></div>--}}
{{--                            <p>{{__('Pregled svih narudžbi')}}</p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a href="">--}}
{{--                        <div class="inside-lm-link">--}}
{{--                            <div class="ilm-l"></div><div class="ilm-c"></div>--}}
{{--                            <p> {{__('Uputstva za korištenje')}} </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </a>--}}

        <div class="subtitle">
            <h4> {{__('Postavke')}} </h4>
            <div class="subtitle-icon">
                <i class="fas fa-cogs"></i>
            </div>
        </div>
        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-key"></i>
                    </div>
                    <p>{{__('Šifarnici')}}</p>
                </div>
            </div>
        </a>

        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-question"></i>
                    </div>
                    <p>{{__('FAQ')}}</p>
                    <div class="extra-elements">
                        <div class="rotate-element"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="inside-links active-links">
                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Pregled svih pitanja')}}</p>
                        </div>
                    </a>

                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p> {{__('Unos novih pitanja')}} </p>
                        </div>
                    </a>
                </div>
            </div>
        </a>

        <a href="#" class="menu-a-link">
            <div class="s-lm-wrapper">
                <div class="s-lm-s-elements">
                    <div class="s-lms-e-img">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <p>{{__('Stranice')}}</p>
                    <div class="extra-elements">
                        <div class="rotate-element"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="inside-links active-links">
                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p>{{__('Pregled svih stranica')}}</p>
                        </div>
                    </a>

                    <a href="#">
                        <div class="inside-lm-link">
                            <div class="ilm-l"></div><div class="ilm-c"></div>
                            <p> {{__('Unos novog sadržaja')}} </p>
                        </div>
                    </a>
                </div>
            </div>
        </a>
    </div>

{{--    @include('system.template.menu.menu-includes.bottom-icons')--}}
</div>


{{--<!-- Upload an image for user account -->--}}
{{--@include('system.template.menu.menu-includes.profile-image')--}}
