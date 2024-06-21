<div class="content-menu">
    <div class="page-menu-icon-w">
        @yield('c-icon')
    </div>

    <div class="page-menu-content">
        <div class="pmc-left">
            <div class="pmc-left-content">
                <h1> @yield('c-title') </h1>
                <div class="pmc-lc-breadcrumbs">
                    @yield('c-breadcrumbs')
                </div>
            </div>
        </div>

        <div class="pmc-right">
            <div class="pmc-right-content">
                @yield('c-buttons')
            </div>
        </div>
    </div>
</div>
