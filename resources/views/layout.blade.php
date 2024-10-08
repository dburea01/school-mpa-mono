<!doctype html>
<html lang="{{ app()->getLocale() }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="template admin bootstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="img/carre_vert_48_48.png" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="d-flex flex-column h-100">

    <nav class="navbar navbar-expand-md navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><x-logo /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-header">
                    <a class="navbar-brand" href="/"><x-logo /></a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">

                    <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
                        @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->photo_url)
                                <img style="height: 20px;" src="{{ Auth::user()->photo_url }}" alt="image not found" class="border">
                                @else
                                <i class="bi bi-person"></i>
                                @endif
                                {{ Auth::user()->full_name }} ( {{ Auth::user()->role->name }} )
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Se déconnecter</a></li>
                                <li><a class="dropdown-item" href="#">Mon profil (@todo)</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @if(Auth::user()->provider === 'local')
                                <li><a class="dropdown-item" href="{{ route('change-password', ['user' => Auth::user()]) }}">Modifier mot passe</a></li>
                                @endif
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menu
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                                @can('viewAny', App\models\User::class)<li><a class="dropdown-item" href="{{ route('users.index') }}">Liste utilisateurs</a></li>@endcan
                                @can('viewAny', App\models\Group::class)<li><a class="dropdown-item" href="{{ route('groups.index') }}">Liste groupes</a></li>@endcan
                                @can('viewAny', App\models\Period::class)<li><a class="dropdown-item" href="{{ route('periods.index') }}">Liste périodes</a></li>@endcan
                                @can('viewAny', App\models\Subject::class)<li><a class="dropdown-item" href="{{ route('subjects.index') }}">Liste matières</a></li>@endcan
                                @can('viewAny', App\models\WorkType::class)<li><a class="dropdown-item" href="{{ route('work-types.index') }}">Liste types de travail</a></li>@endcan
                                @can('viewAny', App\models\Appreciation::class)<li><a class="dropdown-item" href="{{ route('appreciations.index') }}">Liste appreciations</a></li>@endcan

                                @php
                                $periodRepository = new App\Repositories\PeriodRepository();
                                $currentPeriod = $periodRepository->getCurrentPeriod();

                                $userGroupRepository = new App\Repositories\UserGroupRepository();
                                $studentsOfTheConnectedUser = $userGroupRepository->getStudentsOfParent(Auth::user());
                                @endphp
                                @if($currentPeriod)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item disabled" aria-disabled="true">{{ $currentPeriod->name }}</a>
                                </li>
                                @can('viewAny', App\models\Classroom::class)
                                <li>
                                    <a class="dropdown-item" href="{{ route('classrooms.index', ['period' => $currentPeriod->id ]) }}">Liste classes</a>
                                </li>
                                @endcan

                                @can('viewAny', App\models\Work::class)
                                <li>
                                    <a class="dropdown-item" href="{{ route('works.index', ['period' => $currentPeriod->id]) }}">Liste travaux</a>
                                </li>
                                @endcan

                                {{-- only if the connected user is a PARENT : find the students linked to the connected user --}}
                                @if(Auth::user()->role_id == 'PARENT')
                                @php
                                $userGroupRepository = new App\Repositories\UserGroupRepository();
                                $studentsOfTheParent = $userGroupRepository->getStudentsOfParent(Auth::user());
                                @endphp
                                @foreach($studentsOfTheParent as $student)
                                <li>
                                    <a class="dropdown-item" href="{{ route('resultsByUser', ['user' => $student->id]) }}">Résultats de {{ $student->last_name }} {{ $student->first_name }}</a>
                                </li>
                                @endforeach
                                @endif
                                {{-- end of the PARENT menu section --}}

                                {{-- only if the connected user is a STUDENT --}}
                                @if(Auth::user()->role_id == 'STUDENT')
                                <li>
                                    <a class="dropdown-item" href="{{ route('resultsByUser', ['user' => Auth::user()->id]) }}">Résultats de {{ Auth::user()->full_name }}</a>
                                </li>
                                @endif
                                {{-- end of the STUDENT menu section --}}

                                @endif
                                {{-- end of the section depending on a current period --}}
                            </ul>
                        </li>


                        <li class="navbar-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <x-display-current-period />
                        </li>
                        @endauth

                        @guest
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Se connecter</a>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-shrink-0 p-3">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto py-1">
        <div class="container">
            <span>
                <a href="/the-author">&copy; Dom</a> |
                <a href="/contact-author">Contact</a> |
                <a href="/about">A propos</a> |
                <a href="/tos">CGU</a>
            </span>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    @yield('extra_js')
</body>

</html>