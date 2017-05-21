var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.less('app.less');
  	mix.scripts('config.js', 'public/js/config.js');
    mix.scriptsIn('resources/assets/js/controllers/', 'public/js/deposito.js');
    mix.version(['js/deposito.js', 'js/config.js', 'css/app.css']);
});
