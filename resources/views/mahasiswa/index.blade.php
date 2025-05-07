<!DOCTYPE html>
<html lang="en">

@include('template.css')

<body class="nav-fixed">
    @include('template.nav')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('template.sidenav')

        </div>
        <div id="layoutSidenav_content">
            @include('template.content')
            @include('template.footer')


        </div>
    </div>
    @include('template.script')

</body>

</html>
