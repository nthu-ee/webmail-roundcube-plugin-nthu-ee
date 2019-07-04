<?php

declare(strict_types=1);

final class nthu_ee extends rcube_plugin
{
    /**
     * {@inheritdoc}
     */
    public $task = '';

    /**
     * The loaded configuration.
     *
     * @var rcube_config
     */
    private $config;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->load_plugin_config();

        $skin = $this->config->get('skin');
        $local_skin_path = $this->local_skin_path();

        $this->add_texts('locales', true);

        $this->add_plugin_assets($local_skin_path);
        $this->add_plugin_buttons($skin);
    }

    /**
     * Add plugin assets.
     *
     * @param string $local_skin_path the local skin path such as "skins/elastic"
     */
    private function add_plugin_assets(string $local_skin_path): void
    {
        $this->include_stylesheet("{$local_skin_path}/main.css");
        $this->include_script('js/main.min.js');
    }

    /**
     * Add plugin buttons.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons(string $skin): void
    {
        $this->add_plugin_buttons_loginfooter($skin);
        $this->add_plugin_buttons_taskbar($skin);
    }

    /**
     * Add the plugin buttons to loginfooter.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_loginfooter(string $skin): void
    {
        $btns = [
            [
                'label' => "{$this->ID}.old_webmail",
                'title' => "{$this->ID}.old_webmail_never_receive_new_mail",
                'href' => 'https://rcmail.ee.nthu.edu.tw',
                'badgeType' => 'danger',
            ],
            [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ],
        ];

        $btns = \array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['class'] = $btn['class'] ?? '';
            $btn['badgeType'] = $btn['badgeType'] ?? 'secondary';

            // should always has 'support-link' class
            if (!\preg_match('/(^|\s)support-link($|\s)/uS', $btn['class'])) {
                $btn['class'] .= ' support-link';
            }

            if ($skin === 'elastic') {
                $btn['class'] .= " badge badge-{$btn['badgeType']}";

                if (!isset($btn['data-toggle']) && isset($btn['title'])) {
                    $btn['data-toggle'] = 'tooltip';
                }
            }

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'loginfooter');
        }
    }

    /**
     * Add the plugin buttons to taskbar.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_taskbar(string $skin): void
    {
        $btns = [
            [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ],
        ];

        $btns = \array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['class'] = $btn['class'] ?? '';
            $btn['innerclass'] = $btn['innerclass'] ?? '';

            if ($skin === 'classic') {
                $btn['class'] .= ' button-nthu-ee';

                return $btn;
            }

            if ($skin === 'elastic') {
                $btn['class'] .= ' nthu-ee manual';
                $btn['innerclass'] .= ' inner';

                return $btn;
            }

            if ($skin === 'larry') {
                $btn['class'] .= ' button-nthu-ee';
                $btn['innerclass'] .= ' button-inner';

                return $btn;
            }

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'taskbar');
        }
    }

    /**
     * Load plugin configuration.
     */
    private function load_plugin_config(): void
    {
        $RCMAIL = rcmail::get_instance();

        $this->load_config('config.inc.php.dist');
        $this->load_config('config.inc.php');

        $this->config = $RCMAIL->config;
    }
}
