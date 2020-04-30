<?php

declare(strict_types=1);

include __DIR__ . '/lib/vendor/autoload.php';

use Jfcherng\Roundcube\Plugin\NthuEe\AbstractRoundcubePlugin;

final class nthu_ee extends AbstractRoundcubePlugin
{
    /**
     * {@inheritdoc}
     */
    public $task = '';

    /**
     * {@inheritdoc}
     */
    public $actions = [];

    /**
     * {@inheritdoc}
     */
    public $hooks = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this->include_stylesheet("{$this->skinPath}/main.css");
        $this->include_script('assets/main.min.js');

        $this->addPluginButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultPluginPreferences(): array
    {
        return [];
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
