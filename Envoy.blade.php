@servers(['remote' => 'ba', 'local' => '127.0.0.1'])

@include('vendor/autoload.php')

@setup
    # customize this keys...
    # USERNAME_HERE, DOMAIN_NAME_HERE, sunny, REPO_NAME
    #   $dotenv = Dotenv\Dotenv::create(__DIR__, '.env');
    #   $dotenv->load();

    $now = date('Ymd-His');
    $branch = "origin/master";
    $username = "antbot";
    $key_email = "antbot@sunnyface.com";
    $repo_domain = "gitlab.com";
    $repo_group = "sunnyface";
    $repo_name = "antbot";
    $project_root = "/home/$username/$repo_name";
    $domain = 'antbot.sunnyface.com';
    $slack = env('SLACK_ENDPOINT');
    $prepare_php = "scl enable php81 bash";
    $crons = [
        'Laravel Schedule' => [
            '* * * * *',
            "/opt/remi/php80/root/usr/bin/php $project_root/artisan schedule:run >> /dev/null 2>&1"
        ]
    ];
    $alias = [
        'alias php="/opt/remi/php80/root/usr/bin/php"',
        'alias composer="php /bin/composer"',
        'alias ka="php artisan"',
        'alias kdump="composer dump-autoload -o"',
        'alias klean="ka clear-compiled && ka migrate:refresh && ka db:seed"',
    ];
    $envs = [
        'SLACK_ENDPOINT="https://hooks.slack.com/services/XXX/XX/XXX"',
    ];
@endsetup



@task('php', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    php -v
    alias
@endtask

@story('upgrade_php')
    yum install php81 php81-php-mysqlnd php81-php-mbstring php81-php-fpm php81-php-xml php81-php-gd php81-php-soap php81-php-bcmath php81-php-pecl-zip
@endstory

@story('doall')
    build_production
    deploy
    build_dev
@endstory

@task('build_production', ['on' => 'local'])
    yarn prod
    git add .
    git commit -m "Commit before deploy {{$now}}"
    git push -u origin master
@endtask

@task('build_dev', ['on' => 'local'])
    yarn dev
@endtask



@task('deploy', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan down --retry=10
    git fetch --all
    git reset --hard {{ $branch }}
    git pull origin master
    @if ($u)
        composer update --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs
    @else
        composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs
    @endif
    @if ($m)
        php artisan migrate --force
    @endif
    php artisan config:clear
    php artisan config:cache
    php artisan view:clear
    php artisan view:cache
    php artisan up
@endtask

@task('update', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan down --retry=10
    composer update --no-dev --prefer-dist
    php artisan config:clear
    php artisan config:cache
    php artisan view:clear
    php artisan view:cache
    php artisan up
@endtask

@task('passport', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan passport:keys
    php artisan passport:install
    # php artisan vendor:publish --tag=passport-components
@endtask

@task('composer', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan down --retry=10
    composer install --no-dev --prefer-dist
    php artisan up
@endtask

@task('ssh', ['on' => 'remote', 'confirm' => true])
    su -l {{ $username }}
    {{ $prepare_php }}
    [ -d ~/.ssh ] || echo "~/.ssh directory does not exist, lets create"
    [ -d ~/.ssh ] || mkdir ~/.ssh
    echo "Adding {{ $repo_domain }} domain to known_hosts"
    ssh-keyscan {{ $repo_domain }} >> ~/.ssh/known_hosts
    cd ~/.ssh
    echo "Deleting ssh keys..."
    rm -rf id_rsa id_rsa.pub
    ssh-keygen -t rsa -C "{{ $repo_domain }}" -b 4096  -N "" -f id_rsa
    cat  ~/.ssh/id_rsa.pub
@endtask

@story('setup')
    download
    install
@endstory

@task('download', ['on' => ['remote']])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~
    echo "Cloning {{ "git@" . $repo_domain .":". $repo_group ."/" . $repo_name . ".git" }}"
    git clone {{ "git@" . $repo_domain .":". $repo_group ."/" . $repo_name . ".git" }}
    cd ~/{{ $repo_name }}
    echo "Done download"
@endtask

@task('install', ['on' => ['remote']])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    composer install --no-dev
    [ -f .env ] || echo ".env does not exist, using default .env.example"
    [ -f .env ] || cp .env.example .env
    echo "Generating new KEY for .env"
    php artisan key:generate
    echo "Linking storage folder"
    php artisan storage:link
@endtask

@task('alias', ['on' => ['remote']])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~
    @foreach ($alias as $alia)
        LINE='{{$alia}}'
        FILE=.profile
        grep -qF "$LINE" "$FILE" || echo "$LINE" >> "$FILE"
    @endforeach
    alias
    echo "Done."
@endtask

@task('add_env', ['on' => ['remote']])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    @foreach ($envs as $env)
        LINE='{{$env}}'
        FILE=.env
        grep -qF "$LINE" "$FILE" || echo "$LINE" >> "$FILE"
    @endforeach
    cat .env
    echo "Done."
@endtask

@task('publish', ['on' => ['remote']])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~
    rm -rf public_html
    ln -s ~/{{ $repo_name }}/public ~/public_html
    echo "App should be now live."
@endtask

@task('cron_add', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    echo "# write out current crontab"
    @foreach ($crons as $cron_name => $cron_job)
        echo "{{$cron_job[1]}}"
        croncmd="{{$cron_job[1]}}"
        cronjob="{{$cron_job[0]}} $croncmd"
        ( crontab -l | grep -v -F "$croncmd" ; echo "$cronjob" ) | crontab -
    @endforeach
    echo "# Done addCrons"
@endtask

@task('cron_remove', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    echo "# remove crontabs"
    @foreach ($crons as $cron_name => $cron_job)
        croncmd="{{$cron_job[1]}}"
        ( crontab -l | grep -v -F "$cron_name" ) | crontab -
        ( crontab -l | grep -v -F "$croncmd" ) | crontab -
    @endforeach
    echo "# Done rmCrons"
@endtask

@task('apachelogswatch', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    tail -f /var/log/virtualmin/{{$domain}}_error_log
@endtask

@task('laralogswatch', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    tail -f storage/logs/laravel.log
@endtask

@task('catenv', ['on' => 'local'])
    cat .env
@endtask

@task('link_klaravel', ['on' => 'local'])
    cd vendor/ksoft/
    rm -rf klaravel/
    ln -s ~/Developer/Laravel/Laravel-Plugins/klaravel/ ./klaravel
    ls -la
    cd ..
    cd ..
@endtask

@task('klean', ['on' => 'local'])
    php artisan clear-compiled
    php artisan migrate:refresh
    php artisan db:seed
@endtask

@task('dump', ['on' => 'local'])
    composer dump-autoload
    php artisan config:clear
    php artisan view:clear
    php artisan cache:clear
    php artisan clear-compiled
@endtask

@task('up', ['on' => ['remote']])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan up
@endtask

@task('down', ['on' => ['remote']])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan down
@endtask

@story('migrate')
    warning
    run_migration_seed
@endstory

@task('warning', ['on' => ['local']])
    echo "---------------------------------------------"
    echo "---------------------------------------------"
    echo "--------------- "ATTENTION" -----------------"
    echo "---------------------------------------------"
    echo "---------------------------------------------"
    echo "-------------  Are you sure?  ---------------"
    echo "---------------------------------------------"
    echo "---------------------------------------------"
@endtask

@task('run_migration_seed', ['on' => 'remote', 'confirm' => true])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan migrate --force
    @if ($seed)
        php artisan db:seed --force
    @endif
@endtask

@task('m:r', ['on' => 'remote'])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan migrate:rollback --force
@endtask

@task('rights', ['on' => 'remote', 'confirm' => true])
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    chmod -R 0777 public/upload app/storage
    find . -type d -exec chmod 775 {} \;
    find . -type f -exec chmod 664 {} \;
@endtask

@task('reload_php_server', ['on' => 'remote'])
    /etc/rc.d/init.d/php-fcgi-{{str_replace('.', '-', $domain)}} restart
@endtask

@task('reload_nginx', ['on' => 'remote'])
    systemctl restart nginx
@endtask

@task('optimizeInstallation', ['on' => 'remote'])
    echo 'start optimizeInstallation'
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan clear-compiled
    php artisan optimize
@endtask

@task('backupDatabase', ['on' => 'remote'])
    echo 'start backupDatabase'
    su -l {{ $username }}
    {{ $prepare_php }}
    cd ~/{{ $repo_name }}
    php artisan backup:run
@endtask
