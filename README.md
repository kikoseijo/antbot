<p align="center">
<img src="https://raw.githubusercontent.com/kikoseijo/antbot/master/public/img/svg/ant-logo-yellow.svg" width="100" alt="Antbot Logo">
</p>

# Antbot

An UI manager for running crypto bots like [Passivbot](https://www.passivbot.com/) with an exchange scrapper, currently ***(only Bybit its implemented)***.

- Beta version.
- Under heavy development.
- Its not been tested.
- There is no documentation.

## Installation

Its Laravel, please follow the guides here: [Laravel Installation with docker](https://laravel.com/docs/9.x/installation#laravel-and-docker)

## Configuration

Install [Passivbot](https://www.passivbot.com/) as you will normally do and configure the full path on `config/antbot.php`.

```
'paths' => [
    'bot_path' => '/home/antbot/passivbot',
    'logs_path' => '/home/antbot/klogs',
],
```

Create your defaults grids and update the configuration file with your own ones.

```
'grid_configs' => [
    'recursive' => 'configs/live/recursive.json',
    'neat' => 'configs/live/neat.json',
    'static' => 'configs/live/static.json',
],
```

Configure timezone under `config/app.php`.


## Contributing

Thank you for considering contributing to the Antbot! The contribution guide its not jet released, but you can always push a new commit with any new functionality.



## Security Vulnerabilities

If you discover a security vulnerability, please send an e-mail to Kiko Seijo via [kikopc@gmail.com](mailto:kikopc@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Antbot is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
