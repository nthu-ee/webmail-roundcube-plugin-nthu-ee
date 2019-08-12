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
        $this->add_plugin_buttons_loginfooter([
            [
                'label' => "{$this->ID}.old_webmail",
                'title' => "{$this->ID}.old_webmail_never_receive_new_mail",
                'href' => 'https://rcmail.ee.nthu.edu.tw',
                'badgeType' => 'danger',
            ], [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ],
        ], $skin);

        $this->add_plugin_buttons_taskbar([
            [
                'label' => "{$this->ID}.manual",
                'title' => "{$this->ID}.open_manual",
                'href' => 'skins/.manual/',
                'target' => '_blank',
            ],
        ], $skin);
    }

    /**
     * Add the plugin buttons to loginfooter.
     *
     * @param array  $btns the buttons
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_loginfooter(array $btns, string $skin): void
    {
        $btns = \array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['classArray'] = $this->class_string_to_array($btn['class'] ?? '');
            $btn['innerclassArray'] = $this->class_string_to_array($btn['innerclass'] ?? '');
            $btn['badgeType'] = $btn['badgeType'] ?? 'secondary';

            // should always has 'support-link' class
            $btn['classArray'][] = 'support-link';

            if ($skin === 'elastic') {
                $btn['classArray'][] = 'badge';
                $btn['classArray'][] = "badge-{$btn['badgeType']}";

                if (!isset($btn['data-toggle']) && isset($btn['title'])) {
                    $btn['data-toggle'] = 'tooltip';
                }
            }

            $btn['class'] = $this->class_array_to_string($btn['classArray']);
            $btn['innerclass'] = $this->class_array_to_string($btn['innerclassArray']);

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'loginfooter');
        }
    }

    /**
     * Add the plugin buttons to taskbar.
     *
     * @param array  $btns the buttons
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_taskbar(array $btns, string $skin): void
    {
        $btns = \array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['classArray'] = $this->class_string_to_array($btn['class'] ?? '');
            $btn['innerclassArray'] = $this->class_string_to_array($btn['innerclass'] ?? '');

            switch ($skin) {
                case 'classic':
                    $btn['classArray'][] = 'button-nthu-ee';
                    break;
                case 'elastic':
                    $btn['classArray'][] = 'nthu-ee';
                    $btn['classArray'][] = 'manual';
                    $btn['innerclassArray'][] = 'inner';
                    break;
                case 'larry':
                    $btn['classArray'][] = 'button-nthu-ee';
                    $btn['innerclassArray'][] = 'button-inner';
                    break;
                default:
                    break;
            }

            $btn['class'] = $this->class_array_to_string($btn['classArray']);
            $btn['innerclass'] = $this->class_array_to_string($btn['innerclassArray']);

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'taskbar');
        }
    }

    /**
     * Convert the array of classes to HTML class attribute.
     *
     * @param array $class_array the array of classes
     *
     * @return string
     */
    private function class_array_to_string(array $class_array): string
    {
        return \implode(' ', $this->class_string_to_array(\implode(' ', $class_array)));
    }

    /**
     * Convert the HTML class attribute to an array of classes.
     *
     * @param string $class_string the HTML class attribute
     *
     * @return array
     */
    private function class_string_to_array(string $class_string): array
    {
        return \preg_match_all('/[^\s]++/uS', $class_string, $matches)
            ? \array_unique($matches[0])
            : [];
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
