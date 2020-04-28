<?php

declare(strict_types=1);

include __DIR__ . '/lib/vendor/autoload.php';

use Jfcherng\Roundcube\Plugin\NthuEe\RoundcubePluginTrait;

final class nthu_ee extends rcube_plugin
{
    use RoundcubePluginTrait;

    /**
     * {@inheritdoc}
     */
    public $task = '';

    /**
     * Plugin actions and handlers.
     *
     * @var array<string,string>
     */
    public $actions = [];

    /**
     * The plugin configuration.
     *
     * @var array
     */
    private $config = [];

    /**
     * The plugin user preferences.
     *
     * @var array
     */
    private $prefs = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        $this->loadPluginConfigurations();
        $this->loadPluginPreferences();
        $this->registerPluginActions();

        $this->add_texts('localization/', false);
        $this->include_stylesheet($this->local_skin_path() . '/main.css');
        $this->include_script('assets/main.min.js');

        $this->addPluginButtons();
    }

    /**
     * Add plugin buttons.
     */
    private function addPluginButtons(): void
    {
        $this->add_buttons_loginfooter([
            [
                'label' => "{$this->ID}.old_webmail",
                'title' => "{$this->ID}.old_webmail_never_receive_new_mail",
                'href' => 'https://rcmail.ee.nthu.edu.tw',
                'badgeType' => 'danger',
            ], [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => $this->url('manual/'),
                'target' => '_blank',
            ], [
                'label' => "{$this->ID}.department_website",
                'title' => "{$this->ID}.open_department_website",
                'href' => 'http://web.ee.nthu.edu.tw',
                'target' => '_blank',
            ],
        ], 'elastic' /* the login/logout page is always "elastic" */);

        $this->add_buttons_taskbar([
            [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => $this->url('manual/'),
                'target' => '_blank',
            ],
        ]);
    }
}
