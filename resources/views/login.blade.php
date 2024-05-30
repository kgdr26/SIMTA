<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Login</title>
</head>
<body class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
        <div class="container py-5">
            <div class="w-50 center border rounded px-3 py-3 mx-auto">
                <div class="container py-5">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ ('pictures/Logo_UPER.png') }}" alt="Logo_UPER" style="height: 250px">
                            <img src="{{ ('pictures/Logo_HMIK.png') }}" alt="Logo_HMIK" style="height: 200px">
                        </div>
                    <h3 class="text-center">Sistem Informasi Monitoring Tugas Akhir</h3>
                </div>
                
                <h1 class="text-center">Login</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" type="text" value="{{ old('username') }}" name="username" class="form-control" required autofocus>
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 d-grid">
                        <button name="submit" type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
        </div> 
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <p>&copy; 2024 Naliyadivani</p>
                    </div>
                </div>
            </div>
        </footer>
    
</body>
</html>