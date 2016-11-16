
<div id="login" class="col-md-4 col-md-offset-4">
    
    <h1 id="formTitle">Inicio de Sesión</h1>
    <hr>        
   @if($errors->has())
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{$errors->first()}}
        </div>
   @endif
    
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form method="POST" action="/auth/login">
                {!! csrf_field() !!}
                
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon text-white btn-success"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input class="form-control" type="email" name="email" placeholder="Correo electronico" value="{{ old('email') }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon text-white btn-success"><span class="glyphicon glyphicon-lock"></span></span>
                        <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña">
                    </div>
                </div>

                <div>
                    <button class="btn btn-success btn-block" type="submit">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</div>
