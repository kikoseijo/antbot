<p align="center" style="margin-top:40px;">
<img src="https://raw.githubusercontent.com/kikoseijo/antbot/master/public/img/svg/ant-logo-yellow.svg" width="100" alt="Antbot Logo">
</p>

Antbot
======

This open source project comes from the need to run several [Passivbot](https://www.passivbot.com/) ***"A fully automated trading bot"*** on same server, with multiple exchanges with infinite trading accounts & APIs.

A Multi-tenancy architecture allows you to have your friends and family running their own crypto trading bots without any knowledge of Shell or Programming Skills because everything its database driven & managed trough a website.

At same time, this application its able to monitor and interact with the Python crypto bots and comes with exchange scrapper thats able to pull your trades historic data and give you full control over your assets, incomes & bot performance.

The application its developed using the [TALL stack](https://tallstack.dev/), uses Tailwind, Alpine.js, Laravel, and Livewire to achieve a Reactive full-stack solution.

***Important notes:***

- It's a Beta version.
- It's under -[heavy] development.
- You need a knowledge installing Laravel + Installing Python applications and environments.

## Roadmap

New features can be easily achieve with little effort, being based on a full stack for fast development.
Many features can be implemented but the main ones right now are the following:

- [X] Bybit exchange scrapper.
- [ ] Binance exchange scrapper.
- [ ] Bitget exchange scrapper.
- [ ] OKX exchange scrapper.
- [ ] Grid editor with live visualisation in a chart.
- [ ] Export trading records for financial storage purpose.
- [ ] ...much more to come.

## Installation

Its Laravel, please follow any of the methods to run the application from this guide: [Laravel Installation with docker](https://laravel.com/docs/9.x/installation#laravel-and-docker)

## Configuration

After installing and configure Laravel, its time to install and link Passivbot.

### Install Passivbot

Download [Passivbot](https://www.passivbot.com/) and install python requirements to run Passivbot, then update `config/antbot.php` with the
full path.

```php
'paths' => [
    'bot_path' => '/path/to/passivbot',
    'logs_path' => '/path/to/logs-folder',
],
```

### Configure grid system

Passivbot comes with 3 [run modes](https://www.passivbot.com/en/latest/passivbot_modes/), its best starting point for someone new to Passivbot.
You can leave this settings as default, just make sure the files linked are available inside Passivbot installation.
You will also be able to create and edit your own grids directly from the control panel.

```php
'grid_configs' => [
    'recursive' => 'configs/live/recursive_grid_mode.example.json',
    'neat' => 'configs/live/neat_grid_mode.example.json',
    'static' => 'configs/live/static_grid_mode.example.json',
],
```

### Configure timezone

This is a Laravel straight forward configuration you can find inside `config/app.php`.

### Cronjob

In order to keep bots running in case server reboot, retrieve exchange balance, postions and trades history, its necessary to create a CronJob to run every minute.

Here its an example of the command:

```bash
* * * * * cd /path/to/antbot && /opt/remi/php81/root/usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

Screenshots
-----------

<img src="https://raw.githubusercontent.com/kikoseijo/antbot/master/public/img/screenshots/exchange-dashboard.png" width="800" alt="Exchanges dashboard">
<img src="https://raw.githubusercontent.com/kikoseijo/antbot/master/public/img/screenshots/bots.png" width="800" alt="Crypto trading bots">
<img src="https://raw.githubusercontent.com/kikoseijo/antbot/master/public/img/screenshots/grids.png" width="800" alt="Trading grid modes">
<img src="https://raw.githubusercontent.com/kikoseijo/antbot/master/public/img/screenshots/records.png" width="800" alt="Exchange trading records">
<img src="https://raw.githubusercontent.com/kikoseijo/antbot/master/public/img/screenshots/symbols.png" width="800" alt="Exchange available trading Symbols">

Installation support
--------------------

If you need help installing the application I'm able to provide you with my professional services, just give me a shout.
I'll be pleased to do the job for you, choose the right VPS/Cloud Server or hosting provider around the world.

Contributing
------------

Thank you for considering contributing to the Antbot system!
The contribution guide its not jet released, but you can always push a new commit with what you can think of new functionality or fixing bugs.

Security Vulnerabilities
------------------------

If you discover a security vulnerability, please send an e-mail to Kiko Seijo via [kikopc@gmail.com](mailto:kikopc@gmail.com). All security vulnerabilities will be promptly addressed.

License
-------

The Antbot is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Credits
-------

* Kiko Seijo [Sunnyface.com](https://sunnyface.com 'Laravel, React, freelancer in Málaga')

Sunnyface.com, is a software development company from Málaga, Spain. We provide quality software based on the cloud for local & international companies, providing technology solutions with the latest [programming languages](https://sunnyface.com/tecnologia/ 'Programador experto react y vue en Málaga').

Special thanks: to supporters and clients that allows us contributing back to the WWW community.

[DevOps](https://sunnyface.com 'Programador ios málaga Marbella') Web development  


---

<div dir=rtl markdown=1>Created by <b>Kiko Seijo</b></div>
