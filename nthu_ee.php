<?php

final class nthu_ee extends rcube_plugin
{
    /**
     * We only load this plugin in all task.
     *
     * @var string
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
        $RCMAIL = rcmail::get_instance();
        $skin = $RCMAIL->config->get('skin');

        $this->load_plugin_config();

        $this->add_texts('locales', true);

        $this->add_plugin_assets();
        $this->add_plugin_login_page_button($skin);
        $this->add_plugin_taskbar_button($skin);
    }

    /**
     * Add plugin assets.
     */
    private function add_plugin_assets(): void
    {
        $local_skin_path = $this->local_skin_path();

        $this->include_stylesheet("{$local_skin_path}/main.css");
        $this->include_script('js/main.min.js');
    }

    /**
     * Add the plugin login page button.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_login_page_button(string $skin): void
    {
        $btns = [
            [
                'label' => __CLASS__ . '.manual',
                'title' => __CLASS__ . '.open_manual',
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ],
        ];

        $btns = \array_map(function ($btn) use ($skin) {
            $btn['type'] = 'link';
            $btn['class'] = $btn['class'] ?? '';

            // should always has 'support-link' class
            if (!preg_match('/(^|\s)support-link($|\s)/uS', $btn['class'])) {
                $btn['class'] .= ' support-link';
            }

            if ($skin === 'elastic') {
                $btn['class'] .= ' badge badge-secondary';
                $btn['data-toggle'] = 'tooltip';
            }

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'loginfooter');
        }
    }

    /**
     * Add the plugin taskbar button.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_taskbar_button(string $skin): void
    {
        $btns = [
            [
                'label' => __CLASS__ . '.manual',
                'title' => __CLASS__ . '.open_manual',
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ],
        ];

        $btns = \array_map(function ($btn) use ($skin) {
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
