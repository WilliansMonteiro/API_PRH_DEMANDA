@extends('layout.main')


@section('content')
<style>
    .body {
        width: 100%;
        height: 974px;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 100% 100%;
        background-image: url({{ asset('img/avaliacao/pagina_login.jpg') }});
    }
    img .body {
        opacity: 0.5;
    }


    
</style>
<div class = "body">
    <section class = "content-header">
        <div class = "container-fluid">
            <div class = "row mb-2">
                <div class = "col-sm-6">

                </div>
                <div class = "col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class = "content">
            <div class = "row justify-content-center ">
                <div class = "col-md-4" style= "opacity: 1"; >
                    <div class = "card card-outline card-primary" >
                        <div class = "card-header">{{ __('Login') }}</div>
                        
                        @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Erros encontrados!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class = "card-body">
                            <form id="login-interno" method = "POST" action = "{{ route('login') }}">
                                <input type="hidden" id="hashLogin" value="{{ $hashLogin}}">
                                <input type="hidden" name="hashGerado" id="hashGerado" value="">
                                @csrf

                                <div class = "form-group row" style= "opacity: 1";>
                                    <label for = "nr_matricula"
                                            class = "col-md-4 col-form-label text-md-right">Matrícula</label>

                                    <div class = "col-md-6">
                                        <input id = "nr_matricula" type = "text" class = "form-control"
                                                readonly = "readonly" name = "nr_matricula"
                                                value = "{{ $matriculaUsuario }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                <input id="codigo" type="password" class="form-control @error('codigo') is-invalid @enderror" name="codigo" required autocomplete="current-password">

                                @error('codigo')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                </div>
                                </div>

                                

                                <div class = "form-group row mb-0">
                                    <div class = "col-md-8 offset-md-4">
                                        <button type = "submit" class = "btn btn-primary">
                                            {{ __('Login') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <script type="text/javascript">

        var hashCodigo = function(event) {
    
            hashAsync().then(function (hash) {
                console.log(hash)
                document.getElementById("codigo").disabled = true;
                document.getElementById('hashGerado').value = hash;
                document.getElementById("login-interno").submit()
            });
    
            event.preventDefault()
    
        };
    
        var form = document.getElementById("login-interno");
    
        form.addEventListener("submit", hashCodigo, true);
    
        function hashAsync() {
            return new Promise(function (resolve) {
                let codigo = document.getElementById('hashLogin').value
                console.log(codigo);
                let encrypted = CryptoJSAesJson.encrypt(document.getElementById('codigo').value, codigo)
                resolve(encrypted)
    
            })
        }
    
    </script>
@endsection
