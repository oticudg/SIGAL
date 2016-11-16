var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.less('app.less');
  	mix.scripts(['config.js'], 'public/js/config.js');  
    mix.scriptsIn(['resources/assets/js/controllers/'], 'public/js/deposito.js');
});

