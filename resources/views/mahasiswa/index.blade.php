<!DOCTYPE html>
<html lang="en">

@include('mahasiswa.css')

<body class="nav-fixed">
    @include('mahasiswa.nav')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('mahasiswa.sidenav')

        </div>
        <div id="layoutSidenav_content">
            @include('mahasiswa.content')
            @include('mahasiswa.footer')


        </div>
    </div>
    @include('mahasiswa.script')

</body>

</html>
